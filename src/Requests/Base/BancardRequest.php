<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Base;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Requests\Contracts;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

abstract class BancardRequest implements Contracts\BancardRequest {

    private ?BancardResponse $response = null;

    /**
     * @var RequestInterface Guzzle original request
     */
    private RequestInterface $request;

    public function __construct(
        private Bancard $bancard,
    ) {}

    public function getMethod(): string {
        return 'POST';
    }

    public function getOperation(): array {
        return [];
    }

    abstract protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse;

    protected function through(): string {
        return 'request';
    }

    final public function execute(): bool {
        // execute this request and get result
        $response = $this->bancard->{$this->through()}($this);
        // store request made
        $this->request = $this->bancard->getLatestRequest();
        // parse and store response
        $this->response = $this->buildResponse($this, $response);
        // return response status
        return $this->getResponse()?->wasSuccess() ?? false;
    }

    final public function getBody(): StreamInterface {
        $this->request->getBody()->rewind();
        return $this->request->getBody();
    }

    final public function getResponse(): mixed {
        return $this->response;
    }

    public function __set(string $name, $value): void {
        if ( !method_exists($this, $method = $this->strCamel(sprintf('set_%s', $name)))) {
            throw new RuntimeException(sprintf('The attribute [%s] does not exist for class [%s].',
                $name, self::class));
        }

        // update attribute value
        $this->$method($value);
    }

    public function __get(string $name) {
        if ( !method_exists($this, $method = $this->strCamel(sprintf('get_%s', $name)))) {
            throw new RuntimeException(sprintf('The attribute [%s] does not exist for class [%s].',
                $name, self::class));
        }

        // return attribute value
        return $this->$method();
    }

    public function __isset(string $name): bool {
        // return true if attribute getter exists
        return method_exists($this, $method = $this->strCamel(sprintf('get_%s', $name)))
            // and has value (? maybe)
            && $this->$method() !== null;
    }

    /**
     * Simplified version of Illuminate\Support\Str::camel()
     *
     * @param  string  $value
     *
     * @return string
     */
    private function strCamel(string $value): string {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));
        $studlyWords = array_map('ucfirst', $words);
        $studly = implode($studlyWords);

        return lcfirst($studly);
    }

}
