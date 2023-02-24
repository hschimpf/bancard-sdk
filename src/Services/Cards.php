<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Responses\CardsNewResponse;

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
        // get a CardsNew request
        $request = self::newCardsNewRequest($user_id, $card_id, $phone_no, $email, $return_url);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}