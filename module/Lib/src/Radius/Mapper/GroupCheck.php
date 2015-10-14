<?php
namespace Lib\Radius\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Lib\Base\AbstractMapper;
use Lib\Base\Exception as Exception;

class GroupCheck extends AbstractMapper
{
    /**
     * Overwrite MapperAbstract primaryKey 
     * The db primary key's column name 
     */
    protected $primaryKeys = array('id');

    /**
     * Find row by user group
     *
     * @return Zend\Db\ResultSet\ResultSet
     */
    public function findByGroup($groupname)
    {
        $rowset = $this->tableGateway->select(array('groupname' => $groupname));
        if ($rowset->count() <=0) {
            throw new Exception\ObjectNotFoundException(__CLASS__." Could not find row: [$groupname]");
        }
        return $rowset;
    }

    /**
     * Find row by user group
     *
     * @return Zend\Db\ResultSet\ResultSet
     */
    public function findByGroups(array $groupnames)
    {
        $rowset = $this->tableGateway->select(array('groupname' => $groupnames));
        if ($rowset->count() <=0) {
            throw new Exception\ObjectNotFoundException(__CLASS__." Could not find row: [$groupname]");
        }
        return $rowset;
    }

    /**
     * Find row by username and attribute
     *
     * @return Lib\Radius\Model\GroupCheck
     */
    public function findByGroupAttrOp($groupname, $attribute, $op)
    {
        $rowset = $this->tableGateway->select(
            array('groupname' => $groupname, 'attribute' => $attribute, 'op' => $op)
        );
        $row = $rowset->current();
        if (!$row) {
            throw new Exception\ObjectNotFoundException(__CLASS__." Could not find row: groupname [$groupname] and attribute [$attribute]");
        }
        return $row;
    }
}
