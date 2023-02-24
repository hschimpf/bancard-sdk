<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;
use HDSSolutions\Bancard\Requests\Contracts\SingleBuyRequest;

final class TokenBuilder {

    public static function for(BancardRequest $request): string {
        return self::{$request->getEndpoint()}($request);
    }

    private static function single_buy(SingleBuyRequest $request): string {
        // return a token for
        return md5(sprintf("%s%u%.2F%s",
            Bancard::getPrivateKey(),
            $request->shop_process_id,
            $request->amount,
            $request->currency,
        ));
    }

}
