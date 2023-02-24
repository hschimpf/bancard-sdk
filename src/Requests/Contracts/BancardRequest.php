<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;

/**
 * @template TResponse of BancardResponse
 */
interface BancardRequest {

    /**
     * @return bool True if request execution succeeded
     */
    public function execute(): bool;

    /**
     * @return string HTTP Method for the request
     */
    public function getMethod(): string;

    /**
     * @return string Endpoint for the request
     */
    public function getEndpoint(): string;

    /**
     * @return array Operation data
     */
    public function getOperation(): array;

    /**
     * @return TResponse|null Bancard response of the request
     */
    public function getResponse(): mixed;

}
