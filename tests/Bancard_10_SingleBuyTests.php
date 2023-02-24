<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Requests\SingleBuyRequest;
use HDSSolutions\Bancard\Responses\Contracts\SingleBuyResponse;
use PHPUnit\Framework\TestCase;

final class Bancard_10_SingleBuyTests extends TestCase {

    /**
     * @throws Exception
     */
    public function testSingleBuyRequest(): void {
        $this->assertInstanceOf(SingleBuyResponse::class, $response = Bancard::single_buy(
            shop_process_id: random_int(8**16, PHP_INT_MAX),
            amount:          random_int(5, 20) * 1000,
            description:     'Test',
            currency:        Currency::Guarani,
            return_url:      'https://localhost?success',
            cancel_url:      'https://localhost?cancelled',
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
        $this->assertNotEmpty($response->getProcessId());
    }

    /**
     * @throws Exception
     */
    public function testBuildCustomSingleBuyRequest(): SingleBuyRequest {
        $request = Bancard::newSingleBuyRequest(
            shop_process_id: random_int(8**16, PHP_INT_MAX),
            amount:          random_int(5, 20) * 1000,
            description:     'Test',
            currency:        Currency::Guarani,
        );
        $request->additional_data = 'Testing SDK';
        // this is just to fire code coverage
        $this->assertTrue(isset($request->additional_data));
        $this->assertNotNull($request->additional_data);
        // manually set return and cancel urls
        $request->setReturnUrl($return_url = 'https://localhost?success');
        $request->setCancelUrl($cancel_url = 'https://localhost?cancelled');
        // check values
        $this->assertSame($request->getReturnUrl(), $return_url);
        $this->assertSame($request->getCancelUrl(), $cancel_url);
        // set values using magic setters & getters
        $request->return_url = $return_url = "$return_url&test=true";
        $request->cancel_url = $cancel_url = "$cancel_url&test=true";
        // check values
        $this->assertSame($request->getReturnUrl(), $return_url);
        $this->assertSame($request->getCancelUrl(), $cancel_url);

        return $request;
    }

    /**
     * @depends testBuildCustomSingleBuyRequest
     */
    public function testExecuteSingleBuyRequest(SingleBuyRequest $request): void {
        $this->assertTrue($request->execute());
        $this->assertInstanceOf(SingleBuyResponse::class, $response = $request->getResponse());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getProcessId());
    }

}
