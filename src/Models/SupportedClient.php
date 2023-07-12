<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class SupportedClient extends Base\Model {

    protected string $name;

    protected string $logo_url;

    /**
     * @param  object  $supported_client
     */
    public function __construct(object $supported_client) {
        $this->name = $supported_client->name;
        $this->logo_url = $supported_client->logo_url;
    }

}
