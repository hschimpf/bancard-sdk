<?php declare(strict_types=1);

namespace HDSSolutions\Bancard;

use GuzzleHttp\Client;
use HDSSolutions\Bancard\Traits\BuildsRequests;
use HDSSolutions\Bancard\Traits\HasServices;

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
     * @var string|null Private Key for building tokens
     */
    private static ?string $PRIVATE_KEY = null;

    /**
     * @var string|null Public Key for communication
     */
    private static ?string $PUBLIC_KEY = null;

    /**
     * @var Client HTTP Client to Bancard services
     */
    private Client $client;

    private function __construct() {
        // init HTTP client
        $this->client = new Client([ 'base_uri' => self::$DEV_ENV ? self::URI_Production : self::URI_Staging ]);
        // init services
        $this->HasServices_init();
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
    private static function instance(): self {
        return self::$singleton ??= new self();
    }

    /**
     * Stores the credentials to use for communication with Bancard services
     *
     * @param  ?string  $privateKey  Privated Key
     * @param  ?string  $publicKey  Public Key.
     */
    public static function credentials(?string $privateKey, ?string $publicKey): void {
        self::$PRIVATE_KEY = $privateKey;
        self::$PUBLIC_KEY = $publicKey;
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
