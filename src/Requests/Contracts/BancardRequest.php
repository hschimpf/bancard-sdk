<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Contracts;

interface BancardRequest {

    /**
     * @return array Operation data
     */
    public function getOperation(): array;

}
