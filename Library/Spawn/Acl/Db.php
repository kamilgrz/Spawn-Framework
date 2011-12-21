<?php
/**
* Spawn Framework
*
* acl db
*
* @author  Paweł Makowski
* @copyright (c) 2010-2011 Paweł Makowski
* @license http://spawnframework.com/license New BSD License
* @package Acl
*/
namespace Spawn\Acl;
class Db
{

    /**
     * @var array
     */
    protected $_config = array();

    /**
     *
     * @var \Spawn\Db
     */
    protected $_db;

    /**
     *
     * @param \Spawn\Db $db
     */
    public function __construct(\Spawn\Db $db = null)
    {
        $this -> _db = (null == $db)? new \Spawn\Db() : $db;
        $this -> _config = \Spawn\Config::load('Acl') -> getAll();
    }

    /**
     *
     * @param integer $id
     * @return object
     */
    public function getRoleById($id)
    {
         //SELECT name FROM role WHERE id = ?
        $role = $this -> _db
                      -> query('SELECT *
                         FROM ' . $this -> _config['role.table_name'] . '
                         WHERE ' . $this -> _config['role.id'] . ' = ?'
                             , $id)
                      -> fetch();

        return $role;
    }

    /**
     *
     * @param string $name
     * @return object
     */
    public function getRoleByName($name)
    {
         //SELECT name FROM role WHERE id = ?
        $role = $this -> _db
                      -> query('SELECT *
                         FROM ' . $this -> _config['role.table_name'] . '
                         WHERE ' . $this -> _config['role.name'] . ' = ? LIMIT 1'
                             , $name)
                      -> fetch();

        return $role;
    }

    /**
     *
     * @return object
     */
    public function getRoleAll()
    {
        $role = $this -> _db
                      -> query('SELECT * FROM ' . $this -> _config['role.table_name'])
                      -> fetchAll();
        return $role;              
    }

    /**
     *
     * @param integer $id
     * @return object
     */
    public function getGroupRole($id)
    {
        //SELECT r.* FROM group_role AS gr JOIN role AS r WHERE gr.role_id = r.id AND gr.group_id = ?
        $role = $this -> _db
              -> query('SELECT r.*
                 FROM ' . $this -> _config['group_role.table_name'] . ' AS gr
                 JOIN ' . $this -> _config['role.table_name'] . ' AS r
                 WHERE gr.' . $this -> _config['group_role.role_id'] . ' = r.id
                 AND gr.' . $this -> _config['group_role.group_id'] . ' = ?',
                     $id)
              -> fetchAll();

        return $role;
    }

