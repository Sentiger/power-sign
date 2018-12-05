# 微服务签名算法
## package源安装
> `composer require sentiger/power-sign `

## 普通使用
```php

include __DIR__ . '/vendor/autoload.php';

$appKey       = '0123456';
$appSecretKey = '4564877211';
$expireTime   = 0;
$signClient   = new \Sentiger\PowerSign\PowerSign($appKey, $appSecretKey, $expireTime);

$data    = [
    'client_time' => time(),
    'name'        => 'Sentiger',
    'age'         => '18'
];

// 生成签名
$signStr = $signClient->createSign($data);

// 检测签名

$checkData         = $data;
$checkData['sign'] = $signStr;
try {
    $signClient->checkSign($checkData);
} catch (\Exception $e) {
    echo $e->getMessage();
}


print_r($signStr);
```

## 在laravel框架中通过依赖注入使用
- 配置

> config/services.php

```php
...

'sassyc' => [
        'app_key'        => '123456',
        'app_secret_key' => '11114fdsfadas',
        'expire_time'    => 0,  //服务端验证签名过期时间0表示不过期
    ]
...

```

- 通过依赖注入使用

```php
Route::get('sign', function (\Sentiger\PowerSign\PowerSign) {
    $data = [
        'client_time' => 1543995526,
        'name'        => '张三',
        'age'         => '12'
    ];
    // 生成签名
    $signStr = $signClient->createSign($data);

    // curl传
    $checkData = array_merge($data, [
        'sign' => $signStr
    ]);

    // 检测签名
    $signClient->checkSign($checkData);

    return $signStr;

});

```