<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Responses\SingleBuyResponse;

/**
 * Trait for handling single purchase transactions through Bancard
 *
 * This trait provides methods for processing one-time payments through Bancard's payment gateway.
 * It supports both standard card payments and Zimple (phone-based) transactions.
 */
trait SingleBuy {

    /**
     * Process a single purchase transaction
     *
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     * @param  float  $amount  Transaction amount
     * @param  string  $description  Description of the purchase
     * @param  string  $currency  Currency code (e.g., PYG, USD; see {@see Currency})
     * @param  string|null  $return_url  URL to redirect after successful payment
     * @param  string|null  $cancel_url  URL to redirect after cancelled payment
     *
     * @return SingleBuyResponse Response containing transaction details and status
     */
    public static function single_buy(
        int $shop_process_id,
        float $amount,
        string $description,
        string $currency,
        ?string $return_url = null,
        ?string $cancel_url = null,
    ): SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest(
            $shop_process_id, $amount, $description, $currency, $return_url, $cancel_url
        );
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Process a single purchase transaction using Zimple (phone-based payment)
     *
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     * @param  float  $amount  Transaction amount
     * @param  string  $description  Description of the purchase
     * @param  string  $currency  Currency code (e.g., PYG, USD; see {@see Currency})
     * @param  string  $phone_no  Customer's phone number for Zimple payment
     * @param  string|null  $return_url  URL to redirect after successful payment
     * @param  string|null  $cancel_url  URL to redirect after cancelled payment
     *
     * @return SingleBuyResponse Response containing transaction details and status
     */
    public static function single_buy_zimple(
        int $shop_process_id,
        float $amount,
        string $description,
        string $currency,
        string $phone_no,
        ?string $return_url = null,
        ?string $cancel_url = null,
    ): SingleBuyResponse {
        // get a SingleBuy request
        $request = self::newSingleBuyRequest(
            $shop_process_id, $amount, $description, $currency, $return_url, $cancel_url
        );
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
