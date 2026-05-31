<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPaymentRequest;
use App\Services\SubscriptionPaymentRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionPaymentRequestController extends Controller
{
    public function __construct(
        protected SubscriptionPaymentRequestService $paymentRequests
    ) {}

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $requests = SubscriptionPaymentRequest::query()
            ->with(['tenant.owner', 'plan', 'requester', 'reviewer'])
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $pendingCount = SubscriptionPaymentRequest::query()
            ->where('status', SubscriptionPaymentRequest::STATUS_PENDING)
            ->count();

        return view('platform.payment-requests.index', [
            'requests' => $requests,
            'status' => $status,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function approve(SubscriptionPaymentRequest $paymentRequest): RedirectResponse
    {
        $this->paymentRequests->approve(
            $paymentRequest,
            request()->user(),
            request()->input('note')
        );

        return back()->with('success', 'Langganan disetujui dan diaktifkan.');
    }

    public function reject(Request $request, SubscriptionPaymentRequest $paymentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $this->paymentRequests->reject(
            $paymentRequest,
            $request->user(),
            $validated['rejection_reason']
        );

        return back()->with('success', 'Pengajuan langganan ditolak.');
    }
}
