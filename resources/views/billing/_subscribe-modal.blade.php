<div
    class="billing-modal-overlay"
    x-show="open"
    x-cloak
    x-transition.opacity
    x-on:keydown.escape.window="closeModal()"
    x-on:click="closeModal()"
>
    <div
        class="billing-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="billing-modal-title"
        x-on:click.stop
        x-transition
    >
        <div class="billing-modal-head">
            <div>
                <h3 id="billing-modal-title">Berlangganan — <span x-text="selectedPlan?.name ?? ''"></span></h3>
                <p>Transfer sesuai nominal, lalu upload bukti pembayaran.</p>
            </div>
            <button type="button" class="billing-modal-close" x-on:click="closeModal()" aria-label="Tutup">&times;</button>
        </div>

        <form
            method="POST"
            action="{{ route('billing.payment-proof.store') }}"
            enctype="multipart/form-data"
            class="billing-modal-body"
        >
            @csrf
            <input type="hidden" name="plan_id" :value="selectedPlan?.id ?? ''">

            <div class="billing-bank-box">
                <strong>Rekening transfer</strong>
                <span x-text="bank.bank"></span> · <span x-text="bank.account_number"></span><br>
                a/n <span x-text="bank.account_name"></span>
            </div>

            <div class="billing-cycle-grid">
                <label class="billing-cycle-option">
                    <input type="radio" name="billing_cycle" value="monthly" x-model="billingCycle">
                    <span class="billing-cycle-card">
                        <strong>Bulanan</strong>
                        <span x-text="formatRp(selectedPlan?.price_monthly_idr ?? 0) + '/bulan'"></span>
                    </span>
                </label>
                <label class="billing-cycle-option">
                    <input type="radio" name="billing_cycle" value="yearly" x-model="billingCycle">
                    <span class="billing-cycle-card">
                        <span class="billing-cycle-badge" x-show="yearlySavings > 0" x-cloak x-text="'Hemat ' + yearlyDiscountPercent + '%'"></span>
                        <strong>Tahunan</strong>
                        <span x-text="formatRp(selectedPlan?.price_yearly_idr ?? 0) + '/tahun'"></span>
                        <span class="billing-cycle-sub" x-show="yearlySavings > 0" x-cloak>
                            Setara <span x-text="formatRp(yearlyEquivalentMonthly)"></span>/bln · hemat <span x-text="formatRp(yearlySavings)"></span>
                        </span>
                    </span>
                </label>
            </div>

            <div class="billing-modal-amount">
                <template x-if="billingCycle === 'yearly' && yearlySavings > 0">
                    <p class="billing-modal-strike">Harga 12 bulan: <span x-text="formatRp(fullYearPrice)"></span></p>
                </template>
                Total transfer:
                <strong x-text="formatRp(selectedAmount)"></strong>
            </div>

            <div class="vx-field mb-4">
                <label class="vx-label" for="reference_number">No. referensi transfer (opsional)</label>
                <input id="reference_number" type="text" name="reference_number" class="vx-input" placeholder="Contoh: TRF123456" maxlength="100" value="{{ old('reference_number') }}">
            </div>

            <div class="vx-field mb-4">
                <label class="vx-label" for="note">Catatan (opsional)</label>
                <textarea id="note" name="note" class="vx-input" rows="2" placeholder="Nama pengirim, bank asal, dll.">{{ old('note') }}</textarea>
            </div>

            <div class="vx-field mb-2">
                <label class="vx-label" for="proof">Bukti pembayaran *</label>
                <input id="proof" type="file" name="proof" class="vx-input" accept=".jpg,.jpeg,.png,.pdf" required>
                @error('proof')
                    <p class="vx-error">{{ $message }}</p>
                @enderror
                @error('plan_id')
                    <p class="vx-error">{{ $message }}</p>
                @enderror
                @error('billing_cycle')
                    <p class="vx-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="billing-modal-actions">
                <button type="button" class="vx-btn vx-btn-ghost" x-on:click="closeModal()">Batal</button>
                <button type="submit" class="vx-btn vx-btn-primary">Kirim Bukti Pembayaran</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('billingPage', (config) => ({
            open: false,
            selectedPlan: null,
            billingCycle: 'monthly',
            bank: config.bank,
            hasPending: config.hasPending,
            init() {
                if (config.openPlanId) {
                    const plan = config.plans.find(p => p.id === config.openPlanId);
                    if (plan) {
                        this.openModal(plan);
                    }
                }
            },
            openModal(plan) {
                if (this.hasPending) {
                    return;
                }
                this.selectedPlan = plan;
                this.billingCycle = plan.yearly_savings_idr > 0 ? 'yearly' : 'monthly';
                this.open = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.open = false;
                document.body.style.overflow = '';
            },
            get selectedAmount() {
                if (!this.selectedPlan) {
                    return 0;
                }
                return this.billingCycle === 'yearly'
                    ? this.selectedPlan.price_yearly_idr
                    : this.selectedPlan.price_monthly_idr;
            },
            get fullYearPrice() {
                return this.selectedPlan?.full_year_idr ?? 0;
            },
            get yearlySavings() {
                return this.selectedPlan?.yearly_savings_idr ?? 0;
            },
            get yearlyDiscountPercent() {
                return this.selectedPlan?.yearly_discount_percent ?? 0;
            },
            get yearlyEquivalentMonthly() {
                return this.selectedPlan?.yearly_equivalent_monthly_idr ?? 0;
            },
            formatRp(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
            },
        }));
    });
</script>
@endpush
