## php grpc客户端

### composer安装：

```

"repositories": [
    {
        "type": "git",
        "url": "https://github.com/luoluoluo/php-grpc-client"
    }
],

"require": {
    "wanshi/php-grpc-client": "dev-master"
}

```

### 使用

```

use Wanshi\GrpcClient\GrpcClient;

$config = [
    'name' => 'user',
    'host' => '127.0.0.1',
    'port' => 11001
];
$res = new GrpcClient($config);
$res->login();

```
