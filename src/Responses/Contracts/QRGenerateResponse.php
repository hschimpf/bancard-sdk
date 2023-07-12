<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

use HDSSolutions\Bancard\Models\QRExpress;

interface QRGenerateResponse {

    public function getQRExpress(): ?QRExpress;

    public function getSupportedClients(): array;

}
