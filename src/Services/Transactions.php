<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Responses\Contracts\ChargeResponse;
use HDSSolutions\Bancard\Responses\Contracts\ConfirmationResponse;

trait Transactions {

    /**
     * @param  Card  $card
     * @param  int  $shop_process_id
     * @param  float  $amount
     * @param  string  $currency
     * @param  string  $description
     *
     * @return ChargeResponse
     */
    public static function charge(Card $card, int $shop_process_id, float $amount, string $currency, string $description): ChargeResponse {
        // get a new Charge request
        $request = self::newChargeRequest($card, $shop_process_id, $amount, $currency, $description);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * @param  int  $shop_process_id
     *
     * @return ConfirmationResponse
     */
    public static function confirmation(int $shop_process_id): ConfirmationResponse {
        // get a new Confirmation request
        $request = self::newConfirmationRequest($shop_process_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
