<?php
                
namespace Zeo\OrderDelete\Helper;
        
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public function isDisable()
    {
        return $module_status = (boolean) $this->_scopeConfig->getValue('advanced/modules_disable_output/Zeo_OrderDelete');
    }
}
