<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Traits;

use HDSSolutions\Bancard\Services;

trait HasServices {
    use Services\SingleBuy;
    use Services\Cards;
    use Services\Transactions;
    use Services\QR;
}
