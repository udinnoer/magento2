<?php
 
namespace Deja\Tools\Controller\Index;
 
use Magento\Framework\App\Action\Context;
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $_helper;
    protected $_order;
 
    public function __construct(Context $context, 
        \Deja\Tools\Helper\Notification $helper,
        \Magento\Sales\Api\Data\OrderInterface $order
    )
    {
        $this->_helper = $helper;
        $this->_order = $order;
        return parent::__construct($context);
    }
 
    public function execute()
    {   
        //$arrReturn= array("");
        $productid = $this->getRequest()->getParam('id');
        $item=$this->_helper->teleOrder($productid);
        echo $item;
    }



}