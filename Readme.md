
1. 将`.env.example`复制成`.env`文件
```php
cp .env.example .env

# 生成laravel key
php artisan key:generate

# 配置数据库和其他信息
```

2. 数据库迁移
```php
php artisan migrate --seed
或者
php artisan migrate:refresh --seed
```

3. 定时任务验证
```php
php artisan schedule:run
```

3. 开启定时任务
```php
* * * * * php /home/vagrant/bitcon/artisan schedule:run >> /dev/null 2>&1
```



#### Log
1. 目前可以实时抓取最优挂单，抓取尽量实现了两边同时抓取，入库。
目前问题在于，抓取来的数据量大，且没有用。
考虑改为每次更新单条记录。并开一个任务，每次抓取这两条记录进行分析。

增加command，用于系统定时任务直接调用程序，不用laravel的定时任务控制
~~创建count_data表，用于记录每次计算的结果，盈利状态，是否可交易，是否交易成功，是否撤单，交易后状态~~
~~创建money表，用于记录当前金额分布状态~~