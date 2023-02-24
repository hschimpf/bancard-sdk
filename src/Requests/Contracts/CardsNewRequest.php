<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

/**
 * @property int $shop_process_id
 * @property int $card_id
 * @property int $user_id
 * @property string $user_cell_phone
 * @property string $user_mail
 * @property string $return_url
 */
interface CardsNewRequest extends BancardRequest {

    public function getCardId(): int;

    public function getUserId(): int;

    public function getUserCellPhone(): string;

    public function getUserMail(): string;

    public function setReturnUrl(?string $return_url): ?string;

    public function getReturnUrl(): ?string;

}
