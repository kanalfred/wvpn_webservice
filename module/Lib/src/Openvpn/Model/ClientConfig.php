<?php
namespace Lib\Openvpn\Model;

use Lib\Openvpn\Entity\ClientConfig as ClientConfigEntity;
/**
 * Business rules for the ClientConfig 
 */
class ClientConfig extends ClientConfigEntity
{
    // business level constants
    // business level properties
    // business level methods 

    const CONFIG_TEMPLATE_FILE = '/var/www/vhosts/dev/api-test/httpdocs/ca1-vpn-client-login.ovpn';
    private $mapper;
    private $buildConfigWorker;

    public function __construct($mapper = null, $buildConfigWorker = null)
    {
        $this->mapper = $mapper;
        $this->buildConfigWorker = $buildConfigWorker;
        parent::__construct();
    }

    public function buildConfigFile($account, $server){
        // call worker to build config
        $configTempalte = $this->getConfigTemplate();
        $config = $this->buildConfigWorker->run($account, $server, $configTempalte);

        $this->accountId = $account;
        $this->server    = $server;
        $this->config    = $config;
        $this->modified  = date("Y-m-d H:i:s");
    }

    public static function getConfigTemplate(){
        return $config = file_get_contents(self::CONFIG_TEMPLATE_FILE);
    }


    /*public function getConfigFile($accountId, $server)
    {
        try{
            $clientConfig = $sm->get('Lib\Openvpn\Mapper\ClientConfig')->find(array($accountId,$server));
            return $clientConfig->getConfig();
        }catch(Exception\ObjectNotFoundException $e){
            $clientKey = $sm->get('Lib\Openvpn\Model\ClientKey');
            $clientKey->buildKey( $accountId );
            $clientKeyMapper = $sm->get('Lib\Openvpn\Mapper\ClientKey')->save($clientKey); 
        }
    }*/


    // buildConfig($host, $account)  from config param
       // build key  & buile Param
          // ----- client key
          // $config = $this->getConfigTemplate(); 
          // $this->config = $this->buildKey($host, $account, $config)
          // ----- client Param
          // $this->config = $this->buildParam($host, $account, $this->config);
          // return $this->config

    // buildKeyConfig( $account, $config)
          // mapper->find($account);
              // check if key not exsit clientKey->exist($account)
                  // $keys = static:clientkey->build($account); return clientKey Objecr
                  // mapper save new build keys
              // else get keys from db
                  // $this->config = $clientKey->replaceConfigKey($keys, $this->getConfigTemplate());
              // if no change return $this->config
   
    // buildParamConfig($host, $account, $config)
          // mapper->find($account);
              // check paramConfig is updated
                  // $this->config = $ClientConfigParam->replaceConfigParam($serverName, $config);
                  // return $this-config
                  // 2 method
                  // $params = clientConfigParamMapper->findByAccount($account);
                  // foreach($params as $params){
                  //    $config = \Lib\Openvpn\Util\ConfigHelper::replaceKey($param->getparam(), $param->getValue(), $config);
                  // }
              // if no change return config
           
    // getConfigTemplate();
    // getConfigFile($serverName, $account) 
          // check if ClientConfig exist 
          // buildConfig if config not exsit 
              // $this->account = $account
              // $this->host    = $host
              // $this->modified = time();
              // $this->config = $this->buildConfig($host, $account);
              // save - configMapper->save($this);
          // if config exist || paramConfig is updated
              // configMapper->find($account)
              // $this->confg = $this->buildParamConfig($host, $account, $config)
              // save - configMapper->save($this);

//http://stackoverflow.com/questions/3191131/read-edit-save-config-files-php
    /*$file = file_get_contents('/path/to/config/file');
$matches = array();
preg_match('/^database\.params\.dbname = (.*)$/', $file, $matches);
$file = str_replace($matches[1], $new_value, $file);
file_put_contents('/path/to/config/file', $file);*/

}
