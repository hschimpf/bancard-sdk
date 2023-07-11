<?php declare(strict_types=1);

namespace HDSSolutions\Bancard\Services;

use HDSSolutions\Bancard\Responses\QRGenerateResponse;

trait QR {

    /**
     * @param  int  $amount
     * @param  string  $description
     *
     * @return QRGenerateResponse
     */
    public static function qr_generate(int $amount, string $description): QRGenerateResponse {
        // get a QR request
        $request = self::newQRGenerateRequest($amount, $description);
        // execute request
        $request->execute();

        // return request response
        return $request->getResponse();
    }

}
