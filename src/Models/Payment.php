<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

use RuntimeException;

final class Payment extends X\Payment {

    /**
     * @param  float  $amount  Amount of the Payment
     * @param  string  $description  Description for the Payment
     * @param  string|null  $currency  Currency for the Payment
     *
     * @return self Payment of type single_buy
     */
    public static function newSingleBuy(int $shop_process_id, float $amount, string $description, ?string $currency = null): self {
        return self::newPayment(PaymentType::SingleBuy, $shop_process_id, $amount, $description, $currency);
    }

    /**
     * @param  string  $payment_type  Type of Payment to make
     * @param  int  $shop_process_id  Unique identifier of Shop Process
     * @param  float  $amount  Amount of the Payment
     * @param  string  $description  Description of the Payment
     * @param  string|null  $currency  Currency for the Payment
     * @param  Card|null  $card  Card to use for the Payment
     *
     * @return self Payment of specified type
     */
    private static function newPayment(string $payment_type, int $shop_process_id, float $amount, string $description, ?string $currency = null, ?Card $card = null): self {
        // validate currency through local model
        if ( !Currency::isValid($currency)) {
            // reject with an exception
            throw new RuntimeException(sprintf("Invalid currency (%s)", $currency));
        }

        // validate payment type through local model
        if ( !PaymentType::isValid($payment_type)) {
            // reject with an exception
            throw new RuntimeException(sprintf("Invalid payment type (%s)", $payment_type));
        }

        // create a new Payment resource
        return self::make([
            'type'            => $payment_type,
            'shop_process_id' => $shop_process_id,
            'amount'          => $amount,
            'description'     => $description,
            'currency'        => $currency ?? Currency::Guarani,
            'card_id'         => $card?->id ?? null,
        ]);
    }

}
