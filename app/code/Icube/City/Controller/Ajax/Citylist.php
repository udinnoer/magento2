<?php
namespace Icube\City\Controller\Ajax;

class Citylist extends \Magento\Framework\App\Action\Action
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
     * City AJAX
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
            $region = $this->getRequest()->getParam('region');
            $selectedCity = urldecode($this->getRequest()->getParam('selectedCity'));
            $cityList = "<option value=''>Pilih Kota</option>";

            if ($region) {

                $region =  $this->_objectManager->create('Icube\City\Helper\Dataregion')->GetregionById($region);
                $statearray =  $this->_objectManager->create('Icube\City\Helper\Data')->Getcity($region["code"]);
                
                foreach ($statearray as $city) {
                    $citycurrent = $city['city']."/".$city['kecamatan'];
                    
                    if($selectedCity == $citycurrent) {
                        $cityList .= "<option selected value='".$city['city']."'>".$city['city']."</option>";
                    } else {
                        $cityList .= "<option value='".$city['city']."'>".$city['city']."</option>";
                    }
                    

                }
                
                if(count($statearray) > 0) {
                    $response['status'] = 'success';
                } else {
                    $response['status'] = 'error';
                }

                $response['message'] = $cityList;
            } else {
                $response['status'] = 'error';
                $response['message'] = '<option value="">Pilih Kota</option>';;
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
?>