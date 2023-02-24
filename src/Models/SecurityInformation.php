<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class SecurityInformation {

    /**
     * @param  string|null  $customer_ip
     * @param  string|null  $card_source
     * @param  string|null  $card_country
     * @param  float|null  $version
     * @param  float|null  $risk_index
     */
    public function __construct(
        protected ?string $customer_ip,
        protected ?string $card_source,
        protected ?string $card_country,
        protected ?float $version,
        protected ?float $risk_index,
    ) {}

}
