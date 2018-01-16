<?php
/**
 * Development Bar 
 *
 * This plugin will add a little bar to the bottom of your webpage
 * while you browse your webpage in development mode.
 * 
 * It will show you:
 * - Current ZF version that is in use.
 * - Avarage dispatch time for the last 100 page views.
 * - The amount of time the current dispatch is slower 
 *   or faster than the avarage 10 last dispatches.
 * - Amount and information about known Cookies
 * - Number of executed SQL-queries and the time it took
 * - Number of SQL-queries executed per second
 * - The longest SQL-query in a javascript alert window
 *  
 * @todo instead of pictures for the arrows use a canvas. 
 *
 * @author Mathias Johansson <hi@mathiasjohansson.se>
 * @licence Use it however you want, drop me an e-mail 
 * if you feel like it or have some improvments.
 */
class System_Controller_Plugin_DevelopmentBar extends Zend_Controller_Plugin_Abstract
{
    private $_iStart = 0;
    private $_iStop = 0;
    private $_oNs = null;
    private $_aExecTime = array();
    // A baseUrl will be prepended to these paths
    private $_sUpArrow = '/img/red_up_arrow.gif';
    private $_sDowArrow = '/img/green_down_arrow.gif';
    private $_iAvarageOf = 100;
    public function __construct ()
    {
        if (! Zend_Session::isStarted()) {
            Zend_Session::start();
        }
        $this->_oNs = new Zend_Session_Namespace('devNamespace');
        // Workaround due to a bug in php 5.2.2 where one can 
        // not indirectly use namespaced arrays
        if (isset($this->_oNs->aExecTime)) {
            $this->_aExecTime = $this->_oNs->aExecTime;
        }
    }
    
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $this->_iStart = microtime(true);
    }
    
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
        if (! $this->getRequest()->isXmlHttpRequest()) {
            // Get execution time for this dispatch
            $this->_iStop = microtime(true);
            $iExecTime = $this->_iStop - $this->_iStart;
            // Calculate avarage execution time 
            array_push($this->_aExecTime, $iExecTime);
            if (count($this->_aExecTime) > $this->_iAvarageOf) {
                array_shift($this->_aExecTime);
            }
            $iAvarageExecTime = 0;
            foreach ($this->_aExecTime as $iTime) {
                $iAvarageExecTime += $iTime;
            }
            // Save array back to the session
            $this->_oNs->aExecTime = $this->_aExecTime;
            $iAvarageExecTime = $iAvarageExecTime / count($this->_aExecTime);
            $iExecDiff = $iExecTime - $iAvarageExecTime;
            $sBaseUrl = $this->getRequest()->getBaseUrl();
            $sDiffImg = '<img src="' . $sBaseUrl . $this->_sUpArrow . '"/>';
            if ($iExecDiff < 0) {
                $sDiffImg = '<img src="' . $sBaseUrl . $this->_sDowArrow . '"/>';
            }
            // Cookies
            $iCookies = count($_COOKIE);
            $sCookies = '';
            foreach ($_COOKIE as $sName => $sValue) {
                $sCookies .= $sName . ' => ' . $sValue . "\\n";
            }
            // Calculate sql information
            $oDb = System_Database::getDB();
            $oProfiler = $oDb->getProfiler();
            $totalTime = $oProfiler->getTotalElapsedSecs();
            $queryCount = $oProfiler->getTotalNumQueries();
            $longestTime = 0;
            $avarageQueryTime = 0;
            $queriesPerSecond = 0;
            $longestQuery = null;
            if ($oProfiler->getQueryProfiles()) {
                foreach ($oProfiler->getQueryProfiles() as $oQuery) {
                    if ($oQuery->getElapsedSecs() > $longestTime) {
                        $longestTime = $oQuery->getElapsedSecs();
                        $longestQuery = $oQuery->getQuery();
                    }
                }
                $avarageQueryTime = $totalTime / $queryCount;
                $queriesPerSecond = $queryCount / $totalTime;
            }
            $sDebug = '<style type="text/css">#debugFooter{background-color: #eee;border-top: 1px dashed #ccc;text-align: left;font-size:10px;color:#CC0000;font-family:sans-serif;padding: 5px;width: 100%;position:fixed; left:0px; bottom:0px;}.c{ float:left;padding-right:8px;}.d{float:left;color:#666;padding-right:8px;}</style>';
            $sDebug .= '<div id="debugFooter">';
            $sDebug .= '<div class="c"><strong>ZF:</strong> ' . Zend_Version::VERSION . '</div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong>Dispatch:</strong> ' . sprintf("%01.4f", $iExecTime) . ' <abbr title="seconds">s</abbr> (' . $sDiffImg . ' ' . (($iExecDiff > 0) ? '+' : '') . sprintf("%01.4f", $iExecDiff) . ' <abbr title="seconds">s</abbr>)</div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong><a href="javascript:alert(\'' . $sCookies . '\');">Cookies</a></strong> (' . $iCookies . ')</div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong>Executed:</strong> ' . $queryCount . ' queries in ' . sprintf("%01.4f", $totalTime) . ' <abbr title="seconds">s</abbr></div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong>Average query:</strong> ' . sprintf("%01.4f", $avarageQueryTime) . ' <abbr title="seconds">s</abbr></div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong><abbr title="Queries per second">Queries/s</abbr>:</strong> ' . floor($queriesPerSecond) . '</div><div class="d">|</div>';
            $sDebug .= '<div class="c"><strong><a href="javascript:alert(\'' . preg_replace('/\'/', '\\\'', htmlentities($longestQuery)) . '\');">Longest query</a>:</strong> ' . sprintf("%01.4f", $longestTime) . ' <abbr title="seconds">s</abbr></div>';
            $sDebug .= '</div>';
            $this->getResponse()->setBody($this->getResponse()->getBody('default') . $sDebug, 'default');
        }
    }
}