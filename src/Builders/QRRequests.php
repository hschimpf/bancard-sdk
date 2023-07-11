<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Models\PendingPayment;
use HDSSolutions\Bancard\Requests\QRGenerateRequest;

trait QRRequests {

    public static function newQRGenerateRequest(float $amount, string $description): QRGenerateRequest {
        // build a pending payment resource
        $pending_payment = new PendingPayment(-1, $amount, $description, Currency::Guarani);

        // return the request for the QR payment
        return new QRGenerateRequest(self::instance(), $pending_payment);
    }

}
