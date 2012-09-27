<?php
/**
* Spawn Framework
*
* user acl
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Acl
*/
namespace Spawn\Acl;
class User
{
    /**
     * @var \Spawn\Db
     */
    protected $_db;
    
    /**
     *
     * @var integer
     */
    protected $_userId;


    /**
     * @param \Spawn\Db $db
     */
    public function __construct(\Spawn\Db $db = null)
    {
        $this -> _db = (null == $db)? new \Spawn\Db() : $db;
        $this -> _config = \Spawn\Config::load('Acl') -> getAll();
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setUserId($id)
    {
        $this -> _userId = $id;
        return $this;
    }
    
    /**
     *
     * @return object
     */
    public function getUserGroup()
    {
        //SELECT gr.* FROM user_group AS ug JOIN group AS gr WHERE ug.group_id = gr.id AND ug.user_id = ?
        $group = $this -> _db
              -> query('SELECT g.*
                    FROM ' . $this -> _config['user_group.table_name'] . ' AS ug
                    JOIN ' . $this -> _config['group.table_name'] . ' AS g
                    WHERE ug.' . $this -> _config['user_group.group_id'] . ' = g.' . $this -> _config['group.id'] . '
                    AND ug.user_id = ?'
                     , $this -> _userId)
              -> fetchAll();
       
        return $group;
    }

    /**
     *
     * @return object
     */
     public function getUserGroupRole()
    {
        $group = $this -> _db
              -> query('SELECT gr.group_id , r.*
                    FROM ' . $this -> _config['user_group.table_name'] . ' AS ug
                    JOIN ' . $this -> _config['group_role.table_name'] . ' AS gr
                    JOIN ' . $this -> _config['role.table_name'] . ' AS r
                    WHERE ug.' . $this -> _config['user_group.group_id'] . ' = gr.' . $this -> _config['group_role.group_id'] . '
                    AND r.' . $this -> _config['role.id'] . ' = gr.' . $this -> _config['group_role.role_id'] . '
                    AND ug.' . $this -> _config['user_group.user_id'] . ' = ?'
                     , $this -> _userId)
              -> fetchAll();
       
        return $group;
    }


    /**
     * @return object
     */
    public function getUserRole()
    {
         //SELECT r.name FROM user_role AS ur JOIN role AS r WHERE ur.role_id = r.id AND ur.user_id = ?
        $role = $this -> _db               
                      -> query('SELECT r.*
                         FROM ' . $this -> _config['user_role.table_name'] . ' AS ur
                         JOIN ' . $this -> _config['role.table_name'] . ' AS r
                         WHERE ur.' . $this -> _config['user_role.role_id'] . ' = r.' . $this -> _config['role.id'] . '
                         AND ur.' . $this -> _config['user_role.user_id'] . ' = ?' ,
                             $this -> _userId)
                      -> fetchAll();

        return $role;
    }

    /**
     *
     * @param integer $id
     * @return $this
     */
    public function addUserRole($id)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['user_role.table_name'] . ' SET ' . $this -> _config['user_role.user_id'] . ' = ?, ' . $this -> _config['user_role.role_id'] . ' = ?', $this -> _userId, $id);
        return $this;
    }

    /**
     *
     * @param integer $id
     * @return $this
     */
    public function addUserGroup($id)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['user_group.table_name'] . ' SET ' . $this -> _config['user_group.user_id'] . ' = ?, ' . $this -> _config['user_group.group_id'] . ' = ?', $this -> _userId, $id);
        return $this;
    }
    
    /**
     *
     * @param integer $id
     * @return $this
     */
    public function deleteUserRole($id)
    {
        $this -> _db -> query('DELETE FROM ' . $this -> _config['user_role.table_name'] . ' WHERE ' . $this -> _config['user_role.user_id'] . ' = ? AND ' . $this -> _config['user_role.role_id'] . ' = ?', $this -> _userId, $id);
        return $this;
    }

    /**
     *
     * @param integer $id
     * @return $this
     */
    public function deleteUserGroup($id)
    {
        $this -> _db -> query('DELETE FROM ' . $this -> _config['user_group.table_name'] . ' WHERE ' . $this -> _config['user_group.user_id'] . ' = ? AND ' . $this -> _config['user_group.group_id'] . ' = ?', $this -> _userId, $id);
        return $this;
    }

    
}
