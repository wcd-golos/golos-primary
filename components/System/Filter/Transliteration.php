<?php
/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Documentation
 * @author keeper
 * @category
 * @package
 * @subpackage
 * @copyright Copyright (c) 2005-2012 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Filter_Transliteration implements Zend_Filter_Interface
{


    /**
     * Sets the filter options
     * @author keeper
     * @param  string|array|Zend_Config $options
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * translit to en
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        // Сначала заменяем "односимвольные" фонемы.
        $value = strtr($value, array(
                               'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e',
                               'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n',
                               'о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f',
                               'х'=>'h','ъ'=>'\'','ы'=>'i','э'=>'e','і'=>'i'));
        $value = strtr($value,   array(
                               'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'E',
                               'З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
                               'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F',
                               'Х'=>'H','Ъ'=>'\'','Ы'=>'I','Э'=>'E','І'=>'I'));

        // Затем - "многосимвольные".
        $value = strtr($value, array(
                            "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
                            "Ж"=>"Zh", "Ц"=>"Ts", "Ч"=>"Ch", "Ш"=>"Sh", "Щ"=>"Shch","Ь"=>"", "Ю"=>"Yu", "Я"=>"Ya",
                            "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
                            )
                       );
        return $value;
    }


    /**
     * Documentation
     *
     * @var mixed
     */
    protected static $_instance;

    /**
    * Return System_Filter_Transliteration static instance
    * @author keeper
    * @return System_Filter_Transliteration
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
