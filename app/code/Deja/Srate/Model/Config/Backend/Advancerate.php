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
  * @category    Deja
  * @package     Deja_Srate
  * @author       CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright   Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license      http://cedcommerce.com/license-agreement.txt
  */
namespace Deja\Srate\Model\Config\Backend;

use Magento\Framework\Model\AbstractModel;

/**
 * Backend model for shipping table rates CSV importing
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Srate extends \Magento\Framework\App\Config\Value
{

    protected $_srateFactory;

	
    
    
    public function __construct(
    		\Magento\Framework\Model\Context $context,
    		\Magento\Framework\Registry $registry,
    		\Magento\Framework\App\Config\ScopeConfigInterface $config,
    		\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
    		\Deja\Srate\Model\ResourceModel\Carrier\SrateFactory $srateFactory,
    		\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
    		\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
    		array $data = []
    ) {
    	$this->_srateFactory = $srateFactory;
    	parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel|void
     */
    public function afterSave()
    {
       
        $tableRate = $this->_srateFactory->create();
        $tableRate->uploadAndImport($this);
        return parent::afterSave();

        
    }
}
