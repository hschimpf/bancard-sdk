<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Models\PendingPayment;
use HDSSolutions\Bancard\Requests\SingleBuyRequest;

trait SingleBuyRequests {

    public static function newSingleBuyRequest(int $shop_process_id, float $amount, string $description, string $currency, ?string $return_url = null, ?string $cancel_url = null): SingleBuyRequest {
        // build a pending payment resource
        $pending_payment = new PendingPayment($shop_process_id, $amount, $description, $currency);

        // return the request for the payment
        return new SingleBuyRequest(self::instance(), $pending_payment, $return_url, $cancel_url);
    }

}
