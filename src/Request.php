<?php

declare(strict_types=1);

namespace Verdient\PayPal;

use Verdient\http\Request as HttpRequest;
use Verdient\http\serializer\body\UrlencodedBodySerializer;

/**
 * @inheritdoc
 * @author Verdient。
 */
class Request extends HttpRequest
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
    public function send(): Response
    {
        return new Response(parent::send());
    }

    /**
     * 携带秘钥
     * @return static
     * @author Verdient。
     */
    public function withBearerToken()
    {
        if ($this->cacheDir) {
            if (!is_dir($this->cacheDir)) {
                mkdir($this->cacheDir, 0777, true);
            }
            $dir = $this->cacheDir;
        } else {
            $dir = sys_get_temp_dir();
        }
        $cacheKey = 'PayPal-BearerToken-' . md5($this->clientId) . '-' . md5($this->clientSecret);
        $cachePath = $dir . DIRECTORY_SEPARATOR . $cacheKey;
        if (file_exists($cacheKey)) {
            $accessToken = unserialize(file_get_contents($cachePath));
            if ($accessToken && $accessToken instanceof AccessToken) {
                if ($accessToken->isExpired()) {
                    unlink($cachePath);
                } else {
                    $this->addHeader('Authorization', $accessToken->type . ' ' . $accessToken->token);
                    return $this;
                }
            }
        }
        $request = new HttpRequest();
        $request->setUrl($this->scheme . '://' . $this->host . '/v1/oauth2/token');
        $request->addHeader('Authorization', 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret));
        $request->setMethod('POST');
        $request->bodySerializer = UrlencodedBodySerializer::class;
        $request->setBody(['grant_type' => 'client_credentials']);
        $res = $request->send();
        if ($res->getStatusCode() != 200) {
            $body = $res->getBody();
            throw new InvalidCredentialsException($body['error_description'] ?? 'Invalid credentials');
        }
        $body = $res->getBody();
        $this->addHeader('Authorization', $body['token_type'] . ' ' . $body['access_token']);
        $accessToken = new AccessToken($body['access_token'], $body['token_type'], time() + $body['expires_in'] - 60);
        file_put_contents($cachePath, serialize($accessToken));
        return $this;
    }
}
