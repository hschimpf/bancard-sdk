<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Models;

final class Confirmation extends Base\Model {

    protected string              $token;

    protected int                 $shop_process_id;

    protected string              $response;

    protected string              $response_details;

    protected ?string             $extended_response_description;

    protected string              $currency;

    protected float               $amount;

    protected ?int                $authorization_number;

    protected ?int                $ticket_number;

    protected ?float              $iva_amount;

    protected ?int                $iva_ticket_number;

    protected ?string             $response_code;

    protected ?string             $response_description;

    protected SecurityInformation $security_information;

    /**
     * @param  object  $confirmation
     */
    public function __construct(object $confirmation) {
        $this->token = $confirmation->token;
        $this->shop_process_id = $confirmation->shop_process_id;
        $this->response = $confirmation->response;
        $this->response_details = $confirmation->response_details;
        $this->extended_response_description = $confirmation->extended_response_description;
        $this->currency = $confirmation->currency;
        $this->amount = (float) $confirmation->amount;
        $this->authorization_number = $confirmation->authorization_number === null ? null
            : (int) $confirmation->authorization_number;
        $this->ticket_number = $confirmation->ticket_number === null ? null
            : (int) $confirmation->ticket_number;
        $this->iva_amount = ($confirmation->iva_amount ?? null) === null ? null
            : (float) $confirmation->iva_amount;
        $this->iva_ticket_number = ($confirmation->iva_ticket_number ?? null) === null ? null
            : (int) $confirmation->iva_ticket_number;
        $this->response_code = $confirmation->response_code;
        $this->response_description = $confirmation->response_description;
        $this->security_information = new SecurityInformation(
            customer_ip:  $confirmation->security_information?->customer_ip,
            card_source:  $confirmation->security_information?->card_source,
            card_country: $confirmation->security_information?->card_country,
            version:      $confirmation->security_information?->version === null ? null
                : (float) $confirmation->security_information?->version,
            risk_index:   $confirmation->security_information?->risk_index === null ? null
                : (float) $confirmation->security_information?->risk_index,
        );
    }

    public function getSecurityInformation(): SecurityInformation {
        return $this->security_information;
    }

}
