<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=nden2',
            'username' => 'nden_user',
            'password' => 'Guk8ITDA',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            // Продолжительность кеширования схемы.
            'schemaCacheDuration' => 31536000,
            // Название компонента кеша, используемого для хранения информации о схеме
            'schemaCache' => 'cache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
    ],
];
