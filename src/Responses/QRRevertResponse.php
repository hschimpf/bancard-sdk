<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Models\Reverse;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a QR code payment reversion request.
 * This class encapsulates the details of the response, including any reverse
 * information related to the QR payment transaction.
 */
final class QRRevertResponse extends Base\BancardResponse implements Contracts\QRRevertResponse {

    public function __construct(
        BancardRequest $request,
        private ?Reverse $reverse = null,
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        return new self(
            request: $request,
            reverse: empty($data->reverse ?? null) ? null : new Reverse($data->reverse),
        );
    }

    public function getReverse(): ?Reverse {
        return $this->reverse;
    }

}
