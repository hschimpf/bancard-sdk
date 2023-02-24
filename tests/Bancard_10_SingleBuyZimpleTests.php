<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Requests\SingleBuyRequest;
use HDSSolutions\Bancard\Responses\Contracts\SingleBuyResponse;
use PHPUnit\Framework\TestCase;

final class Bancard_10_SingleBuyZimpleTests extends TestCase {

    /**
     * @throws Exception
     */
    public function testSingleBuyZimpleRequest(): void {
        $this->assertInstanceOf(SingleBuyResponse::class, $response = Bancard::single_buy_zimple(
            shop_process_id: random_int(8**16, PHP_INT_MAX),
            amount:          random_int(5, 20) * 1000,
            description:     'Test',
            currency:        Currency::Guarani,
            phone_no:        '0981123456',
            return_url:      'https://localhost?success',
            cancel_url:      'https://localhost?cancelled',
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
        $this->assertNotEmpty($response->getProcessId());
    }

    /**
     * @throws Exception
     */
    public function testBuildCustomSingleBuyZimpleRequest(): SingleBuyRequest {
        $request = Bancard::newSingleBuyRequest(
            shop_process_id: random_int(8**16, PHP_INT_MAX),
            amount:          random_int(5, 20) * 1000,
            description:     'Test',
            currency:        Currency::Guarani,
            return_url:      'https://localhost?success',
            cancel_url:      'https://localhost?cancelled',
        );
        // manually set Zimple flag
        $request->enableZimple();
        // store phone number on additional data
        $request->setAdditionalData($phone_no = '0981123456');

        $this->assertSame($request->getAdditionalData(), $phone_no);

        return $request;
    }

    /**
     * @depends testBuildCustomSingleBuyZimpleRequest
     */
    public function testExecuteSingleBuyZimpleRequest(SingleBuyRequest $request): void {
        $this->assertTrue($request->execute());
        $this->assertInstanceOf(SingleBuyResponse::class, $response = $request->getResponse());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getProcessId());
    }

}
