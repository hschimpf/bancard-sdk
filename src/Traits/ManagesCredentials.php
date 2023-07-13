<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Traits;

trait ManagesCredentials {

    /**
     * @var string|null Public Key for communication
     */
    private static ?string $PUBLIC_KEY = null;

    /**
     * @var string|null Private Key for building tokens
     */
    private static ?string $PRIVATE_KEY = null;

    /**
     * @var string|null Public Key for communication (QR Payments)
     */
    private static ?string $QR_PUBLIC_KEY = null;

    /**
     * @var string|null Private Key for building tokens (QR Payments)
     */
    private static ?string $QR_PRIVATE_KEY = null;

    /**
     * @var int|null Commerce code for QR Payments
     */
    private static ?int $QR_COMMERCE_CODE = null;

    /**
     * @var int|null Branch code for QR Payments
     */
    private static ?int $QR_BRANCH_CODE = null;

    /**
     * Stores the credentials to use for communication with Bancard services
     *
     * @param  string|null  $publicKey  Public Key.
     * @param  string|null  $privateKey  Private Key
     */
    public static function credentials(
        ?string $publicKey,
        ?string $privateKey,
    ): void {
        self::$PUBLIC_KEY = $publicKey;
        self::$PRIVATE_KEY = $privateKey;
    }

    /**
     * Stores the credentials to use for communication with Bancard QR services
     *
     * @param  string|null  $serviceUrl  Bancard QR service URL.
     * @param  string|null  $publicKey  Public Key.
     * @param  string|null  $privateKey  Private Key
     * @param  int|null  $qrCommerceCode  Commerce code for QR Payments
     * @param  int|null  $qrBranchCode  Branch code for QR Payments
     */
    public static function qr_credentials(
        ?string $serviceUrl,
        ?string $publicKey,
        ?string $privateKey,
        ?int $qrCommerceCode = null,
        ?int $qrBranchCode = null,
    ): void {
        self::$URI_Production_QR = $serviceUrl;

        self::$QR_PUBLIC_KEY = $publicKey;
        self::$QR_PRIVATE_KEY = $privateKey;

        self::$QR_COMMERCE_CODE = $qrCommerceCode ?? self::$QR_COMMERCE_CODE;
        self::$QR_BRANCH_CODE = $qrBranchCode ?? self::$QR_BRANCH_CODE;
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
     * Returns the registered Public Key for QR Payments
     *
     * @return string|null Public Key for QR Payments
     */
    public static function getQRPublicKey(): ?string {
        return self::$QR_PUBLIC_KEY;
    }

    /**
     * Returns the registered Private Key for QR Payments
     *
     * @return string|null Private Key for QR Payments
     */
    public static function getQRPrivateKey(): ?string {
        return self::$QR_PRIVATE_KEY;
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

}
