<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class Reverse extends Base\Model {

    protected string $status;

    protected string $response_code;

    protected string $response_description;

    /**
     * @param  object  $reverse
     */
    public function __construct(object $reverse) {
        $this->status = $reverse->status;
        $this->response_code = $reverse->response_code;
        $this->response_description = $reverse->response_description;
    }

}
