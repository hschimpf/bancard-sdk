<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

use HDSSolutions\Bancard\Models\Reverse;

interface QRRevertResponse {

    public function getReverse(): ?Reverse;

}
