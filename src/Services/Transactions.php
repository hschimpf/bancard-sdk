<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Responses\Contracts\ChargeResponse;
use HDSSolutions\Bancard\Responses\Contracts\ConfirmationResponse;
use HDSSolutions\Bancard\Responses\Contracts\PreauthorizationConfirmResponse;
use HDSSolutions\Bancard\Responses\Contracts\RollbackResponse;

/**
 * Trait for managing payment transactions through Bancard
 *
 * This trait provides methods for processing various types of payment transactions including:
 * - Direct charges to registered cards
 * - Transaction confirmations
 * - Pre-authorization confirmations
 * - Transaction rollbacks
 */
trait Transactions {

    /**
     * Process a direct charge to a registered card
     *
     * Charges the specified amount to a previously registered card. This is typically
     * used for recurring payments or when the card details are already stored.
     *
     * @param  Card  $card  The registered card to charge
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     * @param  float  $amount  Amount to charge
     * @param  string  $currency  Currency code (e.g., PYG, USD)
     * @param  string  $description  Description of the charge
     *
     * @return ChargeResponse Response containing charge status and transaction details
     */
    public static function charge(
        Card $card,
        int $shop_process_id,
        float $amount,
        string $currency,
        string $description,
    ): ChargeResponse {
        // get a new Charge request
        $request = self::newChargeRequest($card, $shop_process_id, $amount, $currency, $description);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Get confirmation status for a transaction
     *
     * Retrieves the current status of a previously initiated transaction.
     * Use this to verify if a payment was successfully processed.
     *
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     *
     * @return ConfirmationResponse Response containing transaction status and details
     */
    public static function confirmation(int $shop_process_id): ConfirmationResponse {
        // get a new Confirmation request
        $request = self::newConfirmationRequest($shop_process_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Confirm a pre-authorized transaction
     *
     * Confirms and completes a transaction that was previously pre-authorized.
     * This is typically used in two-step payment processes where you first verify
     * the card and then later confirm the actual charge.
     *
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     *
     * @return PreauthorizationConfirmResponse Response containing confirmation status
     */
    public static function preauthorizationConfirm(int $shop_process_id): PreauthorizationConfirmResponse {
        // get a new PreauthorizationConfirm request
        $request = self::newPreauthorizationConfirmRequest($shop_process_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Rollback/Cancel a transaction
     *
     * Attempts to cancel a previously initiated transaction that hasn't been
     * fully processed yet. This should be used when you need to cancel a
     * payment before it's completed.
     *
     * @param  int  $shop_process_id  Unique identifier for the transaction in your system
     *
     * @return RollbackResponse Response containing rollback status
     */
    public static function rollback(int $shop_process_id): RollbackResponse {
        // get a new Rollback request
        $request = self::newRollbackRequest($shop_process_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
