<?php
namespace Lib;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Lib\Radius as Radius;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/'
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                /*
                 * Data source
                 */
                // Radius db Abstract
                'Db\Radius' => function ($sm) {
                    return $sm->get('radius_prod');
                },

                /*
                 * Mapper - tablegateway
                 */
                // CheckMapper
                'Lib\Radius\CheckMapper' =>  function ($sm) {
                    $tableGateway = $sm->get('Lib\Radius\CheckTableGateway');
                    return new Radius\Mapper\CheckMapper($tableGateway);
                },
                'Lib\Radius\CheckTableGateway' =>  function ($sm) {
                    $adapter = $sm->get('Db\Radius');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Radius\Entity\CheckEntity() );
                    return new TableGateway('radcheck', $adapter, null, $resultSetPrototype);
                },
                // ReplyMapper
                'Lib\Radius\ReplyMapper' =>  function ($sm) {
                    $tableGateway = $sm->get('Lib\Radius\ReplyTableGateway');
                    return new Radius\Mapper\ReplyMapper($tableGateway);
                },
                'Lib\Radius\ReplyTableGateway' =>  function ($sm) {
                    $adapter = $sm->get('Db\Radius');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Radius\Entity\ReplyEntity() );
                    return new TableGateway('radreply', $adapter, null, $resultSetPrototype);
                },
                // GroupCheckMapper
                'Lib\Radius\GroupCheckMapper' =>  function ($sm) {
                    $tableGateway = $sm->get('Lib\Radius\GroupCheckTableGateway');
                    return new Radius\Mapper\GroupCheckMapper($tableGateway);
                },
                'Lib\Radius\GroupCheckTableGateway' =>  function ($sm) {
                    $adapter = $sm->get('Db\Radius');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Radius\Entity\GroupCheckEntity() );
                    return new TableGateway('radgroupcheck', $adapter, null, $resultSetPrototype);
                },
                // GroupReplyMapper
                'Lib\Radius\GroupReplyMapper' =>  function ($sm) {
                    $tableGateway = $sm->get('Lib\Radius\GroupReplyTableGateway');
                    return new Radius\Mapper\GroupReplyMapper($tableGateway);
                },
                'Lib\Radius\GroupReplyTableGateway' =>  function ($sm) {
                    $adapter = $sm->get('Db\Radius');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Radius\Entity\GroupReplyEntity() );
                    return new TableGateway('radgroupreply', $adapter, null, $resultSetPrototype);
                },
                // UserGroupMapper
                'Lib\Radius\UserGroupMapper' =>  function ($sm) {
                    $tableGateway = $sm->get('Lib\Radius\UserGroupTableGateway');
                    return new Radius\Mapper\UserGroupMapper($tableGateway);
                },
                'Lib\Radius\UserGroupTableGateway' =>  function ($sm) {
                    $adapter = $sm->get('Db\Radius');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Radius\Entity\UserGroupEntity() );
                    return new TableGateway('radusergroup', $adapter, null, $resultSetPrototype);
                },

                /*
                 * Mapper - composite of sub mappers
                 */
                // AccountMapper 
                'Lib\Radius\AccountMapper' =>  function ($sm) {
                    //$adapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $adapter = $sm->get('Db\Radius');
                    $entity = new Radius\Entity\AccountEntity();
                    $checkMapper = $sm->get('Lib\Radius\CheckMapper');
                    $groupMapper = $sm->get('\Lib\Radius\UserGroupMapper');
                    $groupCheckMapper = $sm->get('\Lib\Radius\GroupCheckMapper');
                    return new Radius\Mapper\AccountMapper($adapter, $entity, $checkMapper, $groupMapper, $groupCheckMapper);
                },

                /*
                 * Web Service Respondent 
                 */
                'Lib\Radius\AccountRespondent' =>  function ($sm) {
                    $accountMapper = $sm->get('\Lib\Radius\AccountMapper');
                    return new Radius\Respondent\AccountRespondent($accountMapper);
                },
            ),
        );
    }
}
