<?php 
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Ip.php 13104 2008-12-08 22:31:50Z tjohns $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

//Class checks URL. Class was created by Sergey Pinaev (pinai4)

class System_Validate_Url extends Zend_Validate_Abstract
{
    const NOT_URL = 'notDigits';

    protected $_messageTemplates = array(
        self::NOT_URL => "'%value%' URL is incorrect"
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        if (!Zend_Uri_Http::check($value)) {
            $this->_error();
            return false;
        }

        return true;
    }
}
?>