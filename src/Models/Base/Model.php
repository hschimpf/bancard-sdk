<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models\Base;

abstract class Model {

    public function __get(string $name) {
        if ( !property_exists($this, $name)) {
            trigger_error(sprintf('Undefined property: %s::$%s', get_class($this), $name), E_USER_ERROR);
        }

        return $this->$name;
    }

    public function __isset(string $name): bool {
        return property_exists($this, $name);
    }

}
