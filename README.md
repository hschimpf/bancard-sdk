# Bancard SDK

A comprehensive PHP SDK for integrating [Bancard vPOS](https://www.bancard.com.py/vpos) and [Bancard VentasQR](https://comercios.bancard.com.py/productos/ventas-qr) payment solutions into your applications.

[![Latest stable version](https://img.shields.io/packagist/v/hds-solutions/bancard-sdk?style=flat-square&label=latest&color=0092CB)](https://github.com/hschimpf/bancard-sdk/releases/latest)
[![License](https://img.shields.io/github/license/hschimpf/bancard-sdk?style=flat-square&color=009664)](https://github.com/hschimpf/bancard-sdk/blob/main/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/hds-solutions/bancard-sdk?style=flat-square&color=747474)](https://packagist.org/packages/hds-solutions/bancard-sdk)
[![Monthly Downloads](https://img.shields.io/packagist/dm/hds-solutions/bancard-sdk?style=flat-square&color=747474&label)](https://packagist.org/packages/hds-solutions/bancard-sdk)
[![Required PHP version](https://img.shields.io/packagist/dependency-v/hds-solutions/bancard-sdk/php?style=flat-square&color=006496&logo=php&logoColor=white)](https://packagist.org/packages/hds-solutions/bancard-sdk)

## Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
- [vPOS Integration](#vpos-integration)
  - [Features](#vpos-features)
  - [Usage Examples](#vpos-usage-examples)
  - [Error Handling](#error-handling)
- [VentasQR Integration](#ventasqr-integration)
  - [Features](#ventasqr-features)
  - [Usage Examples](#ventasqr-usage-examples)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [Contributing](#contributing)
- [License](#license)

## Installation

### Requirements
- PHP >= 8.0

### Via composer
```bash
composer require hds-solutions/bancard-sdk
```

## Configuration

### Setting up Credentials

```php
use HDSSolutions\Bancard\Bancard;

// Set your vPOS API credentials
Bancard::credentials(
    publicKey:  'YOUR_PUBLIC_KEY',
    privateKey: 'YOUR_PRIVATE_KEY',
);
```

### Environment Configuration

The SDK uses the staging environment by default for vPOS. Switch to production when ready:

```php
use HDSSolutions\Bancard\Bancard;

// Switch to production
Bancard::useProduction();

// Or dynamically based on your application environment
Bancard::useProduction(config('app.env') === 'production');
```

## vPOS Integration

### vPOS Features
- Single payments
- Single payments through Zimple
- Card management
- Charge payments to registered cards
- Pre-authorization
- Transaction management _(get confirmation and rollback payments)_

### vPOS Usage Examples

#### Single Payment Flow

Endpoint used to generate a process ID to call the Bancard `<iframe>` for a one-time payment.

```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;

$response = Bancard::single_buy(
    shop_process_id: $shop_process_id,
    amount:          $amount,
    description:     'Premium Subscription',
    currency:        Currency::Guarani,
    return_url:      'https://your-domain.com/payment/success',
    cancel_url:      'https://your-domain.com/payment/cancel',
);

if ($singleBuyResponse->wasSuccess()) {
    // access the generated process ID to call the Bancard <iframe>
    $process_id = $singleBuyResponse->getProcessId();
}

```

#### Single Payment Flow through Zimple

Same as above, but for `Zimple` payments.

```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;

$singleBuyResponse = Bancard::single_buy_zimple(
    shop_process_id: $shop_process_id,
    amount:          $amount,
    description:     'Premium Subscription',
    currency:        Currency::Guarani,
    phone_no:        $phone_no, // this field is automatically send on the `additional_data` property of the request
    return_url:      'https://localhost/your-success-callback-path',
    cancel_url:      'https://localhost/your-cancelled-callback-path',
);
```

#### Card Management

1. **Register a New Card**
```php
use HDSSolutions\Bancard\Bancard;

$response = Bancard::card_new(
    user_id:    $user_id,
    card_id:    $card_id,
    phone_no:   '+595991234567',
    email:      'user@example.com',
    return_url: 'https://your-domain.com/cards/callback',
);

if ($response->wasSuccess()) {
    // access the generated process ID to call the Bancard <iframe>
    $processId = $response->getProcessId();
}
```

2. **List User's Cards**
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;

$response = Bancard::users_cards(
    user_id: $user_id,
);

if ($response->wasSuccess()) {
    foreach ($response->getCards() as $card) {
        echo "Card: {$card->card_masked_number}\n";
        echo "Brand: {$card->card_brand}\n";
        echo "Expiration: {$card->expiration_date}\n";
    }
}
```

3. **Charge a Registered Card**
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Models\Confirmation;
use HDSSolutions\Bancard\Models\SecurityInformation;

$response = Bancard::charge(
    card:            $card,
    shop_process_id: $shop_process_id,
    amount:          $amount,
    currency:        Currency::Guarani,
    description:     'Monthly Subscription',
);

if ($response->wasSuccess()) {
    // access to change Confirmation data
    $confirmation = $chargeResponse->getConfirmation();
    echo sprintf('Ticket No: %u, Authorization ID: %u',
        $confirmation->ticket_number,
        $confirmation->authorization_number);
    
    // also access to the security information data
    $securityInformation = $confirmation->getSecurityInformation();
    echo sprintf('Country: %s, Risk Index: %.2F',
        $securityInformation->card_country,
        $securityInformation->risk_index);
}
```

4. **Get the confirmation of a Payment**

Endpoint to get the confirmation of a payment. Example, in case the above charge request stayed as a pending of confirmation payment.

```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Confirmation;

$confirmationResponse = Bancard::confirmation(
    shop_process_id: $chargeResponse->getRequest()->getShopProcessId(),
);
```

5. **Rollback a Payment**
```php
use HDSSolutions\Bancard\Bancard;

$rollbackResponse = Bancard::rollback(
    shop_process_id: $chargeResponse->getRequest()->getShopProcessId(),
);
```

6. **Remove a Registered Card**

```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;

$response = Bancard::card_delete(
    card: $card,  // must be an instance of Card, obtained from Bancard::users_cards()
);
```

### Error Handling

The SDK provides comprehensive error handling for various scenarios:

#### 1. Basic Error Handling
```php
$response = Bancard::single_buy(/* ... */);

if (! $response->wasSuccess()) {
    foreach ($response->getMessages() as $message) {
        echo sprintf(
            "Error: [%s] %s (Level: %s)\n",
            $message->key,
            $message->description,
            $message->level
        );
    }
}
```

#### 2. Transaction Response Handling
```php
$response = Bancard::charge(/* ... */);

if ($response->wasSuccess()) {
    $confirmation = $response->getConfirmation();
    
    // Access confirmation details
    echo "Response: {$confirmation->response}\n";
    echo "Response Details: {$confirmation->response_details}\n";
    echo "Response Description: {$confirmation->response_description}\n";
    
    // Access security information
    $security = $confirmation->getSecurityInformation();
    echo "Customer IP: {$security->customer_ip}\n";
    echo "Card Country: {$security->card_country}\n";
    echo "Risk Index: {$security->risk_index}\n";
}
```

#### 3. Debug Information
```php
if (! $response->wasSuccess()) {
    // Get request details
    $request = $response->getRequest();
    echo "Request Body: {$request->getBody()->getContents()}\n";
    
    // Get response details
    echo "Response Status: {$response->getStatusCode()}\n";
    echo "Response Body: {$response->getBody()->getContents()}\n";
    
    // Log for debugging
    error_log(sprintf(
        "Bancard API Error: %s, Status: %d, Body: %s",
        $response->getMessages()[0]->description ?? 'Unknown error',
        $response->getStatusCode(),
        $response->getBody()->getContents()
    ));
}
```

## VentasQR Integration

### VentasQR Credentials
⚠ **Important**: VentasQR is not scoped by `Bancard::useProduction()`, since your assigned domain will define your testing/production environment.

```php
use HDSSolutions\Bancard\Bancard;

Bancard::qr_credentials(
    serviceUrl:     'YOUR_QR_ASSIGNED_DOMAIN',
    publicKey:      'YOUR_QR_PUBLIC_KEY',
    privateKey:     'YOUR_QR_PRIVATE_KEY',
    qrCommerceCode: 1234,
    qrBranchCode:   123,
);
```

### VentasQR Features
- Generate QR codes for payments
- Revert QR payments

### VentasQR Usage Examples

1. **Generate QR Code**
```php
$response = Bancard::qr_generate(
    amount:      $amount,
    description: 'Product Purchase',
);

if ($response->wasSuccess()) {
    // access the generated QR data
    $qrExpress = $qrGenerateResponse->getQRExpress();
    echo sprintf('QR Payment ID: %s, QR Image url: %s, QR Data: %s',
        $qrExpress->hook_alias,
        $qrExpress->url,
        $qrExpress->qr_data);
    
    // access the list of supported clients
    $supportedClients = $qrGenerateResponse->getSupportedClients();
    foreach ($supportedClients as $supportedClient) {
        echo sprintf('Client name: %s, Client Logo url: %s',
            $supportedClient->name,
            $supportedClient->logo_url);
    }
}
```

2. **Revert QR Payment**
```php
$response = Bancard::qr_revert(
    hook_alias: $qrExpress->hook_alias,
);

if ($response->wasSuccess()) {
    echo "Payment successfully reverted\n";
}
```

## Advanced Usage

### Request/Response Inspection

Access request and response details for debugging:

```php
// From response to request
$request = $response->getRequest();
echo "Request Body: " . $request->getBody()->getContents() . "\n";

// From request to response
$response = $request->getResponse();
echo "Response Body: " . $response->getBody()->getContents() . "\n";
```

## API Reference

### vPOS Methods
- `Bancard::single_buy()` - Process a one-time payment
- `Bancard::single_buy_zimple()` - Process a Zimple payment
- `Bancard::card_new()` - Register a new card
- `Bancard::users_cards()` - List user's registered cards
- `Bancard::card_delete()` - Remove a registered card
- `Bancard::charge()` - Charge a registered card
- `Bancard::confirmation()` - Check payment status
- `Bancard::preauthorizationConfirm()` - Confirm a pre-authorized payment
- `Bancard::rollback()` - Cancel a pending transaction

### VentasQR Methods
- `Bancard::qr_generate()` - Generate a QR code for payment
- `Bancard::qr_revert()` - Cancel a QR payment

### Currency Support

The SDK supports multiple currencies through the `Currency` class:
- `Currency::Guarani` - Paraguayan Guarani (PYG)
- `Currency::Dollar` - US Dollar (USD)

For detailed API documentation, visit:
- [Bancard vPOS Documentation](https://www.bancard.com.py/vpos)
- [Bancard VentasQR Documentation](https://comercios.bancard.com.py/productos/ventas-qr)

## Contributing
Contributions are welcome! If you find any issues or would like to add new features or improvements, please feel free to submit a pull request.

### Contributors
- [Hermann D. Schimpf](https://github.com/hschimpf)

### Security Vulnerabilities
If you encounter any security-related issues, please feel free to raise a ticket on the issue tracker.

## License
This library is open-source software licensed under the [MIT License](LICENSE).
Please see the [License File](LICENSE) for more information.
