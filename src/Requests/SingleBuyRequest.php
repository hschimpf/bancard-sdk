<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Models\PendingPayment;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use HDSSolutions\Bancard\Responses\SingleBuyResponse;
use RuntimeException;

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
     * @param  PendingPayment  $pending_payment
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     */
    public function __construct(
        Bancard $bancard,
        private PendingPayment $pending_payment,
        private ?string $return_url = null,
        private ?string $cancel_url = null,
    ) {
        parent::__construct($bancard);

        // validate currency through local model
        if ( !Currency::isValid($this->currency)) {
            // reject with an exception
            throw new RuntimeException(sprintf("Invalid currency (%s)", $this->currency));
        }
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

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return SingleBuyResponse::fromGuzzle($request, $response);
    }

    public function getShopProcessId(): int {
        return $this->pending_payment->shop_process_id;
    }

    public function getCurrency(): string {
        return $this->pending_payment->currency;
    }

    public function getAmount(): float {
        return $this->pending_payment->amount;
    }

    public function getDescription(): string {
        return $this->pending_payment->description;
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
