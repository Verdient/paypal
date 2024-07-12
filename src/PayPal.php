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
     * @var string 代理地址
     * @author Verdient。
     */
    protected $proxyHost = null;

    /**
     * @var int 代理端口
     * @author Verdient。
     */
    protected $proxyPort = null;

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

        if ($this->proxyHost) {
            $request->setProxy($this->proxyHost, $this->proxyPort);
        }

        return $request;
    }

    /**
     * 设置代理
     * @param string $host 地址
     * @param int $port 端口
     * @return static
     * @author Verdient。
     */
    public function setProxy($host, $port)
    {
        $this->proxyHost = $host;
        $this->proxyPort = $port;
        return $this;
    }
}
