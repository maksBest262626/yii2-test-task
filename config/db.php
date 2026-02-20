<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';dbname=' . (getenv('DB_NAME') ?: 'yii2_catalog'),
    'username' => getenv('DB_USER') ?: 'yii2',
    'password' => getenv('DB_PASSWORD') ?: 'yii2password',
    'charset' => 'utf8mb4',
];
