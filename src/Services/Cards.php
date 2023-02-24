<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Responses\CardsNewResponse;
use HDSSolutions\Bancard\Responses\ChargeResponse;
use HDSSolutions\Bancard\Responses\UsersCardsResponse;

trait Cards {

    /**
     * @param  int  $user_id
     * @param  int  $card_id
     * @param  string  $phone_no
     * @param  string  $email
     * @param  string|null  $return_url
     *
     * @return CardsNewResponse|null
     */
    public static function card_new(int $user_id, int $card_id, string $phone_no, string $email, ?string $return_url = null): ?CardsNewResponse {
        // get a new CardsNew request
        $request = self::newCardsNewRequest($user_id, $card_id, $phone_no, $email, $return_url);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * @param  int  $user_id
     *
     * @return UsersCardsResponse|null
     */
    public static function users_cards(int $user_id): ?UsersCardsResponse {
        // get a new UserCards request
        $request = self::newUsersCardsRequest($user_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    public static function charge(Card $card, int $shop_process_id, float $amount, string $currency, string $description): ?ChargeResponse {
        // get a new Charge request
        $request = self::newChargeRequest($card, $shop_process_id, $amount, $currency, $description);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
