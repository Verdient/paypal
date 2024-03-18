<?php

declare(strict_types=1);

namespace Verdient\PayPal;

/**
 * 授权秘钥
 * @author Verdient。
 */
class AccessToken
{
    /**
     * @var string 秘钥
     * @author Verdient。
     */
    public $token;

    /**
     * @var string 类型
     * @author Verdient。
     */
    public $type;

    /**
     * @var string 过期时间
     * @author Verdient。
     */
    public $expiredAt;

    /**
     * @param string $token 秘钥
     * @param string $type 类型
     * @param string $expiredAt 过期时间
     * @author Verdient。
     */
    public function __construct($token, $type, $expiredAt)
    {
        $this->token = $token;
        $this->type = $type;
        $this->expiredAt = $expiredAt;
    }

    /**
     * 获取是否已过期
     * @return bool
     * @author Verdient。
     */
    public function isExpired()
    {
        return time() >= $this->expiredAt;
    }
}
