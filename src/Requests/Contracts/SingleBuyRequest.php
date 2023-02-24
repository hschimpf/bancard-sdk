<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

interface SingleBuyRequest {

    /**
     * @return string Token of the Process
     */
    public function getToken(): string;

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

}
