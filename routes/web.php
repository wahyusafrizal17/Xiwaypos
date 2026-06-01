<?php

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\OrderAddonController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Platform\PlatformDashboardController;
use App\Http\Controllers\Platform\SubscriptionPaymentRequestController;
use App\Http\Controllers\Platform\TenantAdminController;
use App\Http\Controllers\RegistrationCredentialsController;
use App\Http\Controllers\TenantSwitchController;
use App\Http\Controllers\UpgradeController;
use Illuminate\Support\Facades\Route;

$showAppRoot = ! config('xiway.landing_on_root', true)
    || (config('xiway.marketing_domain') && config('xiway.app_domain'));

if ($showAppRoot) {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect(auth()->user()->homeUrl());
        }

        return redirect()->route('login');
    });
}

Route::middleware(['auth', 'verified', 'tenant.member'])->group(function () {
    Route::middleware('subscription')->group(function () {
        Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
        Route::get('/onboarding/store', [OnboardingController::class, 'store'])->name('onboarding.store');
        Route::post('/onboarding/store', [OnboardingController::class, 'saveStore'])->name('onboarding.store.save');
        Route::get('/onboarding/categories', [OnboardingController::class, 'categories'])->name('onboarding.categories');
        Route::post('/onboarding/categories', [OnboardingController::class, 'saveCategories'])->name('onboarding.categories.save');
        Route::get('/onboarding/products', [OnboardingController::class, 'products'])->name('onboarding.products');
        Route::post('/onboarding/products', [OnboardingController::class, 'saveProducts'])->name('onboarding.products.save');
        Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
        Route::post('/onboarding/finish', [OnboardingController::class, 'finish'])->name('onboarding.finish');
    });

    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/payment-proof', [BillingController::class, 'storePaymentProof'])
        ->middleware('admin')
        ->name('billing.payment-proof.store');
    Route::get('/upgrade', [UpgradeController::class, 'index'])->name('upgrade.index');

    Route::get('/tenant/select', [TenantSwitchController::class, 'select'])->name('tenant.select');
    Route::post('/tenant/switch', [TenantSwitchController::class, 'switch'])->name('tenant.switch');

    Route::middleware(['onboarding', 'subscription'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('admin')
            ->name('dashboard');

        Route::post('/registration-credentials/dismiss', [RegistrationCredentialsController::class, 'dismiss'])
            ->middleware('admin')
            ->name('registration-credentials.dismiss');

        Route::middleware(['staff', 'plan.feature:cashier'])->group(function () {
            Route::get('/kasir', [CashierController::class, 'index'])->name('cashier.index');
            Route::post('/kasir/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
            Route::get('/kasir/open-bills', [CashierController::class, 'openBillsPage'])->name('cashier.open-bills');
            Route::get('/kasir/open-bills/data', [CashierController::class, 'openBills'])->name('cashier.open-bills.data');
            Route::get('/kasir/open-bills/{transaction}/edit-data', [CashierController::class, 'openBillEditData'])->name('cashier.open-bills.edit-data');
            Route::put('/kasir/open-bills/{transaction}', [CashierController::class, 'updateOpenBill'])->name('cashier.open-bills.update');
            Route::post('/kasir/open-bills/{transaction}/pay', [CashierController::class, 'payOpenBill'])->name('cashier.open-bills.pay');
            Route::delete('/kasir/open-bills/{transaction}', [CashierController::class, 'destroyOpenBill'])->name('cashier.open-bills.destroy');
            Route::get('/kasir/history', [CashierController::class, 'history'])->name('cashier.history');
            Route::get('/kasir/struk/{transaction}', [CashierController::class, 'invoice'])->name('cashier.invoice');
        });

        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::middleware('plan.feature:categories')->group(function () {
                Route::resource('categories', CategoryController::class)->except(['show']);
            });

            Route::middleware('plan.feature:products')->group(function () {
                Route::resource('products', ProductController::class)->except(['show']);
            });

            Route::middleware('plan.feature:order_addons')->group(function () {
                Route::resource('order-addons', OrderAddonController::class)->except(['show']);
            });

            Route::resource('users', UserController::class)->except(['show']);

            Route::middleware('plan.feature:expenses')->group(function () {
                Route::resource('pengeluaran', ExpenseController::class)
                    ->parameters(['pengeluaran' => 'expense'])
                    ->names('expenses')
                    ->except(['show']);
            });

            Route::middleware('plan.feature:assets')->group(function () {
                Route::resource('aset', AssetController::class)
                    ->parameters(['aset' => 'asset'])
                    ->names('assets')
                    ->except(['show']);
            });

            Route::middleware('plan.feature:reports_basic')->group(function () {
                Route::get('laporan', [ReportController::class, 'index'])->name('reports.index');
            });

            Route::middleware('plan.feature:reports_export')->group(function () {
                Route::get('laporan/export', [ReportController::class, 'export'])->name('reports.export');
            });

            Route::middleware('plan.feature:expenses')->group(function () {
                Route::get('laporan/laba-rugi', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
            });
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'platform.admin'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {
        Route::get('/dashboard', [PlatformDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenants', [TenantAdminController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}', [TenantAdminController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{tenant}/activate', [TenantAdminController::class, 'activate'])->name('tenants.activate');
        Route::post('/tenants/{tenant}/extend-trial', [TenantAdminController::class, 'extendTrial'])->name('tenants.extend-trial');
        Route::post('/tenants/{tenant}/suspend', [TenantAdminController::class, 'suspend'])->name('tenants.suspend');
        Route::get('/payment-requests', [SubscriptionPaymentRequestController::class, 'index'])->name('payment-requests.index');
        Route::post('/payment-requests/{paymentRequest}/approve', [SubscriptionPaymentRequestController::class, 'approve'])->name('payment-requests.approve');
        Route::post('/payment-requests/{paymentRequest}/reject', [SubscriptionPaymentRequestController::class, 'reject'])->name('payment-requests.reject');
    });

require __DIR__.'/auth.php';
