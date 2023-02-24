<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models\X;

use HDSSolutions\Bancard\Models\Base;

/**
 * @property-read int $id Resource Identifier
 * @property int $user_id User Identifier
 * @property int $card_id Card Identifier of the User
 * @property string $phone_no Phone number of the User
 * @property string $email Email address of the User
 */
abstract class PendingCard extends Base\EloquentModel implements Contracts\PendingCard {

    protected $fillable = [
        'user_id',
        'card_id',
        'phone_no',
        'email',
    ];

}
