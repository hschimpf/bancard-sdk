<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a rollback request.
 * This class encapsulates the details of the response, indicating whether the
 * rollback operation was successful.
 */
final class RollbackResponse extends Base\BancardResponse implements Contracts\RollbackResponse {

    protected static function make(BancardRequest $request, object $data): self {
        // store operation result data
        return new self($request);
    }

}
