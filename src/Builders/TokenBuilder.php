<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Payment;

final class TokenBuilder {

    public static function for(Payment $payment): string {
        return self::{$payment->type}($payment);
    }

    private static function single_buy(Payment $payment): string {
        // return a token for
        return md5(sprintf("%s%u%.2F%s",
            Bancard::getPrivateKey(),
            $payment->shop_process_id,
            $payment->amount,
            $payment->currency,
        ));
    }

}
