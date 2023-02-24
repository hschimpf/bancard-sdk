<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

/**
 * @property int $shop_process_id
 */
interface PreauthorizationConfirmRequest {

    public function getShopProcessId(): int;

}
