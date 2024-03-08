##### 一、安装

```shell
composer require "oh86/sm"
```

##### 二、配置

1. 添加下面一行到 `config/app.php` 中 `providers` 部分：

   ```
   Oh86\Sm\SmServiceProvider::class,
   ```

2. 发布配置文件与资源

   ```
   php artisan vendor:publish --provider='Oh86\Sm\SmServiceProvider'
   ```

3. 配置.env

   ```
   SM4_KEY=08c8e6db4907dc755a6097d0abd417c5	# hex
   
   SM2_PRIVATE_KEY=afbee3c2367df945294f39f23fac1678b4e7b4bc18b69a33890ae21bc6e43830 # hex
   SM2_PUBLIC_KEY=04145c4a725692115e45e6c147bebc4e25ec024569b6e8589d2bcc9181a1cfe401747d8593d7f8fad8ba45635ea6a7c514555bd3298ccdae8e2391ae782b00d09d # hex
   ```

三、使用示例

```
>>> sm3("123");
=> "6e0f9e14344c5406a0cf5a3b4dfb665f87f4a771a31f7edbb5c72874a32b2957"
>>> 
>>> $encrypt = sm4_encrypt("hello world")
=> "f7064332db25a4ab8615721c49f49ee6"
>>> sm4_decrypt($encrypt)
=> "hello world"
>>> 
>>> $encrypt = sm2_encrypt("hello world")
>>> sm2_decrypt($encrypt)
>>> 
>>> $sign = sm2_sign("hello world")
>>> sm2_verify("hello world", $sign)
>>> 
>>> $sign = sm2_sign_without_asn1("hello world")
>>> sm2_verify_without_asn1("hello world", $sign)
>>> 
```



