<?php
namespace Icube\City\Controller\Ajax;

class Kecamatanlist extends \Magento\Framework\App\Action\Action
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
            $city = $this->getRequest()->getParam('city');
            $kecamatanList = "<option value=''>Pilih Kecamatan</option>";

            if ($city) {

                $kecamatanarray =  $this->_objectManager->create('Icube\City\Helper\Data')->Getkecamatan($city);
                foreach ($kecamatanarray as $kecamatan) {
                    $kecamatanList .= "<option value='".$kecamatan['kecamatan']."'>" . $kecamatan['kecamatan'] . "</option>";

                }

                if(count($kecamatanarray) > 0) {
                    $response['status'] = 'success';
                } else {
                    $response['status'] = 'error';
                }

                $response['message'] = $kecamatanList;
            } else {
                $response['status'] = 'error';
                $response['message'] = $kecamatanList;
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