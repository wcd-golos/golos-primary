<?php
/**
 * Хелпер для расчета разницы времени
 * @author norbis
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_View_Helper_Relativetime
{
    /**
    * Documentation
    * @author norbis
    * @return mixed
    */    
    public function relativetime ($unixtime, $accuracy = 2, $splitter = ', ')
    {
        if (time() > $unixtime) {
            $unixtime = time() - $unixtime;
        } else {
            $unixtime = $unixtime - time();
        }
        $mt = new Zend_Measure_Time($unixtime);
        $units = $mt->getConversionList();
        $tr = Zend_Registry::get('Zend_Translate');
        $chunks = array(Zend_Measure_Time::YEAR , Zend_Measure_Time::WEEK , Zend_Measure_Time::DAY , Zend_Measure_Time::HOUR , Zend_Measure_Time::MINUTE , Zend_Measure_Time::SECOND);
        $translations = array('year' => array($tr->_('year') , $tr->_('years')) , 'week' => array($tr->_('week') , $tr->_('weeks')) , 'day' => array($tr->_('day') , $tr->_('days')) , 'h' => array($tr->_('hour') , $tr->_('hours')) , 'min' => array($tr->_('minute') , $tr->_('minutes')) , 's' => array($tr->_('second') , $tr->_('seconds')));
        $measure = array();
        for ($i = 0; $i < count($chunks); $i ++) {
            $chunk_seconds = $units[$chunks[$i]][0];
            if ($unixtime >= $chunk_seconds) {
                $measure[$units[$chunks[$i]][1]] = floor($unixtime / $chunk_seconds);
                $unixtime %= $chunk_seconds;
            }
        }
        $measure = array_slice($measure, 0, $accuracy, true);
        $str = '';
        foreach ($measure as $key => $val) {
            $unit = $translations[$key];
            if ($val == 1) {
                $unit = $unit[0];
            } else {
                $unit = $unit[1];
            }
            $str .= $val . ' ' . $unit . $splitter;
        }
        return substr($str, 0, 0 - strlen($splitter));
    }
}