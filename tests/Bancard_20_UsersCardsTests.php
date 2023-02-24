<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Models\Card;
use HDSSolutions\Bancard\Models\Currency;
use HDSSolutions\Bancard\Responses\Contracts\UsersCardsResponse;
use HDSSolutions\Bancard\Responses\Contracts\ChargeResponse;
use PHPUnit\Framework\TestCase;

final class Bancard_20_UsersCardsTests extends TestCase {

    /**
     * @throws Exception
     */
    public function testUserCardsRequest(): Card {
        $this->assertInstanceOf(UsersCardsResponse::class, $response = Bancard::users_cards(
            user_id:    random_int(2**2, 8**8),
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
        $this->assertNotEmpty($cards = $response->getCards());

        return array_shift($cards);
    }

    /**
     * @depends testUserCardsRequest
     * @throws Exception
     */
    public function testChargeRequest(Card $card): void {
        $this->assertInstanceOf(ChargeResponse::class, $response = Bancard::charge(
            card:            $card,
            shop_process_id: random_int(8**4, 8**8),
            amount:          random_int(5, 20) * 1000,
            currency:        Currency::Guarani,
            description:     'Test',
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
    }

}
