<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Models\Confirmation;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a charge request to Bancard's payment system.
 * This class encapsulates the details of the response, including any confirmation
 * information related to the transaction.
 */
final class ChargeResponse extends Base\BancardResponse implements Contracts\ChargeResponse {

    public function __construct(
        BancardRequest $request,
        private ?Confirmation $confirmation = null,
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        // create an instance of Charge
        $confirmation = ($data->confirmation ?? null) === null ? null : new Confirmation($data->confirmation);
        // store operation result data
        return new self($request, $confirmation);
    }

    public function getConfirmation(): ?Confirmation {
        return $this->confirmation;
    }

}
