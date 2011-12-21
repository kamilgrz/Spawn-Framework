<?php
$config['Default'] = array(
			'user' => 'user',
			'pass' => 'password',
			'dsn' => 'mysql:host=localhost;dbname=name',
			'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
);
return $config;

