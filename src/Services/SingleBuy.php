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
     * @return SingleBuyResponse|null
     */
    public static function single_buy(int $shop_process_id, float $amount, string $description, string $currency, ?string $return_url = null, ?string $cancel_url = null): ?SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest($shop_process_id, $amount, $description, $currency, $return_url, $cancel_url);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
