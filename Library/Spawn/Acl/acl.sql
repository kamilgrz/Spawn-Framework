CREATE TABLE acl_role(
id int(11) NOT NULL auto_increment,
name varchar(55) NOT NULL ,
UNIQUE KEY id (id, name)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE acl_group(
id int(11) NOT NULL auto_increment,
name varchar(55) NOT NULL ,
UNIQUE KEY id (id, name)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE acl_group_role(
id int(11) NOT NULL auto_increment,
group_id int NOT NULL ,
role_id int not null,
UNIQUE KEY id (id)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE acl_group_inherit(
id int(11) NOT NULL auto_increment,
group_id int NOT NULL ,
inherit_id int not null,
UNIQUE KEY id (id)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE acl_user_group(
id int(11) NOT NULL auto_increment,
user_id int not null,
group_id int NOT NULL ,
UNIQUE KEY id (id)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE acl_user_role(
id int(11) NOT NULL auto_increment,
user_id int not null,
role_id int NOT NULL ,
UNIQUE KEY id (id)
) TYPE=MyISAM DEFAULT CHARSET=utf8;