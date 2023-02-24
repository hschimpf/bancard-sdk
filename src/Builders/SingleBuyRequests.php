<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Models\Payment;
use HDSSolutions\Bancard\Requests\SingleBuyRequest;

trait SingleBuyRequests {

    private function newSingleBuyRequest(float $amount, string $descripcion, ?string $currency = null): SingleBuyRequest {
        // build a pending payment resource
        $payment = Payment::newSingleBuy($amount, $descripcion, $currency);

        // return the request for the payment
        return new SingleBuyRequest($payment);
    }

}
