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

        try {
            // execute request through HTTP client
            $response = $this->client->{$request->getMethod()}($endpoint, $options);
        } catch (GuzzleException $e) {
            // get response from exception
            $response = $e->getResponse();
        }

        return $response;
    }

}
