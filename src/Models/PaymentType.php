<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class PaymentType {

    /**
     * SingleBuy payment type
     */
    public const SingleBuy = 'single_buy';

    /**
     * Valid Payment types
     */
    public const PaymentTypes = [
        self::SingleBuy,
    ];

    /**
     * @param  string|null  $payment_type  Payment type to test
     *
     * @return bool True if payment_type is valid / supported
     */
    public static function isValid(?string $payment_type = null): bool {
        return in_array($payment_type, self::PaymentTypes, true);
    }

}
