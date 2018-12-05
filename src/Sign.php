<?php

namespace Sentiger\PowerSign;

use Sentiger\PowerSign\Exceptions\InvalidArgumentException;
use Sentiger\PowerSign\Exceptions\SignException;

class PowerSign
{
    /**
     * @var string $appKey
     */
    private $appKey;
    /**
     * @var string $appSecretKey
     */
    private $appSecretKey;
    /**
     * @var int $expireTime
     */
    private $expireTime;

    public function __construct($appKey, $appSecretKey, $expireTime = 0)
    {
        $this->appKey       = $appKey;
        $this->appSecretKey = $appSecretKey;
        $this->expireTime   = $expireTime;
        if (empty($this->appKey)) {
            throw new InvalidArgumentException('app_key不能为空');
        }
        if (empty($this->appSecretKey)) {
            throw new InvalidArgumentException('app_sercert_key不能为空');
        }
    }

    /**
     * 创建签名
     * @param array $data 需要加密的数组
     * @return string 返回签名
     * @throws InvalidArgumentException
     */
    public function createSign(array $data)
    {
        if (empty($data['client_time'])) {
            throw new InvalidArgumentException('client_time不能为空');
        }
        unset($data['sign'], $data['app_key']);

        ksort($data);
        $data = empty($data) ? (object)$data : $data;
        $str  = json_encode($data, JSON_UNESCAPED_UNICODE) . $this->appSecretKey;
        return md5($str);
    }

    /**
     * 检测签名
     * @param array $data
     * @throws InvalidArgumentException
     * @throws SignException
     */
    public function checkSign(array $data)
    {
        $sign = isset($data['sign']) ? $data['sign'] : '';
        unset($data['sign']);
        $clientTime = isset($data['client_time']) ? $data['client_time'] : 0;
        if (!$clientTime) {
            throw new InvalidArgumentException('client_time不能为空');
        }
        $currentTime = time();

        if ($this->expireTime > 0 && (($currentTime - $this->expireTime > $clientTime) || ($clientTime > $currentTime + $this->expireTime))) {
            throw new SignException('签名过期', SignException::SIGN_EXPIRE_CODE);
        }

        $newSign = $this->createSign($data);
        if ($sign != $newSign) {
            throw new SignException('签名错误', SignException::SING_ERR_CODE);
        }

    }


}
