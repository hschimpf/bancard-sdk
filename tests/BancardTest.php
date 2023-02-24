<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use PHPUnit\Framework\TestCase;

final class BancardTest extends TestCase {

    public function testInstance(): void {
        $single_buy = Bancard::single_buy(15000, 'Test', Currency::Guarani);

        $this->assertInstanceOf(SingleBuyResponse::class, $single_buy);
    }

}
