# PHP Platforms Benchmarks

* AMPHP
* FPM
* RoadRunner
* Swoole

Based on configs from [mrsuh/php-load-test](https://github.com/mrsuh/php-load-test).

### Application

* PHP 7.3.9
* MySQL 8.0.12

## Installation

#### FPM
```sh
cd platform/fpm && docker-compose up
```

#### RoadRunner
```sh
cd platform/roadrunner && docker-compose up
```

#### AMPHP
```sh
cd platform/amphp && docker-compose up
```

#### Swoole
```sh
cd platform/swoole && docker-compose up
```

## Benchmarks
+ Update `bench/.env` with your username and token from [Overload](https://overload.yandex.net)
+ Put **ssh** public key to target server if needed
+ Tank it:
```sh
make rps1000
```
