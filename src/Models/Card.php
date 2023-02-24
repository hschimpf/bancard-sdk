<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class Card extends Base\Model {

    protected int    $user_id;

    protected int    $card_id;

    protected string $card_brand;

    protected string $card_masked_number;

    protected string $card_type;

    protected string $expiration_date;

    protected string $alias_token;

    public function __construct(int $user_id, object $data) {
        $this->user_id = $user_id;
        $this->card_id = $data->card_id;
        $this->card_brand = $data->card_brand;
        $this->card_masked_number = $data->card_masked_number;
        $this->card_type = $data->card_type;
        $this->expiration_date = $data->expiration_date;
        $this->alias_token = $data->alias_token;
    }

}
