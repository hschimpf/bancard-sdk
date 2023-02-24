<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses;

final class CardsNewResponse extends Base\BancardResponse implements Contracts\CardsNewResponse {

    private function __construct(
        private ?string $process_id,
    ) {}

    protected static function make(object $data): self {
        // store process ID
        return new self($data->process_id ?? null);
    }

    public function getProcessId(): ?string {
        return $this->process_id;
    }

}