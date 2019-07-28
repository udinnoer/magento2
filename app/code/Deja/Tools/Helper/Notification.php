<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Deja\Tools\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Notification extends AbstractHelper
{
    protected $orderinterface;
    protected $_productloader;

    public function __construct(
        \Magento\Sales\Api\Data\OrderInterface $orderinterface,
         \Magento\Catalog\Model\ProductFactory $_productloader
    ) {
        $this->orderinterface = $orderinterface;
        $this->_productloader = $_productloader;
    }

    public function teleOrder($productid) {
    	//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		//$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productid);
		$product = $this->_productloader->create()->load($productid);
		$return = $product->getName()."</br>".$product->getDescription();
		return $return;
        //return $productid;
    }    

}