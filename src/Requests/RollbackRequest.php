<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Responses\RollbackResponse;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;

final class RollbackRequest extends Base\BancardRequest implements Contracts\RollbackRequest {

    public function __construct(
        Bancard $bancard,
        private int $shop_process_id,
    ) {
        parent::__construct($bancard);
    }

    public function getEndpoint(): string {
        return 'single_buy/rollback';
    }

    public function getOperation(): array {
        return [
            'shop_process_id' => $this->getShopProcessId(),
        ];
    }

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return RollbackResponse::fromGuzzle($request, $response);
    }

    public function getShopProcessId(): int {
        return $this->shop_process_id;
    }

}
