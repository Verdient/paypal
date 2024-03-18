<?php

declare(strict_types=1);

namespace Verdient\PayPal;

use Verdient\http\Response as HttpResponse;
use Verdient\HttpAPI\AbstractResponse;
use Verdient\HttpAPI\Result;

/**
 * @inheritdoc
 * @author Verdient。
 */
class Response extends AbstractResponse
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    protected function normailze(HttpResponse $response): Result
    {
        $result = new Result();
        $statusCode = $response->getStatusCode();
        $body = $response->getBody();
        $result->data = $body;
        if ($statusCode >= 200 && 300 > $statusCode) {
            $result->isOK = true;
        }
        if (!$result->isOK) {
            $result->errorCode = $body['code'] ?? $statusCode;
            if (isset($body['details']) && !empty($body['details'])) {
                $result->errorMessage = $body['details'][0]['issue'];
            } else {
                $result->errorMessage = $body['message'] ?? $response->getStatusMessage();
            }
        }
        return $result;
    }
}
