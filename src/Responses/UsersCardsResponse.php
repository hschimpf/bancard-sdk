<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after requesting a user's registered cards.
 * This class encapsulates the details of the response, including an array of
 * card objects associated with the user's account.
 */
final class UsersCardsResponse extends Base\BancardResponse implements Contracts\UsersCardsResponse {

    private function __construct(
        BancardRequest $request,
        private array $cards,
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        // create an instance of every card
        $cards = array_map(static fn($card) => new Card($request->user_id, $card), $data->cards ?? []);
        // store user cards
        return new self($request, $cards);
    }

    public function getCards(): array {
        return $this->cards;
    }

}
