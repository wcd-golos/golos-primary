<?php
class System_Session extends System
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected static $_instance = null;

    /**
    * Documentation
    * @author
    * @return mixed
    */
	function __construct()
	{
		parent::__construct();
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _init()
    {
        if (isset($this->_config->Directory)) {
            ini_set('session.save_path', Zend_Registry::get('AppFolder') . $this->_config->Directory);
        }
		if (isset($this->_config->MaxLifeTime)) {
            ini_set('session.gc_maxlifetime', $this->_config->MaxLifeTime);
		}
		if (isset($this->_config->CookiePath)) {
            ini_set('session.cookie_path', $this->_config->CookiePath);
		}
		
		ini_set('session.cookie_httponly', true);

		if (isset($_GET['PHPSESSID'])){
            Zend_Session::setId($_GET['PHPSESSID']);
		}
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _run()
    {
        Zend_Session::start();
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function getParam($ParametrName, $DefaultValue = null)
    {
        return isset($_SESSION[$ParametrName]) ? $_SESSION[$ParametrName] : $DefaultValue;
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function setParam($ParametrName, $ParamValue)
    {
        $_SESSION[$ParametrName] = $ParamValue;
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function isSetParam($ParametrName)
    {
    	return isset($_SESSION[$ParametrName]);
    }

    /**
    * Documentation
    * @author keeper
    * @return mixed
    */
    public static function unsetParam($ParametrName)
    {
    	if (isset($_SESSION[$ParametrName])) unset($_SESSION[$ParametrName]);
    }

    /**
    * Documentation
    * @author keeper
    * @return mixed
    */
    public static function destroy()
    {
        session_destroy();
        session_start();
    }

}