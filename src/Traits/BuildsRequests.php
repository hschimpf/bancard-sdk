<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Traits;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use HDSSolutions\Bancard\Bancard;
use HDSSolutions\Bancard\Builders;
use HDSSolutions\Bancard\Requests\Contracts\BancardRequest;
use RuntimeException;

trait BuildsRequests {
    use Builders\SingleBuyRequests;
    use Builders\CardsRequests;
    use Builders\TransactionsRequests;
    use Builders\QRRequests;

    /**
     * Sends the request to Bancard and returns the response
     *
     * @param  BancardRequest  $request  Request to send
     *
     * @return Response Response from Bancard
     */
    public function request(BancardRequest $request): Response {
        // build request endpoint
        $endpoint = sprintf('vpos/api/0.3/%s', $request->getEndpoint());
        // build request params
        $options = match ($request->getMethod()) {
            'POST', 'DELETE' => [
                // add params to request
                RequestOptions::JSON => [
                    // set public key
                    'public_key' => Bancard::getPublicKey(),
                    // get operation params
                    'operation' => array_merge(
                        // add token for the operation
                        [ 'token' => Builders\TokenBuilder::for($request) ],
                        // add request operation info
                        $request->getOperation(),
                    ),
                ],
            ],
            default => throw new RuntimeException('Unsupported request type'),
        };

        if (Bancard::isDevelop()) {
            $options[ RequestOptions::VERIFY ] = false;
        }

        try {
            // execute request through HTTP client
            $response = $this->client->{$request->getMethod()}($endpoint, $options);
        } catch (GuzzleException $e) {
            // get response from exception
            $response = $e->getResponse();
        }

        return $response;
    }

    /**
     * Sends the request to Bancard and returns the response (QR Payments)
     *
     * @param  BancardRequest  $request  Request to send
     *
     * @return Response Response from Bancard
     */
    public function request_qr(BancardRequest $request): Response {
        // build request options with the authorization request header
        $options = [
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Basic %s', base64_encode(sprintf('apps/%s:%s',
                    Bancard::getQRPublicKey(),
                    Bancard::getQRPrivateKey(),
                ))),
            ],
        ];

        if ( !empty($request->getOperation())) {
            // add operation params
            $options[RequestOptions::JSON] = $request->getOperation();
        }

        if (Bancard::isDevelop()) {
            $options[ RequestOptions::VERIFY ] = false;
        }

        try {
            // execute request through HTTP client
            $response = $this->client_qr->{$request->getMethod()}($request->getEndpoint(), $options);
        } catch (GuzzleException $e) {
            // get response from exception
            $response = $e->getResponse();
        }

        return $response;
    }

}
