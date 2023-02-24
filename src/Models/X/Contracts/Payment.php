<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models\X\Contracts;

use Illuminate\Database\Eloquent\Casts\Attribute;

interface Payment {

    public function token(): Attribute;

}
