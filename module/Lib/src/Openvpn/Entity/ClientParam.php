<?php
namespace Lib\Openvpn\Entity;

use Lib\Base\AbstractEntity;

class ClientParam extends AbstractEntity
{
    protected $accountId;
    protected $params;
    protected $modified;
    protected $dirty;

    public function __construct($accountId = null, $params = null, $modified = null, $dirty = null)
    {
        $this->accountId  = $accountId;
        $this->params      = $params;
        $this->modified   = $modified;
        $this->dirty    = (bool)$dirty;
    }

    public function addParam($key, $value)
    {
        $params = $this->getParams();
        $params[$key] = $value;
        return $this->setParams($params);
    }
    public function deleteParam($key)
    {
        $params = $this->getParams();
        if(!isset($params[$key])){
            throw new \Exception(__CLASS__." delete key:[$key] does not exist in params.");
        }
        unset( $params[$key] );
        return $this->setParams($params);
    }

    // Getter
    public function getAccountId()
    {
        return $this->accountId;
    }
    public function getParams()
    {
        return json_decode($this->params, true);
    }
    public function getParam($key)
    {
        $params = $this->getParams();
        return $params[$key];
    }
    public function getModified()
    {
        return $this->modified;
    }
    public function getDirty()
    {
        return (bool)$this->dirty;
    }

    // Setter
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }
    public function setParams(array $params)
    {
        $this->params =  json_encode($params, true);
        $this->setDirty(true);
        $this->setModified( date("Y-m-d H:i:s") );
        return $this;
    }
    public function setParam($key, $value)
    {
        $this->addParam($key, $value);
        return $this;
    }
    public function setModified($modified)
    {
        $this->modified = $modified;
        return $this;
    }
    public function setDirty($dirty)
    {
        $this->dirty = (bool)$dirty;
        return $this;
    }

    /**
     * Implement Abstract exchangearray
     */
    public function exchangeArray(array $data)
    {
        $this->accountId    = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->params        = (!empty($data['params'])) ? $data['params'] : null;
        $this->modified     = (!empty($data['modified'])) ? $data['modified'] : null;
        $this->dirty      = (!empty($data['dirty'])) ? (bool)$data['dirty'] : null;
    }

    /**
     * Implement Abstract getarraycopy
     */
    public function getArrayCopy()
    {
        return array(
            'account_id' => $this->accountId,
            'params'      => $this->params,
            'modified'   => $this->modified,
            'dirty'    => (bool)$this->dirty,
        );
    }
}
