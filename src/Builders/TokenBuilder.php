<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;
use HDSSolutions\Bancard\Requests\Contracts\CardsNewRequest;
use HDSSolutions\Bancard\Requests\Contracts\SingleBuyRequest;
use HDSSolutions\Bancard\Requests\Contracts\UsersCardsRequest;

final class TokenBuilder {

    public static function for(BancardRequest $request): string {
        // sanitize endpoint name
        $method = preg_replace([ '/\d/', '/\/(\/)*/' ], [ '', '_' ], $request->getEndpoint());

        // return token for request
        return self::$method($request);
    }

    private static function single_buy(SingleBuyRequest $request): string {
        // return a token for a SingleBuy request
        return md5(sprintf('%s%u%.2F%s',
            Bancard::getPrivateKey(),
            $request->shop_process_id,
            $request->amount,
            $request->currency,
        ));
    }

    private static function cards_new(CardsNewRequest $request): string {
        // return a token for a CardsNew request
        return md5(sprintf('%s%u%u%s',
            Bancard::getPrivateKey(),
            $request->card_id,
            $request->user_id,
            'request_new_card',
        ));
    }

    private static function users_cards(UsersCardsRequest $request): string {
        // return a token for a UserCards request
        return md5(sprintf('%s%u%s',
            Bancard::getPrivateKey(),
            $request->user_id,
            'request_user_cards',
        ));
    }

}
