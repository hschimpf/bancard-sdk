<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\PendingPayment;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use HDSSolutions\Bancard\Responses\QRGenerateResponse;

final class QRGenerateRequest extends Base\BancardRequest implements Contracts\QRGenerateRequest {

    /**
     * @param  Bancard  $bancard
     * @param  PendingPayment  $pending_payment
     */
    public function __construct(
        Bancard $bancard,
        private PendingPayment $pending_payment,
    ) { parent::__construct($bancard); }

    protected function through(): string {
        return 'request_qr';
    }

    public function getEndpoint(): string {
        return sprintf('/external-commerce/api/0.1/commerces/%s/branches/%s/selling/generate-qr-express',
            Bancard::getQRCommerceCode(),
            Bancard::getQRBranchCode(),
        );
    }

    public function getOperation(): array {
        return [
            'amount'      => (int) $this->getAmount(),
            'description' => $this->getDescription(),
        ];
    }

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return QRGenerateResponse::fromGuzzle($request, $response);
    }

    public function getAmount(): float {
        return $this->pending_payment->amount;
    }

    public function getDescription(): string {
        return $this->pending_payment->description;
    }

}
