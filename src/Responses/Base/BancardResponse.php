<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Base;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Models\ProcessStatus;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;
use HDSSolutions\Bancard\Responses\Contracts;
use HDSSolutions\Bancard\Responses\Structs\BancardMessage;
use JsonException;
use Psr\Http\Message\StreamInterface;

abstract class BancardResponse implements Contracts\BancardResponse {

    public function __construct(
        private BancardRequest $request,
    ) {}

    /**
     * @var Response Guzzle original response
     */
    private Response $response;

    /**
     * @var string Bancard process status result
     */
    private string $process_status;

    /**
     * @var BancardMessage[] Messages from Bancard
     */
    private array $messages = [];

    abstract protected static function make(BancardRequest $request, object $data): self;

    final public static function fromGuzzle(BancardRequest $request, Response $response): self {
        try {
            // get response body
            $body = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        } catch (JsonException $e) {
            // add a custom body
            $body = (object) [
                'status'   => 'error',
                'messages' => [
                    (object) [
                        'level' => 'error',
                        'key'   => get_class($e),
                        'dsc'   => $e->getMessage(),
                    ],
                ],
            ];
        }

        // get status
        $process_status = $body->status; unset($body->status);
        // get messages
        $messages = $body->messages ?? []; unset($body->messages);

        // build Bancard response instance
        $instance = static::make($request, $body);

        // store guzzle original response
        $instance->response = $response;
        // assign Bancard process status
        $instance->process_status = $process_status;
        // replace messages with BancardMessage instances
        $instance->messages = array_map(static fn($message) => new BancardMessage($message), $messages);

        // return response instance
        return $instance;
    }

    final public function getBody(): StreamInterface {
        $this->response->getBody()->rewind();
        return $this->response->getBody();
    }

    final public function getRequest(): mixed {
        return $this->request;
    }

    final public function getStatusCode(): int {
        return $this->response->getStatusCode();
    }

    final public function getProcessStatus(): string {
        return $this->process_status;
    }

    final public function getMessages(): array {
        return $this->messages;
    }

    final public function wasSuccess(): bool {
        return $this->getProcessStatus() === ProcessStatus::SUCCESS;
    }

}
