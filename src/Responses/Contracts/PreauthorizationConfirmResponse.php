<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

use HDSSolutions\Bancard\Models\Confirmation;

interface PreauthorizationConfirmResponse {

    public function getConfirmation(): ?Confirmation;

}
