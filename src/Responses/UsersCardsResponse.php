<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

final class UsersCardsResponse extends Base\BancardResponse implements Contracts\UsersCardsResponse {

    private function __construct(
        BancardRequest $request,
        private array $cards,
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        // store user cards
        return new self($request, $data->cards);
    }

    public function getCards(): array {
        return $this->cards;
    }

}
