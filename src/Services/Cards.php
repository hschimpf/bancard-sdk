<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Responses\CardDeleteResponse;
use HDSSolutions\Bancard\Responses\CardsNewResponse;
use HDSSolutions\Bancard\Responses\UsersCardsResponse;

/**
 * Trait for managing credit/debit cards in Bancard's system
 *
 * This trait provides methods for card management operations including:
 * - Adding new cards to a user's account
 * - Retrieving a user's stored cards
 * - Deleting cards from a user's account
 */
trait Cards {

    /**
     * Register a new card for a user
     *
     * This method initiates the card registration process. The user will be redirected
     * to Bancard's secure form to enter their card details.
     *
     * @param  int  $user_id  Unique identifier for the user in your system
     * @param  int  $card_id  Unique identifier for the card in your system
     * @param  string  $phone_no  User's phone number
     * @param  string  $email  User's email address
     * @param  string|null  $return_url  URL to redirect after card registration
     *
     * @return CardsNewResponse Response containing registration status and redirect URL
     */
    public static function card_new(
        int $user_id,
        int $card_id,
        string $phone_no,
        string $email,
        ?string $return_url = null,
    ): CardsNewResponse {
        // get a new CardsNew request
        $request = self::newCardsNewRequest($user_id, $card_id, $phone_no, $email, $return_url);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Retrieve all cards registered to a user
     *
     * @param  int  $user_id  Unique identifier for the user in your system
     *
     * @return UsersCardsResponse Response containing list of user's registered cards
     */
    public static function users_cards(int $user_id): UsersCardsResponse {
        // get a new UserCards request
        $request = self::newUsersCardsRequest($user_id);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

    /**
     * Delete a registered card from the user's account
     *
     * @param  Card  $card  The card object to be deleted
     *
     * @return CardDeleteResponse Response containing deletion status
     */
    public static function card_delete(Card $card): CardDeleteResponse {
        // get a new CardDelete request
        $request = self::newCardDeleteRequest($card);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
