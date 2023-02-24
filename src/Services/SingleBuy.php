<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

trait SingleBuy {

    public static function single_buy(float $amount, string $description, ?string $currency = null): SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest($amount, $description, $currency);
    }

}
