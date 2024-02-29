<?php

namespace App\Http\Controllers\Stripe;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Services\StripeWebhookService;
use Error;
use Stripe\Event;

class WebhookController extends CashierController
{
    private StripeWebhookService $stripeWebhookService;

    public function __construct(StripeWebhookService $stripeWebhookService)
    {
        $this->stripeWebhookService = $stripeWebhookService;
    }

    protected function handlePaymentIntentSucceeded($payload)
    {
        try {
            $event = Event::constructFrom($payload);
            $this->stripeWebhookService->paymentIntentSucceeded($event->data['object']);
        } catch (\Exception $e) {
            throw new Error($e);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
