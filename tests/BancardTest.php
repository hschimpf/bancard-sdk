<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Responses\SingleBuyResponse;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class BancardTest extends TestCase {

    public function testThatWeAreOnTestingEnvironment(): void {
        $this->assertTrue(Bancard::isDevelop());
    }

    public function testThatCredentialsCanBeSet(): void {
        Bancard::credentials(
            publicKey: BANCARD_PUBLIC_KEY,
            privateKey: BANCARD_PRIVATE_KEY,
        );
        $this->assertSame(Bancard::getPrivateKey(), BANCARD_PRIVATE_KEY);
    }

    public function testInvalidDataValidations(): void {
        $this->expectException(RuntimeException::class);
        Bancard::single_buy(
            shop_process_id: 0,
            amount:          0,
            description:     '',
            currency:        '??',
            return_url:      '',
            cancel_url:      '',
        );
    }

    public function testInvalidDataRequest(): void {
        $single_buy = Bancard::single_buy(
            shop_process_id: random_int(10000, 99999),
            amount:          -10,
            description:     'Test invalid amount',
            currency:        Currency::Dollar,
            return_url:      '',
            cancel_url:      '',
        );
        $this->assertFalse($single_buy->wasSuccess());
        $this->assertNotEmpty($single_buy->getMessages());
    }

    /**
     * @throws Exception
     */
    public function testSimpleRequest(): void {
        //
        $single_buy = Bancard::single_buy(
            shop_process_id: random_int(10000, 99999),
            amount:          15000,
            description:     'Test',
            currency:        Currency::Guarani,
            return_url:      'https://localhost?success',
            cancel_url:      'https://localhost?cancelled',
        );

        $this->assertInstanceOf(SingleBuyResponse::class, $single_buy);

        $this->assertTrue($single_buy->wasSuccess(), $single_buy->getMessages()[0]->description ?? 'Unknown');
        $this->assertNotEmpty($single_buy->getProcessId());
    }

    public function testCustomRequest(): void {
        $single_buy_request = Bancard::newSingleBuyRequest(
            shop_process_id: random_int(10000, 99999),
            amount:          15000,
            description:     'Test',
            currency:        Currency::Guarani,
        );
        $single_buy_request->additional_data = 'Testing SDK';
        // this is just to fire code coverage
        $this->assertTrue(isset($single_buy_request->additional_data));
        $this->assertNotNull($single_buy_request->additional_data);
        // manually set return and cancel urls
        $single_buy_request->setReturnUrl($return_url = 'https://localhost?success');
        $single_buy_request->setCancelUrl($cancel_url = 'https://localhost?cancelled');
        // check values
        $this->assertSame($single_buy_request->getReturnUrl(), $return_url);
        $this->assertSame($single_buy_request->getCancelUrl(), $cancel_url);
        // set values using magic setters & getters
        $single_buy_request->return_url = $return_url = "$return_url&test=true";
        $single_buy_request->cancel_url = $cancel_url = "$cancel_url&test=true";
        // check values
        $this->assertSame($single_buy_request->getReturnUrl(), $return_url);
        $this->assertSame($single_buy_request->getCancelUrl(), $cancel_url);
    }

}
