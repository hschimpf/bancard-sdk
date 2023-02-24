<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Responses\SingleBuyResponse;

trait SingleBuy {

    /**
     * @param  int  $shop_process_id
     * @param  float  $amount
     * @param  string  $description
     * @param  string  $currency
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     *
     * @return SingleBuyResponse
     */
    public static function single_buy(int $shop_process_id, float $amount, string $description, string $currency, ?string $return_url = null, ?string $cancel_url = null): SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest($shop_process_id, $amount, $description, $currency, $return_url, $cancel_url);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * @param  int  $shop_process_id
     * @param  float  $amount
     * @param  string  $description
     * @param  string  $currency
     * @param  string  $phone_no
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     *
     * @return SingleBuyResponse
     */
    public static function single_buy_zimple(int $shop_process_id, float $amount, string $description, string $currency, string $phone_no, ?string $return_url = null, ?string $cancel_url = null): SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest($shop_process_id, $amount, $description, $currency, $return_url, $cancel_url);
        // enable zimple flag
        $request->enableZimple();
        // store phone on additional_data field
        $request->setAdditionalData($phone_no);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
