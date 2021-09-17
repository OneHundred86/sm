##### 一、安装

```shell
composer require "southcn/sm"
```

##### 二、配置

1. 添加下面一行到 `config/app.php` 中 `providers` 部分：

   ```
   Southcn\Sm\SmServiceProvider::class,
   ```

2. 发布配置文件与资源

   ```
   php artisan vendor:publish --provider=Southcn\Sm\SmServiceProvider
   ```

3. 配置.env

   ```
   SM4_KEY=08c8e6db4907dc755a6097d0abd417c5	# 32位16进制字符串
   ```

三、使用示例

```
>>> sm3("123");
=> "6e0f9e14344c5406a0cf5a3b4dfb665f87f4a771a31f7edbb5c72874a32b2957"
>>> 
>>> $encrypt = sm4_encrypt("hello world")
=> "8a69dcc5e2476196b17d3b4f28972655"
>>> sm4_decrypt($encrypt)
=> "hello world"
>>> 
```



