<?php
$config['table'] = 'user';
$config['salt'] = 'sfSalt';	
$config['id'] = 'id';
$config['name'] = 'name';
$config['password'] = 'pass';
$config['prefix'] = '';

$config['toAdd'] = array('name', 'pass');

$config['remember.expire'] = 31556926;

return $config;

