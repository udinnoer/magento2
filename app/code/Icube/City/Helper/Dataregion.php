<?php 
namespace Icube\City\Helper;

use Magento\Framework\App\Helper\Context;
//use Magento\Framework\App\Action\Context;
use Icube\City\Model\RegionFactory; 
class Dataregion extends \Magento\Framework\App\Helper\AbstractHelper
{

	protected $_modelRegionFactory;
 
    /**
     * @param Context $context
     * @param NewsFactory $modelNewsFactory
     */
    public function __construct(
        Context $context,
        RegionFactory $modelRegionFactory
    ) {
        parent::__construct($context);
        $this->_modelRegionFactory = $modelRegionFactory;
    } 

    public function Getregion($country)
    {
        $regionModel = $this->_modelRegionFactory->create();
 
        // Load the item with ID is 1
        $regionlist = $regionModel->getCollection()
        			->addFieldToFilter('country_id',$country)
        			->setOrder('region_id','ASC');
       return $regionlist->getData();
    }

    public function GetregionById($regionId)
    {
        $regionModel = $this->_modelRegionFactory->create();

        // Load the item with ID is 1
        $region = $regionModel->getCollection()
            ->addFieldToFilter('region_id',$regionId)
            ->getFirstItem();
        return $region->getData();
    }
}