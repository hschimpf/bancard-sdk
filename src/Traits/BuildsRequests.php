<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Traits;

use GuzzleHttp\Psr7\Response;
use HDSSolutions\Bancard\Builders;

trait BuildsRequests {
    use Builders\SingleBuyRequests;

    private function get(string $endpoint, ?array $params) {
        return self::request('GET', $endpoint, $params);
    }

    private function request(string $method, string $endpoint, ?array $data): Response {
        // TODO
        return $this->client->$method($endpoint);
    }
}
