<?php
/**
 * Created by PhpStorm.
 * User: yafei
 * Date: 2016-01-11
 * Time: 5:34 PM
 */

use Fabiang\Xmpp\Util\XML;
use Fabiang\Xmpp\Protocol\ProtocolImplementationInterface;

/**
 * Register new user zyf
 * @param string $username
 * @param string $password
 * @param string $email
 * @package XMPP\Protocol
 * @category XMPP
 */
class XRegister implements ProtocolImplementationInterface
{
    protected $__data = array();
    protected $username = "zlj";
    protected $password = "123456";
    protected $email = "zlj@apolloyl.com";
    /**
     * Get all values
     */
    public function getProperties()
    {
        return $this->__data;
    }
    /**
     * Get a value
     * @param type $key
     * @return type
     */
    public function __get($key)
    {
        return isset($this->__data[$key]) ? $this->__data[$key] : NULL;
    }
    /**
     * Set value
     * @param type $key
     * @param type $value
     * @return \XRegister
     */
    public function __set($key, $value)
    {
        $this->__data[$key] = $value;
        return $this;
    }
    /**
     * Build XML message
     * @return type
     */
    public function toString()
    {
        $query = "<iq type='set' id='%s'><query xmlns='jabber:iq:register'><username>%s</username><password>%s</password><email>%s</email></query></iq>";
        return XML::quoteMessage($query, XML::generateId(), (string) $this->username, (string) $this->password, (string) $this->email);
    }
}