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
namespace Ced\Jne\Model\Config\Backend;

use Magento\Framework\Model\AbstractModel;

/**
 * Backend model for shipping table rates CSV importing
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Jne extends \Magento\Framework\App\Config\Value
{

    protected $_jneFactory;

	
    
    
    public function __construct(
    		\Magento\Framework\Model\Context $context,
    		\Magento\Framework\Registry $registry,
    		\Magento\Framework\App\Config\ScopeConfigInterface $config,
    		\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
    		\Ced\Jne\Model\ResourceModel\Carrier\JneFactory $jneFactory,
    		\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
    		\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
    		array $data = []
    ) {
    	$this->_jneFactory = $jneFactory;
    	parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel|void
     */
    public function afterSave()
    {
       
        $tableRate = $this->_jneFactory->create();
        $tableRate->uploadAndImport($this);
        return parent::afterSave();

        
    }
}
