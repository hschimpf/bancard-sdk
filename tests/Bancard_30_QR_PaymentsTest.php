<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Responses\QRGenerateResponse;
use PHPUnit\Framework\TestCase;

final class Bancard_30_QR_PaymentsTest extends TestCase {

    /**
     * @throws Exception
     */
    public function testQRGenerateRequest(): void {
        $this->assertInstanceOf(QRGenerateResponse::class, $response = Bancard::qr_generate(
            amount:      random_int(5, 20) * 1000,
            description: 'QR Payment Test',
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
    }

}
