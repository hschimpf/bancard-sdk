<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Dotenv\Dotenv;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class Bancard_00_BasicTests extends TestCase {

    public function testThatWeAreOnTestingEnvironment(): void {
        $this->assertTrue(Bancard::isDevelop());
    }

    public function testThatCredentialsCanBeSet(): void {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        Bancard::credentials(
            publicKey:  getenv('BANCARD_PUBLIC_KEY'),
            privateKey: getenv('BANCARD_PRIVATE_KEY'),
        );
        $this->assertSame(Bancard::getPrivateKey(), getenv('BANCARD_PRIVATE_KEY'));
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
            shop_process_id: random_int(8**16, PHP_INT_MAX),
            amount:          -10,
            description:     'Test invalid amount',
            currency:        Currency::Dollar,
            return_url:      '',
            cancel_url:      '',
        );
        $this->assertFalse($single_buy->wasSuccess());
        $this->assertNotEmpty($single_buy->getMessages());
    }

}
