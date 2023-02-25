# Bancard SDK

Library to implement [Bancard vPOS](https://bancard.com.py/vpos) product.

[![Latest Stable Version](http://poser.pugx.org/hds-solutions/bancard-sdk/v)](https://packagist.org/packages/hds-solutions/bancard-sdk) [![Total Downloads](http://poser.pugx.org/hds-solutions/bancard-sdk/downloads)](https://packagist.org/packages/hds-solutions/bancard-sdk) [![License](http://poser.pugx.org/hds-solutions/bancard-sdk/license)](https://packagist.org/packages/hds-solutions/bancard-sdk) [![PHP Version Require](http://poser.pugx.org/hds-solutions/bancard-sdk/require/php)](https://packagist.org/packages/hds-solutions/bancard-sdk)

## Installation
### Dependencies
- PHP >= 8.0

### Through composer
```bash
composer require hds-solutions/bancard-sdk
```

## Usage
To set your Bancard credentials, use the `Bancard::credentials()` method.
```php
use HDSSolutions\Bancard\Bancard;

Bancard::credentials(
    publicKey:  'YOUR_PUBLIC_KEY',
    privateKey: 'YOUR_PRIVATE_KEY',
);
```

The library by defaults uses the `staging` environment. To change to `production` environment use the `Bancard::useProduction()` method.
```php
Bancard::useProduction();
```

This method also can receive a boolean parameter. For example, in Laravel you can dynamically match your environment
```php
Bancard::useProduction(config('app.env') === 'production');
```

## Request and Response objects
The request and the response objects have some helper methods:
```php
use HDSSolutions\Bancard\Bancard;

$response = Bancard::single_buy(...);

// this method returns true only if status == 'success'
if ( !$response->wasSuccess()) {
    // you can access the messages array received from Bancard
    foreach($response->getMessages() as $bancardMessage) {
        echo sprintf('Error: %s, Level: %s => %s',
            $bancardMessage->key,
            $bancardMessage->level,
            $bancardMessage->description);
    }
}

// this method returns the HTTP status code of the response
if ($response->getStatusCode() === 201) {
    // ...
}

// also, you can to access the raw body received
print_r($response->getBody()->getContents());

// you can access to the original request made
$request = $response->getRequest();
// and vice versa
$response = $request->getResponse();

// on the request object you also have access to the raw body sent
print_r($request->getBody()->getContents());
```

## SingleBuy
Endpoint used to generate a process ID to call the Bancard `<iframe>` for an one-time payment.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;

$singleBuyResponse = Bancard::single_buy(
    shop_process_id: $shop_process_id,
    amount:          $amount,
    description:     'Payment description',
    currency:        Currency::Guarani,
    return_url:      'https://localhost/your-success-callback-path',
    cancel_url:      'https://localhost/your-cancelled-callback-path',
);

if ( !$singleBuyResponse->wasSuccess()) {
    // show messages or something ... 
    $singleBuyResponse->getMessages();
}

// access the generated process ID to call the Bancard <iframe>
$process_id = $singleBuyResponse->getProcessId();
```

## SingleBuy Zimple
Same as above, but for `Zimple` payments.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;

$singleBuyResponse = Bancard::single_buy_zimple(
    shop_process_id: $shop_process_id,
    amount:          $amount,
    description:     'Payment description',
    currency:        Currency::Guarani,
    phone_no:        $phone_no, // this field is automatically send on the additional_data property of the request
    return_url:      'https://localhost/your-success-callback-path',
    cancel_url:      'https://localhost/your-cancelled-callback-path',
);
```

## Customizable requests
If you need, you can create a pending request and change the values on runtime. This applies to all available requests.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;

$singleBuyRequest = Bancard::newSingleBuyRequest(
    shop_process_id: $shop_process_id,
    amount:          $amount,
    description:     'Payment description',
    currency:        Currency::Guarani,
    return_url:      'https://localhost/your-success-callback-path',
    cancel_url:      'https://localhost/your-cancelled-callback-path',
);
// for example, enable Zimple flag for this request
$singleBuyRequest->enableZimple();
// for Zimple, you need to specify the user's phone number on the additional data property
$singleBuyRequest->setAdditionalData($phone_no);

// after building the request, you can call the execute() method to send the request to Bancard
if ( !$singleBuyRequest->execute()) {
    // if failed, you can access the response, and messages, ...
    $singleBuyRequest->getResponse()->getMessages();
}
```

## CardsNew
Endpoint used to generate a process ID to call the Bancard `<iframe>` for card registry.
```php
use HDSSolutions\Bancard\Bancard;

$cardsNewResponse = Bancard::card_new(
    user_id:    $user_id,
    card_id:    $card_id,
    phone_no:   $user_phone,
    email:      $user_email,
    return_url: 'https://localhost/your-callback-path',
);

// access the generated process ID to call the Bancard <iframe>
$cardsNewResponse->getProcessId();
```

## UsersCards
Endpoint used to get the registered user cards.
```php
use HDSSolutions\Bancard\Bancard;

$usersCardsResponse = Bancard::users_cards(
    user_id: $user_id,
));

// access the user cards
foreach ($usersCardsResponse->getCards() as $card) {
    echo sprintf('Brand: %s, Number: %s, Type: %s, Expiration Date: %s',
        $card->card_brand,
        $card->card_masked_number,
        $card->card_type,
        $card->expiration_date);
}
```

## CardDelete
Endpoint to remove a registered card. You need an instance of `Card` model obtained from previous request.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;

$cardDeleteResponse = Bancard::card_delete(
    card: $card,
);
```

## Charge
Endpoint used to make a payment using a registered user card. You need an instance of `Card` model obtained from `Bancard::users_cards()`.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Models\Confirmation;

$chargeResponse = Bancard::charge(
    card:            $card,
    shop_process_id: $shop_process_id,
    amount:          $amount,
    currency:        Currency::Guarani,
    description:     'Charge payment description',
));

if ( !$chargeResponse->wasSuccess()) {
    // show messages or something ... 
    $chargeResponse->getMessages();
}

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
```

## Confirmation
Endpoint to get the confirmation of a payment. Example, in case the above charge request stayed as a pending of confirmation payment.
```php
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Confirmation;

$confirmationResponse = Bancard::confirmation(
    shop_process_id: $chargeResponse->getRequest()->getShopProcessId(),
);
```

## Rollback
Endpoint to rollback a payment.
```php
use HDSSolutions\Bancard\Bancard;

$rollbackResponse = Bancard::rollback(
    shop_process_id: $chargeResponse->getRequest()->getShopProcessId(),
);
```

# Security Vulnerabilities
If you encounter any security related issue, feel free to raise a ticket on the issue traker.

# Contributors
- [Hermann D. Schimpf](https://hds-solutions.net)

# Licence
GPL-3.0 Please see [License File](LICENSE) for more information.
