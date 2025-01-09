<?php declare(strict_types=1);

namespace HDSSolutions\Bancard;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use HDSSolutions\Bancard\Requests\Base\BancardRequest;
use HDSSolutions\Bancard\Traits\BuildsRequests;
use HDSSolutions\Bancard\Traits\HasServices;
use HDSSolutions\Bancard\Traits\ManagesCredentials;
use Psr\Http\Message\RequestInterface;

/**
 * Bancard SDK main class for handling payments and transactions
 *
 * This class provides the main interface for interacting with Bancard's payment services.
 * It handles both standard card payments and QR-based transactions, managing the communication
 * with Bancard's API endpoints in both staging and production environments.
 *
 * @final
 */
final class Bancard {
    use ManagesCredentials;
    use HasServices;
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
     * Base URI for production environment (QR Payments)
     */
    private static string $URI_Production_QR = 'https://comercios.bancard.com.py';

    /**
     * @var self Singleton instance
     */
    private static self $singleton;

    /**
     * @var bool Flag to control which environment to use
     */
    private static bool $DEV_ENV = true;

    /**
     * @var Client HTTP Client to Bancard services
     */
    private Client $client;

    /**
     * @var Client HTTP Client to Bancard services (QR Payments)
     */
    private Client $client_qr;

    /**
     * @var RequestInterface|null Latest request sent
     */
    private ?RequestInterface $latest_request = null;

    private function __construct() {
        // init HTTP clients
        $this->client = new Client([
           'base_uri' => self::isProduction()
               ? self::URI_Production
               : self::URI_Staging,
           'handler'  => $stack = HandlerStack::create(),
        ]);
        $this->client_qr = new Client([
           'base_uri' => self::$URI_Production_QR,
           'handler'  => $stack,
        ]);

        // add a middleware to capture requests sent body
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            // store request made
            $this->latest_request = $request;

            return $request;
        }));
    }

    /**
     * @return self Returns singleton instance
     */
    protected static function instance(): self {
        return self::$singleton ??= new self();
    }

    /**
     * @return RequestInterface|null Latest request sent to Bancard
     * @internal Used by {@see BancardRequest::execute()} to store the latest request sent
     */
    public function getLatestRequest(): ?RequestInterface {
        return $this->latest_request;
    }

    /**
     * Enables or disables the use of the development environment.
     *
     * When development mode is enabled, the SDK will use the staging environment
     * for all requests. This can be useful for testing and development purposes.
     *
     * @param  bool  $develop  Whether to enable or disable the development environment.
     *                          Defaults to `true`.
     */
    public static function useDevelop(bool $develop = true): void {
        self::$DEV_ENV = $develop;
    }

    /**
     * Enables or disables the use of the production environment.
     *
     * When production mode is enabled, the SDK will use the production environment
     * for all requests. This can be useful in production environments where you
     * want to use the live Bancard API.
     *
     * @param  bool  $production  Whether to enable or disable the production environment.
     *                             Defaults to `true`.
     */
    public static function useProduction(bool $production = true): void {
        self::$DEV_ENV = !$production;
    }

    /**
     * @return bool Whether the development environment is currently enabled
     */
    public static function isDevelop(): bool {
        return self::$DEV_ENV;
    }

    /**
     * @return bool Whether the production environment is currently enabled
     */
    public static function isProduction(): bool {
        return !self::$DEV_ENV;
    }

}
