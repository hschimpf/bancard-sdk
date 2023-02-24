<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

use HDSSolutions\Bancard\Models\Card;

interface UsersCardsResponse {

    /**
     * @return Card[] Returns the registered cards of the User
     */
    public function getCards(): array;

}
