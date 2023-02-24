<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

use RuntimeException;

final class PendingPayment extends Base\Model {

    /**
     * @param  int  $shop_process_id  Unique identifier of Shop Process
     * @param  float  $amount  Amount of the Payment
     * @param  string  $description  Description of the Payment
     * @param  string|null  $currency  Currency for the Payment, default is {@see Currency::Guarani}
     */
    public function __construct(
        protected int $shop_process_id,
        protected float $amount,
        protected string $description,
        protected ?string $currency = Currency::Guarani,
    ) {
        // validate currency through local model
        if ( !Currency::isValid($this->currency)) {
            // reject with an exception
            throw new RuntimeException(sprintf("Invalid currency (%s)", $this->currency));
        }
    }

}
