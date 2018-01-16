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
abstract class System_Form_Edit extends Zend_Form
{
    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function init()
	{
		parent::init();
		$this->createElements();
		$this->addElement('submit', 'submit', array('label' => 'Add ' . $this->getObjectName()));
		$this->createDecorators();
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	abstract public function createElements();

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function createDecorators()
	{
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array('HtmlTag', array('tag' => 'div', 'class' => 'element')),
        ));
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function setDefaults(array $defaults)
	{
		if (isset($defaults['ID']) && $defaults['ID']){
			$element = $this->getElement('submit');
			$element->setLabel('Edit ' . $this->getObjectName());
		}
		return parent::setDefaults($defaults);
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function getObjectName()
    {
        $classes = explode('_', get_class($this));
        array_pop($classes);
        array_pop($classes);
        return implode('_', $classes);
    }
}
