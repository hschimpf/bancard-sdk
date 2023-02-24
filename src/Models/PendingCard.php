<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class PendingCard extends Base\Model {

    /**
     * @param  int  $user_id  User identifier
     * @param  int  $card_id  Unique Card identifier for user
     * @param  string  $user_cell_phone  User cellphone
     * @param  string  $user_email  User email
     */
    public function __construct(
        protected int $user_id,
        protected int $card_id,
        protected string $user_cell_phone,
        protected string $user_email,
    ) {}

}
