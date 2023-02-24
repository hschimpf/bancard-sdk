<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

use HDSSolutions\Bancard\Models\Card;

/**
 * @property int $user_id
 * @property Card $card
 */
interface CardDeleteRequest extends BancardRequest {

    public function getUserId(): int;

    public function getCard(): Card;

}
