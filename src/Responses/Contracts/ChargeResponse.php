<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

use HDSSolutions\Bancard\Models\Confirmation;

interface ChargeResponse extends BancardResponse {

    public function getConfirmation(): ?Confirmation;

}
