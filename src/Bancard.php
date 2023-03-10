<?php declare(strict_types=1);

namespace HDSSolutions\Bancard;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use HDSSolutions\Bancard\Traits\BuildsRequests;
use HDSSolutions\Bancard\Traits\HasServices;
use Psr\Http\Message\RequestInterface;

final class Bancard {
    use HasServices {
        HasServices::init as HasServices_init;
    }
    use BuildsRequests;

    /**
     * Base URI for testing environment
     */
    private const URI_Staging = 'https://vpos.infonet.com.py:8888';

    /**
     * Base URI for production environment
     */
    private const URI_Production = 'https://vpos.infonet.com.py';

    /**
     * @var self Singleton instance
     */
    private static self $singleton;

    /**
     * @var bool Flag to control which environment to use
     */
    private static bool $DEV_ENV = true;

    /**
     * @var string|null Public Key for communication
     */
    private static ?string $PUBLIC_KEY = null;

    /**
     * @var string|null Private Key for building tokens
     */
    private static ?string $PRIVATE_KEY = null;

    /**
     * @var Client HTTP Client to Bancard services
     */
    private Client $client;

    /**
     * @var RequestInterface|null Latest request sent
     */
    private ?RequestInterface $latest_request = null;

    private function __construct() {
        // init HTTP client
        $this->client = new Client([
           'base_uri' => self::isProduction()
               ? self::URI_Production
               : self::URI_Staging,
           'handler'  => $stack = HandlerStack::create(),
       ]);
        // add a middleware to capture requests sent body
        $stack->push(Middleware::mapRequest(function(RequestInterface $request) {
            // store request made
            $this->latest_request = $request;

            return $request;
        }));
        // init services
        $this->HasServices_init();
    }

    public function getLatestRequest(): ?RequestInterface {
        return $this->latest_request;
    }

    /**
     * Returns the registered Public Key
     *
     * @return string Public Key
     */
    public static function getPublicKey(): string {
        return self::$PUBLIC_KEY;
    }

    /**
     * Returns the registered Private Key
     *
     * @return string Private Key
     */
    public static function getPrivateKey(): string {
        return self::$PRIVATE_KEY;
    }

    /**
     * @return self Returns singleton instance
     */
    protected static function instance(): self {
        return self::$singleton ??= new self();
    }

    /**
     * Stores the credentials to use for communication with Bancard services
     *
     * @param  ?string  $publicKey  Public Key.
     * @param  ?string  $privateKey  Private Key
     */
    public static function credentials(?string $publicKey, ?string $privateKey): void {
        self::$PUBLIC_KEY = $publicKey;
        self::$PRIVATE_KEY = $privateKey;
    }

    public static function useDevelop(bool $develop = true): void {
        self::$DEV_ENV = $develop;
    }

    public static function useProduction(bool $production = true): void {
        self::$DEV_ENV = !$production;
    }

    public static function isDevelop(): bool {
        return self::$DEV_ENV;
    }

    public static function isProduction(): bool {
        return !self::$DEV_ENV;
    }

}
