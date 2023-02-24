<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Requests\Base;

use HDSSolutions\Bancard\Requests\Contracts;
use Illuminate\Support\Str;

abstract class BancardRequest implements Contracts\BancardRequest {

    public function __set(string $name, $value): void {
        if ( !method_exists($this, $method = Str::camel(sprintf('set_%s', $name)))) {
            throw new \RuntimeException(sprintf('The attribute [%s] does not exist for class [%s].',
                self::class, $name));
        }

        // update attribute value
        $this->$method($value);
    }

    public function __get(string $name) {
        if ( !method_exists($this, $method = Str::camel(sprintf('get_%s', $name)))) {
            throw new \RuntimeException(sprintf('The attribute [%s] does not exist for class [%s].',
                self::class, $name));
        }

        // return attribute value
        return $this->$method();
    }

    public function __isset(string $name): bool {
        // return true if attribute getter exists
        return method_exists($this, $method = Str::camel(sprintf('get_%s', $name)))
            // and has value (? maybe)
            && $this->$method() !== null;
    }

}
