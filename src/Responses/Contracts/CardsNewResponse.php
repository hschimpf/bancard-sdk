<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Contracts;

interface CardsNewResponse extends BancardResponse {

    /**
     * @return string|null Returns the process ID generated by Bancard
     */
    public function getProcessId(): ?string;

}
