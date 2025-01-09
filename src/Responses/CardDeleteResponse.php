<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a card deletion request.
 * This class encapsulates the details of the response, indicating whether the
 * card was successfully deleted from the user's account.
 */
final class CardDeleteResponse extends Base\BancardResponse implements Contracts\CardDeleteResponse {

    protected static function make(BancardRequest $request, object $data): self {
        // store operation result data
        return new self($request);
    }

}
