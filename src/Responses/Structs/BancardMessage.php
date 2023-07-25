<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Responses\Structs;

use HDSSolutions\Bancard\Models\Base\Model;
use stdClass;

final class BancardMessage extends Model {

    /**
     * @var string|null Identifier of the message
     */
    protected ?string $key = null;

    /**
     * @var string|null Level of severity
     */
    protected ?string $level = null;

    /**
     * @var string|null Description of the message
     */
    protected ?string $description = null;

    /**
     * @param  stdClass  $message
     */
    public function __construct(stdClass $message) {
        $this->key = $message->key;
        $this->level = $message->level;
        $this->description = $message->dsc ?? $message->description ?? 'Unknown';
    }

}
