<?php
 
namespace Icube\City\Model\Resource;
 
use Magento\Framework\Model\ResourceModel\Db\AbstractDb; 
class City extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('city', 'region_code');
    }
}