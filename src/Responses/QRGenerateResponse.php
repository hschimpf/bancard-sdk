<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Models\QRExpress;
use HDSSolutions\Bancard\Models\SupportedClient;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a QR code generation request.
 * This class encapsulates the details of the response, including the generated QR code
 * information and supported clients for the QR payment system.
 */
final class QRGenerateResponse extends Base\BancardResponse implements Contracts\QRGenerateResponse {

    public function __construct(
        BancardRequest $request,
        private ?QRExpress $qr_express = null,
        private array $supported_clients = [],
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        return new self(
            request:           $request,
            qr_express:        empty($data->qr_express ?? null) ? null : new QRExpress($data->qr_express),
            supported_clients: array_map(static fn($supported_client) => new SupportedClient($supported_client), $data->supported_clients ?? []),
        );
    }

    public function getQRExpress(): ?QRExpress {
        return $this->qr_express;
    }

    public function getSupportedClients(): array {
        return $this->supported_clients;
    }

}
