#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

$dsn = \getenv('DATABASE');
$prt = \parse_url($dsn);
$pdo = new \PDO(\sprintf('mysql:dbname=%s;host=%s', \trim($prt['path'], '/'), $prt['host']), $prt['user'], $prt['pass']);

$pdo->exec('
  CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `status` TINYINT(1) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;
');

$pdo->exec('TRUNCATE TABLE users;');

$stmt = $pdo->prepare(
    'INSERT INTO users (first_name, last_name, status) VALUES (:first_name, :last_name, :status);'
);

$count = $argv[1] ?? 1000;
$faker = Faker\Factory::create();

for ($i = 0; $i < $count; $i++) {
    $stmt->execute([
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'status' => (int) \rand(0, 1),
    ]);
    
    echo '.';
}

echo \PHP_EOL . "Done inserting {$count} rows." . \PHP_EOL;
