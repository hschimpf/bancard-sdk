<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Requests\ChargeRequest;
use HDSSolutions\Bancard\Requests\ConfirmationRequest;

trait TransactionsRequests {

    public static function newChargeRequest(Card $card, int $shop_process_id, float $amount, string $currency, string $description): ChargeRequest {
        // return the request
        return new ChargeRequest(self::instance(), $card, $shop_process_id, $amount, $currency, $description);
    }

    public static function newConfirmationRequest(int $shop_process_id): ConfirmationRequest {
        // return the request
        return new ConfirmationRequest(self::instance(), $shop_process_id);
    }

}
