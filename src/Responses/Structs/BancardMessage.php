<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Structs;

use stdClass;

final class BancardMessage {

    /**
     * @var string|null Identifier of the message
     */
    public ?string $key = null;

    /**
     * @var string|null Level of severity
     */
    public ?string $level = null;

    /**
     * @var string|null Description of the message
     */
    public ?string $description = null;

    /**
     * @param  stdClass  $message
     */
    public function __construct(stdClass $message) {
        $this->key = $message->key;
        $this->level = $message->level;
        $this->description = $message->dsc ?? $message->description ?? 'Unknown';
    }

}
