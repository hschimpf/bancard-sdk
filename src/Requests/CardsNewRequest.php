<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\PendingCard;
use HDSSolutions\Bancard\Responses\CardsNewResponse;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;

final class CardsNewRequest extends Base\BancardRequest implements Contracts\CardsNewRequest {

    /**
     * @param  Bancard  $bancard
     * @param  PendingCard  $pending_card
     * @param  string|null  $return_url
     */
    public function __construct(
        Bancard $bancard,
        private PendingCard $pending_card,
        private ?string $return_url = null,
    ) {
        parent::__construct($bancard);
    }

    public function getEndpoint(): string {
        return 'cards/new';
    }

    public function getOperation(): array {
        return [
            'card_id'         => $this->getCardId(),
            'user_id'         => $this->getUserId(),
            'user_cell_phone' => $this->getUserCellPhone(),
            'user_mail'       => $this->getUserMail(),
            'return_url'      => $this->getReturnUrl(),
        ];
    }

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return CardsNewResponse::fromGuzzle($request, $response);
    }

    public function getCardId(): int {
        return $this->pending_card->card_id;
    }

    public function getUserId(): int {
        return $this->pending_card->user_id;
    }

    public function getUserCellPhone(): string {
        return $this->pending_card->user_cell_phone;
    }

    public function getUserMail(): string {
        return $this->pending_card->user_email;
    }

    public function setReturnUrl(?string $return_url): ?string {
        return $this->return_url = $return_url;
    }

    public function getReturnUrl(): ?string {
        return $this->return_url;
    }

}
