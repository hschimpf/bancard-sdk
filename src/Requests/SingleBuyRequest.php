<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use HDSSolutions\Bancard\Models\Payment;

/**
 * @property string $token
 * @property int $shop_process_id
 */
final class SingleBuyRequest extends Base\BancardRequest implements Contracts\SingleBuyRequest {

    /**
     * @var string|null Additional data to send to Bancard service
     */
    private ?string $additional_data = null;

    /**
     * @param  Payment  $payment
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     */
    public function __construct(
        private Payment $payment,
        private ?string $return_url = null,
        private ?string $cancel_url = null,
    ) {}

    public function getOperation(): array {
        return [
            'token'           => $this->getToken(),
            'shop_process_id' => $this->getShopProcessId(),
            'currency'        => $this->getCurrency(),
            'amount'          => number_format($this->getAmount(), 2),
            'description'     => $this->getDescription(),
            'additional_data' => $this->getAdditionalData(),
            'return_url'      => $this->getReturnUrl(),
            'cancel_url'      => $this->getCancelUrl(),
        ];
    }

    public function getToken(): string {
        return $this->payment->token;
    }

    public function getShopProcessId(): int {
        return $this->payment->id;
    }

    public function getCurrency(): string {
        return $this->payment->currency;
    }

    public function getAmount(): float {
        return $this->payment->amount;
    }

    public function getDescription(): string {
        return $this->payment->description;
    }

    public function setAdditionalData(?string $additional_data): ?string {
        return $this->additional_data = $additional_data;
    }

    public function getAdditionalData(): ?string {
        return $this->additional_data;
    }

    public function setReturnUrl(?string $return_url): ?string {
        return $this->return_url = $return_url;
    }

    public function getReturnUrl(): ?string {
        return $this->return_url;
    }

    public function setCancelUrl(?string $cancel_url): ?string {
        return $this->cancel_url = $cancel_url;
    }

    public function getCancelUrl(): ?string {
        return $this->cancel_url;
    }

}
