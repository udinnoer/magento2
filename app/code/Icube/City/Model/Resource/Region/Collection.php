<?php
 
namespace Icube\City\Model\Resource\Region;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Icube\City\Model\Region',
            'Icube\City\Model\Resource\Region'
        );
    }
}