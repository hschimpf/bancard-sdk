<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Responses\QRGenerateResponse;
use HDSSolutions\Bancard\Responses\QRRevertResponse;

/**
 * Trait for handling QR code-based payments through Bancard
 *
 * This trait provides methods for generating and managing QR code payments,
 * allowing customers to pay by scanning a QR code with their mobile banking app.
 */
trait QR {

    /**
     * Generate a QR code for payment
     *
     * Creates a new QR code that customers can scan to make a payment. The QR code
     * will be compatible with Bancard's mobile payment system.
     *
     * @param  int  $amount  Amount to be paid in the smallest currency unit (e.g., cents)
     * @param  string  $description  Description of what is being paid for
     *
     * @return QRGenerateResponse Response containing the QR code data and payment status
     */
    public static function qr_generate(int $amount, string $description): QRGenerateResponse {
        $request = self::newQRGenerateRequest($amount, $description);
        $request->execute();

        return $request->getResponse();
    }

    /**
     * Revert/Cancel a QR code payment
     *
     * Cancels a previously generated QR code payment that hasn't been processed yet.
     *
     * @param  string  $hook_alias  Unique identifier for the QR payment transaction
     *
     * @return QRRevertResponse Response containing the reversion status
     */
    public static function qr_revert(string $hook_alias): QRRevertResponse {
        $request = self::newQRRevertRequest($hook_alias);
        $request->execute();

        return $request->getResponse();
    }

}
