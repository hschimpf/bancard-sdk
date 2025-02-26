<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;

/**
 * Represents the response returned after a single buy transaction request.
 * This class encapsulates the details of the response, including the process ID
 * associated with the single buy operation.
 */
final class SingleBuyResponse extends Base\BancardResponse implements Contracts\SingleBuyResponse {

    private function __construct(
        BancardRequest $request,
        private ?string $process_id,
    ) {
        parent::__construct($request);
    }

    protected static function make(BancardRequest $request, object $data): self {
        // store process ID
        return new self($request, $data->process_id ?? null);
    }

    public function getProcessId(): ?string {
        return $this->process_id;
    }

}
