<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Builders\TokenBuilder;
use HDSSolutions\Bancard\Models\Payment;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use HDSSolutions\Bancard\Responses\SingleBuyResponse;

final class SingleBuyRequest extends Base\BancardRequest implements Contracts\SingleBuyRequest {

    /**
     * @var bool Flag to make request as Zimple
     */
    private bool $zimple = false;

    /**
     * @var string|null Additional data to send to Bancard service
     */
    private ?string $additional_data = null;

    /**
     * @param  Bancard  $bancard
     * @param  Payment  $payment
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     */
    public function __construct(
        Bancard $bancard,
        private Payment $payment,
        private ?string $return_url = null,
        private ?string $cancel_url = null,
    ) {
        parent::__construct($bancard);
    }

    public function getMethod(): string {
        return 'POST';
    }

    public function getEndpoint(): string {
        return 'single_buy';
    }

    public function getOperation(): array {
        return array_merge([
            'shop_process_id' => $this->getShopProcessId(),
            'currency'        => $this->getCurrency(),
            'amount'          => number_format($this->getAmount(), 2, '.', ''),
            'description'     => $this->getDescription(),
            'additional_data' => $this->getAdditionalData(),
            'return_url'      => $this->getReturnUrl(),
            'cancel_url'      => $this->getCancelUrl(),
        ], $this->zimple ? [
            'zimple'          => true,
        ] : []);
    }

    protected function buildResponse(Response $response): BancardResponse {
        // return parsed response
        return SingleBuyResponse::fromGuzzle($response);
    }

    public function getToken(): string {
        return TokenBuilder::for($this);
    }

    public function getShopProcessId(): int {
        return $this->payment->shop_process_id;
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

    public function enableZimple(bool $zimple = true): bool {
        return $this->zimple = $zimple;
    }

}
