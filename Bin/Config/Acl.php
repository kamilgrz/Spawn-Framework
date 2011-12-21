<?php

$config['role.table_name'] = 'acl_role';
$config['role.id'] = 'id';
$config['role.name'] = 'name';

$config['group.table_name'] = 'acl_group';
$config['group.id'] = 'id';
$config['group.name'] = 'name';

$config['group_role.table_name'] = 'acl_group_role';
$config['group_role.id'] = 'id';
$config['group_role.group_id'] = 'group_id';
$config['group_role.role_id'] = 'role_id';

$config['group_role.table_name'] = 'acl_group_role';
$config['group_role.id'] = 'id';
$config['group_role.group_id'] = 'group_id';
$config['group_role.role_id'] = 'role_id';

$config['group_inherit.table_name'] = 'acl_group_inherit';
$config['group_inherit.id'] = 'id';
$config['group_inherit.group_id'] = 'group_id';
$config['group_inherit.inherit_id'] = 'inherit_id';

$config['user_group.table_name'] = 'acl_user_group';
$config['user_group.id'] = 'id';
$config['user_group.user_id'] = 'user_id';
$config['user_group.group_id'] = 'group_id';

$config['user_role.table_name'] = 'acl_user_role';
$config['user_role.id'] = 'id';
$config['user_role.user_id'] = 'user_id';
$config['user_role.role_id'] = 'role_id';

return $config;

