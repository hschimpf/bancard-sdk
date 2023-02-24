<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

use HDSSolutions\Bancard\Models\Card;

/**
 * @property int $shop_process_id
 * @property float $amount
 * @property string $additional_data
 * @property string $currency
 * @property Card $card
 */
interface ChargeRequest extends BancardRequest {

    public function getCard(): Card;

    public function getShopProcessId(): int;

    public function getAmount(): float;

    public function getIvaAmount(): ?float;

    public function getCurrency(): string;

    public function getDescription(): string;

    public function setNumberOfPayments(int $number_of_payments = 1): ?int;

    public function getNumberOfPayments(): ?int;

    public function setAdditionalData(?string $additional_data): ?string;

    public function getAdditionalData(): ?string;

    public function asPreAuthorization(bool $pre_authorization = true): bool;

    public function isPreAuthorization(): bool;

}
