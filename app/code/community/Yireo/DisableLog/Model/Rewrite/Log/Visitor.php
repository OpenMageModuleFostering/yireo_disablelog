<?php
/**
 * Yireo DisableLog for Magento 
 *
 * @package     Yireo_DisableLog
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (c) 2013 Yireo (http://www.yireo.com/)
 * @license     Open Software License
 */

class Yireo_DisableLog_Model_Rewrite_Log_Visitor extends Mage_Log_Model_Visitor
{
    /**
     * Object initialization
     */
    protected function _construct()
    {
        // Call upon the parent constructor
        $rt = parent::_construct();
        
        // If module is disabled
        if((bool)Mage::getStoreConfig('disablelog/settings/enabled') == false) {
            return $rt;
        }
        
        // Check disable_all flag
        $this->_skipRequestLogging = (bool)Mage::getStoreConfig('disablelog/settings/disable_all');

        // Check if logging should be disabled for a specific user-agent
        if($this->_skipRequestLogging == false) {
            $userAgent = Mage::helper('core/http')->getHttpUserAgent();
            $ignoreAgents = Mage::getConfig()->getNode('global/skip_user_agents');
            if ($ignoreAgents) {
                $ignoreAgents = $ignoreAgents->asArray();
                foreach($ignoreAgents as $ignoreAgent) {
                    if (stristr($userAgent, $ignoreAgent)) {
                        $this->_skipRequestLogging = true;
                        break;
                    }
                }
            }
        }
        
        return $rt;
    }
}
