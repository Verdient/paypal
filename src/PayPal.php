<?php

declare(strict_types=1);

namespace Verdient\PayPal;

use Verdient\HttpAPI\AbstractClient;

/**
 * PayPal
 * @author Verdient。
 */
class PayPal extends AbstractClient
{
    /**
     * @var string 客户端编号
     * @author Verdient。
     */
    public $clientId = null;

    /**
     * @var string 客户端秘钥
     * @author Verdient。
     */
    public $clientSecret = null;

    /**
     * @var string 缓存目录
     * @author Verdient。
     */
    public $cacheDir = null;

    /**
     * @inheritdoc
     * @author Verdient。
     */
    public $request = Request::class;

    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function request($path): Request
    {
        /** @var Request */
        $request = parent::request($path);
        $request->addHeader('Authorization', 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret));
        $request->clientId = $this->clientId;
        $request->clientSecret = $this->clientSecret;
        $request->cacheDir = $this->cacheDir;
        return $request;
    }
}
