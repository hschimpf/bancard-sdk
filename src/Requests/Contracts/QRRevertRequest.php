<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

interface QRRevertRequest {

    public function getHookAlias(): string;

}
