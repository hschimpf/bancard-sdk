<?php declare(strict_types=1);

namespace HDSSolutions\Bancard;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use HDSSolutions\Bancard\Requests\Base\BancardRequest;
use HDSSolutions\Bancard\Traits\BuildsRequests;
use HDSSolutions\Bancard\Traits\HasServices;
use Psr\Http\Message\RequestInterface;

final class Bancard {
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
    private const URI_Production_QR = 'https://comercios.bancard.com.py';

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
     * @var int|null Commerce code for QR Payments
     */
    private static ?int $QR_COMMERCE_CODE = null;

    /**
     * @var int|null Branch code for QR Payments
     */
    private static ?int $QR_BRANCH_CODE = null;

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
        // use the same client for development environment
        $this->client_qr = self::isDevelop() ? $this->client : new Client([
           'base_uri' => self::URI_Production_QR,
           'handler'  => $stack,
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

    /**
     * @return self Returns singleton instance
     */
    protected static function instance(): self {
        return self::$singleton ??= new self();
    }

    /**
     * Stores the credentials to use for communication with Bancard services
     *
     * @param  string|null  $publicKey  Public Key.
     * @param  string|null  $privateKey  Private Key
     * @param  int|null  $qr_commerce_code  Commerce code for QR Payments
     * @param  int|null  $qr_branch_code  Branch code for QR Payments
     */
    public static function credentials(
        ?string $publicKey,
        ?string $privateKey,
        ?int $qr_commerce_code = null,
        ?int $qr_branch_code = null,
    ): void {
        self::$PUBLIC_KEY = $publicKey;
        self::$PRIVATE_KEY = $privateKey;

        self::$QR_COMMERCE_CODE = $qr_commerce_code ?? self::$QR_COMMERCE_CODE;
        self::$QR_BRANCH_CODE = $qr_branch_code ?? self::$QR_BRANCH_CODE;
    }

    /**
     * @return RequestInterface|null Latest request sent to Bancard
     * @internal Used by {@see BancardRequest::execute()} to store the latest request sent
     */
    public function getLatestRequest(): ?RequestInterface {
        return $this->latest_request;
    }

    /**
     * Returns the registered Public Key
     *
     * @return string|null Public Key
     */
    public static function getPublicKey(): ?string {
        return self::$PUBLIC_KEY;
    }

    /**
     * Returns the registered Private Key
     *
     * @return string|null Private Key
     */
    public static function getPrivateKey(): ?string {
        return self::$PRIVATE_KEY;
    }

    /**
     * Returns the Commerce code for QR Payments
     *
     * @return int|null Commerce code
     */
    public static function getQRCommerceCode(): ?int {
        return self::$QR_COMMERCE_CODE;
    }

    /**
     * Returns the Branch code for QR Payments
     *
     * @return int|null Branch code
     */
    public static function getQRBranchCode(): ?int {
        return self::$QR_BRANCH_CODE;
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
