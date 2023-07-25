<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Responses\Contracts\BancardResponse;
use HDSSolutions\Bancard\Responses\QRRevertResponse;

final class QRRevertRequest extends Base\BancardRequest implements Contracts\QRRevertRequest {

    /**
     * @param  Bancard  $bancard
     * @param  string  $hook_alias
     */
    public function __construct(
        Bancard $bancard,
        private string $hook_alias,
    ) { parent::__construct($bancard); }

    protected function through(): string {
        return 'request_qr';
    }

    public function getEndpoint(): string {
        return sprintf('/external-commerce/api/0.1/commerces/%s/branches/%s/selling/payments/revert/%s',
            Bancard::getQRCommerceCode(),
            Bancard::getQRBranchCode(),
            $this->getHookAlias(),
        );
    }

    public function getMethod(): string {
        return 'PUT';
    }

    protected function buildResponse(Contracts\BancardRequest $request, Response $response): BancardResponse {
        // return parsed response
        return QRRevertResponse::fromGuzzle($request, $response);
    }

    public function getHookAlias(): string {
        return $this->hook_alias;
    }

}
