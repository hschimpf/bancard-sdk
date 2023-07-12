<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class QRExpress extends Base\Model {

    protected float  $amount;

    protected string $hook_alias;

    protected string $description;

    protected string $url;

    protected string $created_at;

    protected string $qr_data;

    /**
     * @param  object  $qr_express
     */
    public function __construct(object $qr_express) {
        $this->amount = (float) $qr_express->amount;
        $this->hook_alias = $qr_express->hook_alias;
        $this->description = $qr_express->description;
        $this->url = $qr_express->url;
        $this->created_at = $qr_express->created_at;
        $this->qr_data = $qr_express->qr_data;
    }

}
