<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Tests;

use Exception;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Requests\UsersCardsRequest;
use HDSSolutions\Bancard\Responses\Contracts\UsersCardsResponse;
use PHPUnit\Framework\TestCase;

final class Bancard_20_UsersCardsTests extends TestCase {

    /**
     * @throws Exception
     */
    public function testUserCardsRequest(): void {
        $this->assertInstanceOf(UsersCardsResponse::class, $response = Bancard::users_cards(
            user_id:    random_int(2**2, 8**8),
        ));
        $this->assertTrue($response->wasSuccess(), $response->getMessages()[0]->description ?? 'Unknown');
        $this->assertNotEmpty($response->getCards());
    }

}
