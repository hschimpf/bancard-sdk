<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use HDSSolutions\Bancard\Responses\ChargeResponse;

final class ChargeRequest extends Base\BancardRequest implements Contracts\ChargeRequest {

    private ?float $iva_amount = null;

    private int $number_of_payments = 1;

    /**
     * @var string|null Additional data to send to Bancard service
     */
    private ?string $additional_data = null;

    private bool $pre_authorization = false;

    /**
     * @param  Bancard  $bancard
     * @param  Card  $card
     * @param  int  $shop_process_id
     * @param  float  $amount
     * @param  string  $currency
     * @param  string  $description
     */
    public function __construct(
        Bancard $bancard,
        private Card $card,
        private int $shop_process_id,
        private float $amount,
        private string $currency,
        private string $description,
    ) {
        parent::__construct($bancard);
    }

    public function getEndpoint(): string {
        return 'charge';
    }

    public function getOperation(): array {
        return [
            'shop_process_id'    => $this->getShopProcessId(),
            'amount'             => number_format($this->getAmount(), 2, '.', ''),
            'iva_amount'         => $this->getIvaAmount() === null ? null
                : number_format($this->getIvaAmount(), 2, '.', ''),
            'currency'           => $this->getCurrency(),
            'description'        => $this->getDescription(),
            'number_of_payments' => $this->getNumberOfPayments(),
            'additional_data'    => $this->getAdditionalData() ?? '',
            'preauthorization'   => $this->isPreAuthorization() ? 'S' : null,
            'alias_token'        => $this->card->alias_token,
        ];
    }

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return ChargeResponse::fromGuzzle($request, $response);
    }

    public function getCard(): Card {
        return $this->card;
    }

    public function getShopProcessId(): int {
        return $this->shop_process_id;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getIvaAmount(): ?float {
        return $this->iva_amount;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setNumberOfPayments(int $number_of_payments = 1): int {
        return $this->number_of_payments = $number_of_payments;
    }

    public function getNumberOfPayments(): ?int {
        return $this->number_of_payments;
    }

    public function setAdditionalData(?string $additional_data): ?string {
        return $this->additional_data = $additional_data;
    }

    public function getAdditionalData(): ?string {
        return $this->additional_data;
    }

    public function asPreAuthorization(bool $pre_authorization = true): bool {
        return $this->pre_authorization = $pre_authorization;
    }

    public function isPreAuthorization(): bool {
        return $this->pre_authorization;
    }

}
