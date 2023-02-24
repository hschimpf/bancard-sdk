<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use Psr\Http\Message\StreamInterface;

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
     * @return StreamInterface Gets the body of the request made
     */
    public function getBody(): mixed;

    /**
     * @return TResponse|null Bancard response of the request
     */
    public function getResponse(): mixed;

}
