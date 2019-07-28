<?php
 namespace Icube\City\Controller\Ajax;

 class Billinginfo extends \Magento\Framework\App\Action\Action
 {
    
     /**
      * Is the request a Javascript XMLHttpRequest?
      *
      * Should work with Prototype/Script.aculo.us, possibly others.
      *
      * @return boolean
      */
     public function isXmlHttpRequest()
     {
         return ($this->getRequest()->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
     }
 
     /**
      * Check is Request from AJAX
      *
      * @return boolean
      */
     public function isAjax()
     {
         if ($this->isXmlHttpRequest()) {
             return true;
         }
 
         return false;
     }
 
     /**
      * Billing AJAX
      *
      * @return boolean
      */
     public function execute()
     {
         /* activate this when it's live */
         /*if (!$this->isAjax()) {
             return;
         }*/
 
         $response = array();
 
         try {
             $valueSent = $this->getRequest()->getParam('valueSent');
 
            if ($valueSent) {
                 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $obj = $objectManager->get('Icube\City\Cookie\Cookie');
                 if($valueSent == 'unchecked') {
                     $obj->delete();
                     $response['status'] = 'Different billing address selected!';
                 } else if ($valueSent == 'checked') {
                     $id = $obj->get();
                     $kecamatan = $obj->getkec($id);
                     $setkecamatan = $obj->set($kecamatan);
                     $response['status'] = 'Same billing address selected!';
                 }

             } else {
                 $response['status'] = 'error';
                 $response['message'] = 'Unable to call AJAX ';
             }
         } catch (\Exception $e) {
             $msg = "";
             if ($this->_getSession()->getUseNotice(true)) {
                 $msg = $e->getMessage();
             } else {
                 $messages = array_unique(explode("\n", $e->getMessage()));
                 foreach ($messages as $message) {
                     $msg .= $message.'<br/>';
                 }
             }
 
             $response['status'] = 'error';
             $response['message'] = $msg;
         }
 
 
         $this->getResponse()->representJson(
             $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
         );
     }
 }