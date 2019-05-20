<?php
 
namespace Icube\City\Model;
 
use Magento\Framework\Model\AbstractModel;
 
class Region extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Icube\City\Model\Resource\Region');
    }
}