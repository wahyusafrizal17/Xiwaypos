{{-- Gaya POS identik hitam-merah Xiway POS, class prefix pc-* --}}
<style>
    .pos-coffee {
        height: 100svh;
        max-height: 100svh;
        --cream: #f5f5f5;
        --warm-white: #ffffff;
        --espresso: #111111;
        --brown-dark: #1a1a1a;
        --brown-mid: #4b5563;
        --brown-light: #6b7280;
        --caramel: #E01010;
        --caramel-dark: #C40D0D;
        --caramel-light: #fff0f0;
        --sage: #374151;
        --sage-light: #f3f4f6;
        --border: rgba(0, 0, 0, 0.08);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 8px 32px rgba(0, 0, 0, 0.12);
        --radius: 16px;
        --radius-sm: 10px;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        background: var(--cream);
        color: var(--espresso);
        -webkit-font-smoothing: antialiased;
    }

    .pc-header {
        background: var(--espresso);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 clamp(14px, 3vw, 28px);
        height: 60px;
        flex-shrink: 0;
        gap: 12px;
        border-bottom: 3px solid var(--caramel);
    }
    .pc-header .brand {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        line-height: 1;
        text-decoration: none;
    }
    .pc-header .brand .xiway-brand-lockup-logo {
        display: block;
        height: clamp(30px, 4.5vw, 38px);
        width: auto;
        max-width: 44px;
        object-fit: contain;
        object-position: left center;
    }
    .pc-header .brand .xiway-brand-lockup-text {
        font-size: clamp(18px, 3.2vw, 22px);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.03em;
        line-height: 1;
        white-space: nowrap;
    }
    .pc-header .brand .xiway-brand-lockup-text em {
        color: var(--caramel);
        font-style: normal;
    }
    .pc-header .brand .xiway-brand-lockup {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pc-header-meta {
        display: flex;
        align-items: center;
        gap: clamp(10px, 2vw, 18px);
        font-size: 12px;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.72);
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .pc-header-meta strong { color: #fff; font-weight: 600; }
    .pc-header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pc-header-actions a,
    .pc-header-actions button {
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        color: #fff;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: transparent;
        cursor: pointer;
        transition: background 0.15s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pc-header-actions a svg { display: inline-block; }
    .pc-header-actions a.pc-nav-active {
        background: rgba(224, 16, 16, 0.22);
        border-color: rgba(224, 16, 16, 0.55);
        color: #fff;
    }
    .pc-header-badge {
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 999px;
        background: var(--caramel);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
    }
    .pc-header-actions a:hover,
    .pc-header-actions button:hover { background: rgba(255,255,255,0.08); }

    .pc-header-actions .pc-logout-btn {
        padding: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        min-height: 36px;
    }
    .pc-header-logout-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .pc-header-logout-icon path {
        stroke: currentColor;
    }

    .pc-main {
        display: grid;
        grid-template-columns: 1fr minmax(280px, 360px);
        grid-template-rows: minmax(0, 1fr);
        flex: 1 1 0;
        overflow: hidden;
        min-height: 0;
        align-items: stretch;
    }

    .pc-left {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 20px 20px 20px 24px;
        gap: 16px;
        min-width: 0;
        min-height: 0;
    }

    .pc-edit-open-bill-banner {
        flex-shrink: 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        border: 1.5px solid #fca5a5;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
    }
    .pc-edit-open-bill-text {
        font-size: 12px;
        color: var(--brown-dark);
        line-height: 1.35;
    }
    .pc-edit-open-bill-text strong {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--caramel);
        margin-bottom: 2px;
    }
    .pc-edit-open-bill-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pc-edit-open-bill-save {
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--caramel);
        color: #fff;
        cursor: pointer;
    }
    .pc-edit-open-bill-save:hover:not(:disabled) {
        background: var(--caramel-dark);
    }
    .pc-edit-open-bill-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .pc-edit-open-bill-cancel {
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--warm-white);
        color: var(--brown-mid);
        cursor: pointer;
    }
    .pc-edit-open-bill-cancel:hover:not(:disabled) {
        border-color: var(--brown-light);
        color: var(--espresso);
    }

    .pc-categories {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 4px;
    }
    .pc-categories::-webkit-scrollbar { display: none; }

    .pc-cat-btn {
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 50px;
        border: 1.5px solid var(--border);
        background: var(--warm-white);
        color: var(--brown-mid);
        cursor: pointer;
        transition: all 0.18s ease;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .pc-cat-btn:hover { border-color: var(--brown-light); color: var(--brown-dark); }
    .pc-cat-btn.active {
        background: var(--caramel);
        color: #fff;
        border-color: var(--caramel);
    }

    .pc-search-wrap { position: relative; flex-shrink: 0; }
    .pc-search-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--brown-light);
        pointer-events: none;
    }
    .pc-search-input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--warm-white);
        font-family: inherit;
        font-size: 14px;
        color: var(--espresso);
        outline: none;
        transition: border-color 0.18s;
    }
    .pc-search-input::placeholder { color: var(--brown-light); }
    .pc-search-input:focus { border-color: var(--caramel); }

    .pc-menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(156px, 1fr));
        gap: 18px;
        align-items: start;
        align-content: start;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 4px 20px 0;
        flex: 1 1 0;
        min-height: 0;
    }
    @media (min-width: 1280px) {
        .pc-menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(172px, 1fr));
            gap: 20px;
        }
    }
    .pc-menu-grid::-webkit-scrollbar { width: 5px; }
    .pc-menu-grid::-webkit-scrollbar-track { background: transparent; }
    .pc-menu-grid::-webkit-scrollbar-thumb { background: rgba(224, 16, 16, 0.18); border-radius: 999px; }

    .pc-menu-card {
        background: var(--warm-white);
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        padding: 0;
        margin: 0;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        text-align: left;
        height: fit-content;
        align-self: start;
        width: 100%;
        min-width: 0;
        font: inherit;
        appearance: none;
        -webkit-appearance: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }
    .pc-menu-card:hover:not(:disabled) {
        border-color: rgba(224, 16, 16, 0.22);
        box-shadow: 0 6px 18px rgba(224, 16, 16, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    .pc-menu-card:active:not(:disabled) {
        transform: translateY(-1px) scale(0.985);
    }
    .pc-menu-card:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(224, 16, 16, 0.22), 0 8px 24px rgba(224, 16, 16, 0.1);
    }
    .pc-menu-card:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .pc-card-thumb {
        position: relative;
        height: 172px;
        width: 100%;
        background: linear-gradient(160deg, #fafafa 0%, #f1f5f9 100%);
        overflow: hidden;
        flex-shrink: 0;
    }
    .pc-card-thumb img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.35s ease;
    }
    .pc-menu-card:hover:not(:disabled) .pc-card-thumb img {
        transform: scale(1.05);
    }
    .pc-card-emoji {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        line-height: 1;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.06));
    }
    .pc-card-body {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 10px 12px 12px;
        min-width: 0;
    }
    .pc-card-name {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--espresso);
        line-height: 1.35;
        letter-spacing: -0.01em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .pc-card-desc { font-size: 11px; color: var(--brown-light); line-height: 1.35; }
    .pc-card-price {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--caramel);
        letter-spacing: -0.02em;
    }
    .pc-card-tag {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 1;
        font-size: 9px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        color: #64748b;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        max-width: calc(100% - 16px);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pc-right {
        background: var(--warm-white);
        border-left: 1.5px solid var(--border);
        overflow: hidden;
        min-height: 0;
        min-width: 0;
    }

  .pc-right.pc-right-sidebar {
        display: flex;
        flex-direction: column;
    }

    /* Panel pesanan: header + isi scroll + footer selalu terlihat */
    .pc-cart-panel {
        display: grid;
        grid-template-rows: auto minmax(0, 1fr) auto;
        flex: 1 1 0;
        min-height: 0;
        height: 100%;
        overflow: hidden;
    }

    .pc-order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px 12px;
        border-bottom: 1.5px solid var(--border);
        flex-shrink: 0;
    }
    .pc-order-header h2 {
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        font-size: 17px;
        font-weight: 500;
        color: var(--espresso);
        margin: 0;
        line-height: 1.2;
    }
    .pc-order-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
        justify-content: flex-end;
    }

    .pc-order-type-btn {
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--brown-mid);
        cursor: pointer;
        transition: all 0.18s;
    }
    .pc-order-type-btn.active {
        background: var(--caramel);
        color: #fff;
        border-color: var(--caramel);
    }

    .pc-cart-items {
        overflow-y: auto;
        overflow-x: hidden;
        padding: 10px 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 0;
    }
    .pc-cart-items::-webkit-scrollbar { width: 3px; }
    .pc-cart-items::-webkit-scrollbar-thumb { background: var(--caramel-light); border-radius: 4px; }

    .pc-cart-empty {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: var(--brown-light);
        font-size: 13px;
        text-align: center;
        padding: 16px 12px;
    }
    .pc-cart-empty .pc-empty-icon { font-size: 32px; opacity: 0.4; }

    .pc-cart-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        background: var(--cream);
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
    }
    .pc-cart-thumb {
        width: 40px; height: 40px;
        border-radius: 8px;
        background: var(--warm-white);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-size: 18px;
    }
    .pc-cart-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .pc-cart-info { flex: 1; min-width: 0; }
    .pc-cart-name { font-size: 12px; font-weight: 500; color: var(--espresso); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pc-cart-price { font-size: 11px; color: var(--brown-mid); }
    .pc-qty-ctrl { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
    .pc-qty-btn {
        width: 26px; height: 26px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        background: var(--warm-white);
        cursor: pointer;
        font-size: 15px;
        display: flex; align-items: center; justify-content: center;
        color: var(--brown-dark);
        transition: all 0.15s;
        line-height: 1;
    }
    .pc-qty-btn:hover { background: var(--caramel); color: #fff; border-color: var(--caramel); }
    .pc-qty-num { font-size: 13px; font-weight: 500; min-width: 18px; text-align: center; color: var(--espresso); }

    .pc-remove-link {
        font-size: 10px;
        font-weight: 500;
        color: var(--caramel);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0 0 0 4px;
        text-decoration: underline;
    }

    .pc-order-summary {
        border-top: 1.5px solid var(--border);
        padding: 10px 18px max(10px, env(safe-area-inset-bottom, 0px));
        background: var(--warm-white);
        box-shadow: 0 -8px 24px rgba(15, 23, 42, 0.06);
        z-index: 2;
    }
    .pc-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--brown-mid);
        padding: 3px 0;
    }
    .pc-summary-row.pc-total {
        font-size: 15px;
        font-weight: 500;
        color: var(--espresso);
        padding-top: 0;
        margin-top: 0;
    }

    .pc-pay-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
    }
    .pc-pay-modal {
        width: 100%;
        max-width: 380px;
        max-height: min(90dvh, 560px);
        overflow-y: auto;
        background: var(--warm-white);
        border-radius: var(--radius);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        padding: 18px 18px 16px;
    }
    .pc-pay-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1.5px solid var(--border);
    }
    .pc-pay-modal-head .pc-pay-modal-title {
        margin: 0;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        font-size: 18px;
        font-weight: 500;
        color: var(--espresso);
        line-height: 1.25;
    }
    .pc-pay-modal-tagihan-block {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .pc-pay-modal-tagihan-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--brown-mid);
        letter-spacing: 0.02em;
    }
    .pc-pay-modal-tagihan-amount {
        font-size: 15px;
        font-weight: 600;
        color: var(--espresso);
        font-variant-numeric: tabular-nums;
    }
    .pc-pay-modal-section-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--brown-mid);
        margin: 0 0 8px;
        display: block;
    }
    .pc-pay-modal-name-wrap {
        margin-bottom: 14px;
    }
    .pc-pay-modal-name-input {
        width: 100%;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--cream);
        color: var(--espresso);
    }
    .pc-pay-modal-name-input::placeholder { color: var(--brown-light); }
    .pc-pay-modal-name-input:focus {
        outline: none;
        border-color: var(--caramel);
    }
    .pc-split-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    .pc-pay-modal-select {
        flex: 0 0 auto;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--cream);
        color: var(--espresso);
    }
    .pc-split-amount {
        flex: 1;
        min-width: 0;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 10px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--cream);
        color: var(--espresso);
        text-align: right;
    }
    .pc-split-amount:focus,
    .pc-pay-modal-select:focus {
        outline: none;
        border-color: var(--caramel);
    }
    .pc-split-remove {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--cream);
        color: var(--brown-mid);
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        transition: background 0.15s;
    }
    .pc-split-remove:hover { background: var(--sage-light); color: var(--espresso); }
    .pc-split-add {
        width: 100%;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        padding: 8px;
        margin-bottom: 12px;
        border-radius: var(--radius-sm);
        border: 1.5px dashed var(--brown-light);
        background: transparent;
        color: var(--brown-mid);
        cursor: pointer;
    }
    .pc-split-add:hover { border-color: var(--caramel); color: var(--espresso); }
    .pc-pay-modal-summary {
        background: var(--sage-light);
        border-radius: var(--radius-sm);
        padding: 10px 12px;
        margin-bottom: 14px;
    }
    .pc-pay-modal-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--brown-dark);
        padding: 2px 0;
    }
    .pc-pay-modal-summary-row span:last-child {
        font-weight: 600;
        color: var(--espresso);
    }
    .pc-pay-modal-settle-hint {
        width: 100%;
        margin: 4px 0 0;
        font-size: 11px;
        color: var(--brown-light);
        text-align: right;
    }
    .pc-pay-modal-actions {
        display: flex;
        gap: 10px;
    }
    .pc-pay-modal-confirm {
        flex: 1;
        min-width: 0;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        padding: 11px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--caramel);
        color: white;
        cursor: pointer;
        transition: background 0.15s;
    }
    .pc-pay-modal-confirm:hover:not(:disabled) { background: var(--caramel-dark); }
    .pc-pay-modal-confirm:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
    .pc-pay-modal-open-bill {
        flex: 1;
        min-width: 0;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        padding: 11px;
        border-radius: var(--radius-sm);
        border: 1.5px solid #f59e0b;
        background: #fffbeb;
        color: #b45309;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .pc-pay-modal-open-bill:hover:not(:disabled) {
        background: #fef3c7;
        border-color: #d97706;
    }
    .pc-pay-modal-open-bill:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .pc-varian-overlay {
        position: fixed;
        inset: 0;
        z-index: 65;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
    }
    .pc-varian-modal {
        width: 100%;
        max-width: 320px;
        background: var(--warm-white);
        border-radius: var(--radius);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        padding: 20px 18px 16px;
        text-align: center;
    }
    .pc-varian-title {
        margin: 0 0 6px;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        font-size: 18px;
        font-weight: 500;
        color: var(--espresso);
    }
    .pc-varian-product {
        margin: 0 0 16px;
        font-size: 13px;
        font-weight: 600;
        color: var(--brown-mid);
    }
    .pc-varian-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }
    .pc-varian-pick {
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        padding: 14px 12px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
    }
    .pc-varian-ice {
        background: #e0f2fe;
        border-color: #7dd3fc;
        color: #0369a1;
    }
    .pc-varian-ice:hover {
        background: #bae6fd;
        border-color: #38bdf8;
    }
    .pc-varian-hot {
        background: #ffedd5;
        border-color: #fdba74;
        color: #c2410c;
    }
    .pc-varian-hot:hover {
        background: #fed7aa;
        border-color: #fb923c;
    }
    .pc-varian-cancel {
        width: 100%;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        padding: 10px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--brown-mid);
        cursor: pointer;
    }
    .pc-varian-cancel:hover {
        background: var(--sage-light);
        color: var(--espresso);
    }

    .pc-addon-overlay {
        position: fixed;
        inset: 0;
        z-index: 66;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
    }
    .pc-addon-modal {
        width: 100%;
        max-width: 360px;
        max-height: min(88dvh, 520px);
        overflow-y: auto;
        background: var(--warm-white);
        border-radius: var(--radius);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        padding: 20px 18px 16px;
    }
    .pc-addon-title {
        margin: 0 0 6px;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        font-size: 18px;
        font-weight: 500;
        color: var(--espresso);
    }
    .pc-addon-product {
        margin: 0 0 6px;
        font-size: 14px;
        font-weight: 600;
        color: var(--brown-mid);
    }
    .pc-addon-hint {
        margin: 0 0 14px;
        font-size: 12px;
        color: var(--brown-light);
    }
    .pc-addon-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
    }
    .pc-addon-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--cream);
        cursor: pointer;
        transition: border-color 0.15s ease;
    }
    .pc-addon-row:has(.pc-addon-check:checked) {
        border-color: var(--caramel);
        background: #fff;
    }
    .pc-addon-check {
        margin-top: 2px;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        accent-color: var(--caramel);
        cursor: pointer;
    }
    .pc-addon-row-text {
        flex: 1;
        min-width: 0;
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 10px;
    }
    .pc-addon-label { font-size: 13px; font-weight: 600; color: var(--espresso); }
    .pc-addon-price { font-size: 12px; font-weight: 600; color: var(--brown-mid); white-space: nowrap; }
    .pc-addon-extra {
        margin: 0 0 14px;
        font-size: 12px;
        color: var(--brown-mid);
    }
    .pc-addon-extra strong { color: var(--espresso); }
    .pc-addon-actions {
        display: flex;
        gap: 10px;
    }
    .pc-addon-cancel {
        flex: 1;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        padding: 11px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--brown-mid);
        cursor: pointer;
    }
    .pc-addon-cancel:hover { background: var(--sage-light); color: var(--espresso); }
    .pc-addon-confirm {
        flex: 1.2;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        padding: 11px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--caramel);
        color: #fff;
        cursor: pointer;
    }
    .pc-addon-confirm:hover { background: var(--caramel-dark); }
    .pc-cart-addons {
        display: block;
        margin-top: 2px;
        font-size: 10px;
        font-weight: 600;
        color: var(--brown-mid);
        line-height: 1.35;
    }

    .pc-cart-suhu {
        display: inline-block;
        margin-top: 2px;
        margin-bottom: 2px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        padding: 2px 6px;
        border-radius: 4px;
        background: var(--caramel-light);
        color: var(--caramel);
    }

    .pc-open-bill-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--warm-white);
    }
    .pc-open-bill-info { flex: 1; min-width: 0; }
    .pc-open-bill-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--espresso);
        margin: 0 0 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pc-open-bill-id { font-size: 12px; font-weight: 700; color: var(--brown-dark); margin: 0; }
    .pc-open-bill-meta { font-size: 11px; color: var(--brown-mid); margin: 2px 0 0; }
    .pc-open-bill-preview {
        font-size: 10px;
        color: var(--brown-light);
        margin: 4px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pc-open-bill-pay {
        flex-shrink: 0;
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        background: var(--caramel);
        color: #fff;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }
    .pc-open-bill-pay:hover { background: var(--caramel-dark); }

    .pc-checkout-btn {
        width: 100%;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        padding: 13px;
        border-radius: var(--radius);
        background: var(--caramel);
        color: white;
        border: none;
        cursor: pointer;
        letter-spacing: 0.2px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .pc-checkout-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .pc-checkout-icon path {
        stroke: currentColor;
    }
    .pc-checkout-btn:hover:not(:disabled) { background: var(--caramel-dark); transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .pc-checkout-btn:active:not(:disabled) { transform: translateY(0); }
    .pc-checkout-btn:disabled {
        background: var(--caramel-light);
        color: var(--brown-light);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Tablet landscape dengan sidebar (1024px+) */
    @media (min-width: 1024px) and (max-width: 1366px) {
        .pos-coffee {
            height: 100svh;
            max-height: 100svh;
        }

        .pc-header {
            height: 52px;
            min-height: 52px;
            max-height: 52px;
        }

        .pc-header-meta {
            flex-wrap: nowrap;
            font-size: 11px;
        }

        .pc-header-actions a,
        .pc-header-actions button {
            padding: 5px 8px;
            font-size: 11px;
        }

        .pc-main {
            grid-template-columns: 1fr minmax(248px, 280px);
        }

        .pc-order-header {
            padding: 12px 14px 8px;
        }

        .pc-order-header h2 {
            font-size: 15px;
        }

        .pc-cart-items {
            padding: 8px 14px;
        }

        .pc-cart-empty {
            padding: 12px 8px;
        }

        .pc-cart-empty .pc-empty-icon {
            font-size: 28px;
        }

        .pc-order-summary {
            padding: 8px 14px max(12px, env(safe-area-inset-bottom, 12px));
        }

        .pc-checkout-btn {
            padding: 11px 14px;
            font-size: 13px;
            margin-top: 8px !important;
        }

        .pc-menu-grid {
            column-gap: 16px;
            row-gap: 28px;
        }

        .pc-card-thumb {
            height: 148px;
        }

        .pc-card-emoji {
            font-size: 48px;
        }

        .pc-card-body {
            padding: 9px 11px 11px;
        }
    }

    /* Tablet portrait & medium screens */
    @media (min-width: 768px) and (max-width: 1023px) {
        .pc-menu-grid {
            column-gap: 16px;
            row-gap: 26px;
        }

        .pc-card-thumb {
            height: 148px;
        }

        .pc-card-emoji {
            font-size: 48px;
        }
    }

    @supports not (height: 100svh) {
        .pos-coffee {
            height: 100dvh;
            max-height: 100dvh;
        }
    }

    @media (max-width: 1023px) {
        .pc-main { grid-template-columns: 1fr; }
        .pc-right.pc-right-sidebar { display: none; }
    }

    /* SweetAlert2 POS overrides */
    .swal-popup-pos { border-radius: 16px !important; padding: 22px !important; font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important; }
    .swal-popup-pos .swal2-title { font-size: 18px !important; font-weight: 700 !important; color: #0f172a !important; }
    .swal-popup-pos .swal2-html-container { margin: 8px 0 4px !important; }
    .swal-popup-pos .swal2-actions { gap: 8px !important; margin-top: 14px !important; }
    .swal-btn-primary, .swal-btn-ghost, .swal-btn-danger {
        display: inline-flex !important; align-items: center !important; justify-content: center !important;
        padding: 10px 16px !important; border-radius: 10px !important; font-size: 14px !important;
        font-weight: 600 !important; cursor: pointer !important; border: 1px solid transparent !important;
        transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    }
    .swal-btn-primary { background: var(--caramel) !important; color: #fff !important; }
    .swal-btn-primary:hover { background: var(--caramel-dark) !important; }
    .swal-btn-ghost { background: #fff !important; color: #1f2937 !important; border-color: #e2e8f0 !important; }
    .swal-btn-ghost:hover { background: #f8fafc !important; }
    .swal-btn-danger { background: #dc2626 !important; color: #fff !important; border-color: #dc2626 !important; }
    .swal-btn-danger:hover { background: #b91c1c !important; border-color: #b91c1c !important; }
</style>
