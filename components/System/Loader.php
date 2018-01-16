<?php
/**
 * Documentation
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Loader extends Zend_Loader
{
	/*
     * If the file was not found in the $dirs, or if no $dirs were specified,
     * it will attempt to load it from PHP's include_path.
     *
     * @param string $class      - The full class name of a Zend component.
     * @param string|array $dirs - OPTIONAL Either a path or an array of paths
     *                             to search.
     * @return void
     * @throws Zend_Exception
     */
    public static function loadClass($class, $dirs = null)
    {
    	if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        if ((null !== $dirs) && !is_string($dirs) && !is_array($dirs)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception('Directory argument must be a string or an array');
        }

        // autodiscover the path from the class name
        if (preg_match("/^(\w+)_(\w+)Controller$/", $class, $matches)) {
            //print_r($matches);
            $file = $matches[1].DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$matches[2].'Controller.php';
        } elseif (count(explode('_', $class)) != 1) {
        	$file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        } else {
        	$file = $class . DIRECTORY_SEPARATOR . $class . '.php';
        }
        
        if (!empty($dirs)) {
            // use the autodiscovered path
            $dirPath = dirname($file);
            if (is_string($dirs)) {
                $dirs = explode(PATH_SEPARATOR, $dirs);
            }
            foreach ($dirs as $key => $dir) {
                if ($dir == '.') {
                    $dirs[$key] = $dirPath;
                } else {
                    $dir = rtrim($dir, '\\/');
                    $dirs[$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
                }
            }
            $file = basename($file);
            //print_r($dirs);
            self::loadFile($file, $dirs, true);
        } else {
            self::_securityCheck($file);
            $dir = '';
            $arrComponentsPaths = Zend_Registry::get('ComponentsPath');
            if (is_string($arrComponentsPaths)) $arrComponentsPaths = array($arrComponentsPaths);
            if (is_array($arrComponentsPaths) && count($arrComponentsPaths)) {
                foreach ($arrComponentsPaths as $ComponentsPath) {
                    //echo $ComponentsPath . '/' . $file;
                    if (file_exists($ComponentsPath . '/' . $file)) {
                        $dir = $ComponentsPath . '/';
                        break;
                    }
                }
            }
            //echo $dir . $file . '<br/>';
            include_once $dir . $file;
        }
		
        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }

    /**
     * spl_autoload() suitable implementation for supporting class autoloading.
     *
     * Attach to spl_autoload() using the following:
     * <code>
     * spl_autoload_register(array('Zend_Loader', 'autoload'));
     * </code>
     *
     * @param string $class
     * @return string|false Class name on success; false on failure
     */
    public static function autoload($class)
    {
        try {
            self::loadClass($class);
            return $class;
        } catch (Exception $e) {
            return false;
        }
    }
}