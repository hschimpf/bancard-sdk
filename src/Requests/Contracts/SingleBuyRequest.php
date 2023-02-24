<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

/**
 * @property int $shop_process_id
 * @property string $currency
 * @property float $amount
 * @property string $description
 * @property string $additional_data
 * @property string $return_url
 * @property string $cancel_url
 */
interface SingleBuyRequest extends BancardRequest {

    public function getShopProcessId(): int;

    public function getCurrency(): string;

    public function getAmount(): float;

    public function getDescription(): string;

    public function setAdditionalData(?string $additional_data): ?string;

    public function getAdditionalData(): ?string;

    public function setReturnUrl(?string $return_url): ?string;

    public function getReturnUrl(): ?string;

    public function setCancelUrl(?string $cancel_url): ?string;

    public function getCancelUrl(): ?string;

    public function enableZimple(bool $zimple = true): bool;

}
