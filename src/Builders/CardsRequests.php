<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Builders;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Requests\CardsNewRequest;

trait CardsRequests {

    public static function newCardsNewRequest(int $user_id, int $card_id, string $phone_no, string $email, ?string $return_url = null): CardsNewRequest {
        // build a pending card resource
        $card = new Card([
            'user_id'  => $user_id,
            'card_id'  => $card_id,
            'phone_no' => $phone_no,
            'email'    => $email,
        ]);

        // return the request for the payment
        return new CardsNewRequest(self::instance(), $card, $return_url);
    }

}
