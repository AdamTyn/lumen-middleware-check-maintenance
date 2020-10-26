# lumen-middleware-check-maintenance

移植Laravel的 `CheckForMaintenanceMode` [检查维护模式]中间件到Lumen

# Usage

在 **'app/bootstrap/app.php'** 中注册全局中间件：

```
$app->middleware([
    \AdamTyn\Lumen\Middleware\CheckForMaintenanceMode::class
]);
```

# Attention

本组件单独使用并不能达到Laravel一样的效果，必须同时安装下面2个组件

|                           Library                            |                        Description                         |
| :----------------------------------------------------------: | :--------------------------------------------------------: |
| [adamtyn/lumen-artisan-down](https://github.com/AdamTyn/lumen-artisan-down) | 移植Laravel的 `php artisan down` [进入维护模式]指令到Lumen |
| [adamtyn/lumen-artisan-up](https://github.com/AdamTyn/lumen-artisan-up) |  移植Laravel的 `php artisan up` [退出维护模式]指令到Lumen  |
