
1. 将`.env.example`复制成`.env`文件
```php
cp .env.example .env

# 生成laravel key
php artisan key:generate

# 配置数据库和其他信息
```

2. 数据库迁移
```php
php artisan migrate
```

3. 定时任务验证
```php
php artisan schedule:run
```

3. 开启定时任务
```php
* * * * * php /home/vagrant/bitcon/artisan schedule:run >> /dev/null 2>&1
```