    /**
     *
     * @return object
     */
    public function getGroupRoleAll()
    {
        $role = $this -> _db
              -> query('SELECT r.*, gr.' . $this -> _config['group_role.group_id'] . '
                 FROM ' . $this -> _config['group_role.table_name'] . ' AS gr
                 JOIN ' . $this -> _config['role.table_name'] . ' AS r
                 WHERE gr.' . $this -> _config['group_role.role_id'] . ' = r.id')
              -> fetchAll();

        return $role;   
    }

    /**
     *
     * @param string $name
     * @return object
     */
    public function getGroupByName($name)
    {
        $role = $this -> _db
                      -> query('SELECT *
                         FROM ' . $this -> _config['group.table_name'] . '
                         WHERE ' . $this -> _config['group.name'] . ' = ? LIMIT 1'
                             , $name)
                      -> fetch();

        return $role;
    }

    /**
     *
     * @param integer $id
     * @return object
     */
    public function getGroupById($id)
    {
        $role = $this -> _db
                      -> query('SELECT *
                         FROM ' . $this -> _config['group.table_name'] . '
                         WHERE ' . $this -> _config['group.id'] . ' = ?'
                             , $id)
                      -> fetch();

        return $role;
    }

    /**
     *
     * @return object
     */
    public function getGroupAll()
    {
        $role = $this -> _db
                      -> query('SELECT * FROM ' . $this -> _config['group.table_name'])
                      -> fetchAll();

        return $role;
    }

    /**
     *
     * @param string $name
     * @return object
     */
    public function addRole($name)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['role.table_name'] . ' SET ' . $this -> _config['role.name'] . ' = ?', $name);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return object
     */    
    public function deleteRoleByName($name)
    {
        $this -> _db -> query('BEGIN;
                SELECT @id:=id FROM ' . $this -> _config['role.table_name'] . ' WHERE ' . $this -> _config['role.name'] . ' = ? LIMIT 1;     
                DELETE FROM ' . $this -> _config['role.table_name'] . ' WHERE ' . $this -> _config['role.id'] . ' = @id;
                DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.role_id'] . ' = @id;
                DELETE FROM ' . $this -> _config['user_role.table_name'] . ' WHERE ' . $this -> _config['user_role.role_id'] . ' = @id;
            COMMIT;', $name);
         
        return $this;
    }

    /**
     *
     * @param integer $id
     * @return object
     */
     //DELETE FORM role as r, group_role as gr where r.id = gr.role_id ans r.id = ?
    public function deleteRoleById($id)
    {    
        $this -> _db -> query('BEGIN;     
                    DELETE FROM ' . $this -> _config['role.table_name'] . ' WHERE ' . $this -> _config['role.id'] . ' = :id;
                    DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.role_id'] . ' = :id;
                    DELETE FROM ' . $this -> _config['user_role.table_name'] . ' WHERE ' . $this -> _config['user_role.role_id'] . ' = :id;
                 COMMIT;', array(':id' => $id)); 
      
        return $this;
    }

    /**
     *
     * @param string $name
     * @return $this
     */
    public function addGroup($name)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['group.table_name'] . ' SET ' . $this -> _config['group.name'] . ' = ?', $name);
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @param integer $role
     * @return $this
     */
    public function addGroupRole($groupId, $roleId)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['group_role.table_name'] . ' SET ' . $this -> _config['group_role.group_id'] . ' = ?, ' . $this -> _config['group_role.role_id'] . ' = ?', array($groupId, $roleId) );
        return $this;
    }
    

    /**
     *
     * @param integer $id
     * @return object
     */
    public function deleteGroupById($id)
    {
        $this -> _db -> query('BEGIN;     
                    DELETE FROM ' . $this -> _config['group.table_name'] . ' WHERE ' . $this -> _config['group.id'] . ' = :id;
                    DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.group_id'] . ' = :id;
                    DELETE FROM ' . $this -> _config['group_inherit.table_name'] . ' WHERE ' . $this -> _config['group_inherit.group_id'] . ' = :id OR   ' . $this -> _config['group_inherit.inherit_id'] . ' = :id;
                    DELETE FROM ' . $this -> _config['user_group.table_name'] . ' WHERE ' . $this -> _config['user_group.group_id'] . ' = :id;
                 COMMIT;', array(':id' => $id)); 
        return $this;
    }

    /**
     *
     * @param string $name
     * @return object
     */
    public function deleteGroupByName($name)
    {
        $this -> _db -> query('BEGIN;
            SELECT @id:=id FROM ' . $this -> _config['group.table_name'] . ' WHERE ' . $this -> _config['group.name'] . ' = ? LIMIT 1;     
            DELETE FROM ' . $this -> _config['group.table_name'] . ' WHERE ' . $this -> _config['group.id'] . ' = @id;
            DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.group_id'] . ' = @id;
            DELETE FROM ' . $this -> _config['group_inherit.table_name'] . ' WHERE ' . $this -> _config['group_inherit.group_id'] . ' = @id OR   ' . $this -> _config['group_inherit.inherit_id'] . ' = @id;
            DELETE FROM ' . $this -> _config['user_group.table_name'] . ' WHERE ' . $this -> _config['user_group.group_id'] . ' = @id;
            COMMIT;', $name);
            
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @param integer $roleId
     * @return $this
     */
    public function deleteGroupRole($groupId, $roleId)
    {
        $this -> _db -> query('DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.group_id'] . ' = ? AND ' . $this -> _config['group_role.role_id'] . ' = ?', array($groupId, $roleId) );
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @param array $role
     * @return $this
     */
    public function deleteGroupRoles($groupId, array $role)
    {
        $roleCount = count($role);
        array_push($role, $groupId);
        $this -> _db -> query('DELETE FROM ' . $this -> _config['group_role.table_name'] . ' WHERE ' . $this -> _config['group_role.role_id'] . ' IN('.implode(',', array_fill(0, $roleCount, '?') ).') AND  ' . $this -> _config['group_role.group_id'] . ' = ?', $role);
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @param integer $inheritGroupId
     * @return $this
     */
    public function addGroupInherit($groupId, $inheritGroupId)
    {
        $this -> _db -> query('INSERT INTO ' . $this -> _config['group_inherit.table_name'] . ' SET ' . $this -> _config['group_inherit.group_id'] . ' = ?, ' . $this -> _config['group_inherit.inherit_id'] . ' = ?', array($groupId, $inheritGroupId) );
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @param integer $inheritGroupId
     * @return $this
     */
    public function deleteGroupInherit($groupId, $inheritGroupId)
    {
        $this -> _db -> query('DELETE FROM ' . $this -> _config['group_inherit.table_name'] . ' WHERE ' . $this -> _config['group_inherit.group_id'] . ' = ? AND ' . $this -> _config['group_inherit.inherit_id'] . ' = ?', array($groupId, $inheritGroupId) );
        return $this;
    }

    /**
     *
     * @param integer $groupId
     * @return $this
     */
    public function getGroupInherit($groupId)
    {
        return $this -> _db -> query('SELECT * FROM ' . $this -> _config['group_inherit.table_name'] . ' WHERE ' . $this -> _config['group_inherit.group_id'] . ' = ? LIMIT 1', $groupId ) -> fetch();
    }
    
    /**
     *
     * @return object
     */
    public function getGroupInheritAll()
    {
        return $this -> _db -> query('SELECT * FROM ' . $this -> _config['group_inherit.table_name']) -> fetchAll();
    }

    /**
     *
     * @param integer $groupId
     * @return object
     */
    public function loadGroupInheritRole($groupId)
    {
         return $this -> _db
                  -> query('SELECT r.*
                         FROM ' . $this -> _config['group_inherit.table_name'] . ' AS gi
                         JOIN ' . $this -> _config['group_role.table_name'] . ' AS gr
                         JOIN ' . $this -> _config['role.table_name'] . ' as r
                         WHERE gi.' . $this -> _config['group_inherit.inherit_id'] . ' = gr.' . $this -> _config['group_role.group_id'] . '
                         AND r.' . $this -> _config['role.id'] . ' = gr.' . $this -> _config['group_role.role_id'] . '
                         AND gi.' . $this -> _config['group_inherit.group_id'] . ' = ?
                         GROUP BY r.' . $this -> _config['role.id'],
                         $groupId )
                  -> fetchAll();
    }

}
