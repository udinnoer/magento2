<?php 
namespace Icube\City\Helper;

use Magento\Framework\App\Helper\Context;
//use Magento\Framework\App\Action\Context;
use Icube\City\Model\CityFactory; 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	protected $_modelCityFactory;
 
    /**
     * @param Context $context
     * @param NewsFactory $modelNewsFactory
     */
    public function __construct(
        Context $context,
        CityFactory $modelCityFactory
    ) {
        parent::__construct($context);
        $this->_modelCityFactory = $modelCityFactory;
    } 

    public function Getcity($region)
    {
        $cityModel = $this->_modelCityFactory->create();

        // Load the item with ID is 1
        $citylist = $cityModel->getCollection()
                    ->addFieldToFilter('region_code',$region)
                    ->setOrder('city','ASC');
        $citylist->getSelect()->group('city');
        
        return $citylist->getData();
    }

    public function Getkecamatan($city=null)
    {

        $cityModel = $this->_modelCityFactory->create();
 
        if($city){
            // Load kecamatan by City
            $kecamatanlist = $cityModel->getCollection()
                        ->addFieldToFilter('city',$city)
                        ->setOrder('kecamatan','ASC');

        }else{
            // Load all kecamatan
            $kecamatanlist = $cityModel->getCollection()
                        // ->addFieldToFilter('city',$city)
                        ->setOrder('kecamatan','ASC');
            
        }

        return $kecamatanlist->getData();
    }

}