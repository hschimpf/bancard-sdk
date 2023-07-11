<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

final class QRGenerateResponse extends Base\BancardResponse implements Contracts\QRGenerateResponse {

    protected static function make(BancardRequest $request, object $data): self {
        // store operation result data
        return new self($request);
    }

}
