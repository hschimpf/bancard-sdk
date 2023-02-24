<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class Currency {

    /**
     * For payments in Paraguayan Guarani
     */
    public const Guarani = 'PYG';

    /**
     * For payments in US Dollar
     */
    public const Dollar = 'USD';

    /**
     * Valid currencies
     */
    public const Currencies = [
        self::Guarani,
        self::Dollar,
    ];

    /**
     * @param  string|null  $currency  Currency to test
     *
     * @return bool True if currency is valid / supported
     */
    public static function isValid(?string $currency = null): bool {
        return in_array($currency, self::Currencies, true);
    }

}
