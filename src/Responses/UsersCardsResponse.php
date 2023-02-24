<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

final class UsersCardsResponse extends Base\BancardResponse implements Contracts\UsersCardsResponse {

    private function __construct(
        private array $cards,
    ) {}

    protected static function make(object $data): self {
        // store user cards
        return new self($data->cards);
    }

    public function getCards(): array {
        return $this->cards;
    }

}
