<?php
/**
 * CedCommerce
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the End User License Agreement (EULA)
  * that is bundled with this package in the file LICENSE.txt.
  * It is also available through the world-wide-web at this URL:
  * http://cedcommerce.com/license-agreement.txt
  *
  * @category    Ced
  * @package     Ced_Jne
  * @author       CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright   Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license      http://cedcommerce.com/license-agreement.txt
  */
namespace Ced\Jne\Model\Carrier;

use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Jne extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'jne';

    protected $_isFixed = true;

    protected $_defaultConditionName = 'package_weight';

    protected $_conditionNames = [];

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * @var \Ced\Jne\Model\Resource\Carrier\JneFactory
     */
    protected $_tablerateFactory;


    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Ced\Jne\Model\ResourceModel\Carrier\JneFactory $tablerateFactory,
        array $data = []
    ) {
    	$this->_scopeConfig = $scopeConfig;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_tablerateFactory = $tablerateFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

     }

    /**
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result
     */
    public function collectRates(RateRequest $request)
    {
    	//echo "masuk";die();
        //udin tambahkan untuk restriction shipping get list category yg di disable
        //$listcategory = '["4","5"]';
        $listcategory = $this->_scopeConfig->getValue('carriers/jne/notcategory', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $arrCategory = json_decode($listcategory,true);
        $shippingenable = true;
        //end udin
        $oldValue = $request->getPackageValue();
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();
        $freeQty = 0;
        // exclude Virtual products price from Package value if pre-configured
        if (!$this->getConfigFlag('use_virtual_product') && $request->getAllItems()) {
        	
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
				
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                	
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual()) {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->getTypeId() == 'virtual') {
                	
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }
		 // exclude Downloadable products price from Package value if pre-configured
        if (!$this->getConfigFlag('use_download_product') && $request->getAllItems()) {
        	
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                //udin tambahkan untuk restriction shipping by category
                if ($item->getProduct()->getCategoryIds() and count($arrCategory)>0){
                    foreach ($item->getProduct()->getCategoryIds() as $categoriId) {
                        if (in_array($categoriId, $arrCategory)) {
                            $shippingenable = false;
                        }
                    }
                }
                //end udin
                
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                    	
                    	//return($child->getProduct());die;
                    	
                    	
                        if ($child->getProduct()->getTypeId()=='downloadable') {
                        	
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->getTypeId()=='downloadable') {
                	
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
           
        }

        // Free shipping by qty
         $freeQty = 0;
        if ($request->getAllItems()) {
            $freePackageValue = 0;
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                            $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeShipping = is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0;
                    $freeQty += $item->getQty() - $freeShipping;
                    $freePackageValue += $item->getBaseRowTotal();
                }
            }
            
            $oldValue = $request->getPackageValue();
           
            $request->setPackageValue($oldValue - $freePackageValue);
        }
       
        
        $result = $this->_rateResultFactory->create();
       
       // print_r($rates);echo "admin";
        //udin: implementasi restriction cek di cek rates
        if ($shippingenable){
            $rates = $this->getdefaultRate($request);
        }else{
            $rates = array();
        }
       // print_r($rates);echo "admin";
        if ($shippingenable && 
            $this->_scopeConfig->getValue('carriers/jne/free_shipping', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) && 
        		($request->getFreeShipping() === true || 
        		($request->getPackageValue() >= $this->_scopeConfig->getValue('carriers/jne/min_freeshipping_amount', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) && 
        		$request->getPackageWeight() <= $this->_scopeConfig->getValue('carriers/jne/max_freeshipping_weight', \Magento\Store\Model\ScopeInterface::SCOPE_STORE))))
          {
        	
        	$method = $this->_rateMethodFactory->create();
        	$method->setCarrier($this->_code);
        	$method->setCarrierTitle("Jne Rate");
        	$method->setMethod('rate_free');
        	$method->setMethodTitle('Free Shipping');
        	$method->setPrice('0.00');
        	$method->setCost('0.00');
            $method->setMethodDescription('#NA');
        	$result->append($method);
        	
        }
        
        
        if (!empty($rates)) {
        	$count=0;
        	foreach ($rates as $rate)
        	{
        		if (!empty($rate) && $rate['price'] >= 0) {
        			$method = $this->_rateMethodFactory->create();
        
        			$method->setCarrier($this->_code);
        			$method->setCarrierTitle($this->getConfigData('title'));
        			$method->setMethod('jne'.$count++);
        			$method->setMethodTitle($this->getConfigData('name'));
        			$method->setCost($rate['price']);
        			$method->setPrice($rate['price']);
                    $method->setMethodDescription($rate['etd']);
        			$result->append($method);
        		}
        	}
        		
        }        
        else {
        	/** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
        	$error = $this->_rateErrorFactory->create(
        			[
        			'data' => [
        			'carrier' => $this->_code,
        			'carrier_title' => $this->getConfigData('title'),
        			'error_message' => $this->getConfigData('specificerrmsg'),
        			],
        			]
        	);
        	$result->append($error);
        }
        
     
        return $result;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @return array|bool
     */
    public function getdefaultRate(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        return $this->_tablerateFactory->create()->getRates($request);
    }

    /**
     * @param string $type
     * @param string $code
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    /* public function getCode($type, $code = '')
    {
        $codes = [
            'condition_name' => [
                'package_weight' => __('Weight vs. Destination'),
                'package_value' => __('Price vs. Destination'),
                'package_qty' => __('# of Items vs. Destination'),
            ],
            'condition_name_short' => [
                'package_weight' => __('Weight (and above)'),
                'package_value' => __('Order Subtotal (and above)'),
                'package_qty' => __('# of Items (and above)'),
            ],
        ];

        if (!isset($codes[$type])) {
            throw new LocalizedException(__('Please correct Table Rate code type: %1.', $type));
        }

        if ('' === $code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw new LocalizedException(__('Please correct Table Rate code for type %1: %2.', $type, $code));
        }

        return $codes[$type][$code];
    } */

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
      return [$this->_code=> $this->getConfigData('name')];
    }
}
