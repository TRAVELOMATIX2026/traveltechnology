<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - gpayments
 * @author     suresh babu<sureshbabu.provab@gmail.com>
 */

class Gpayments extends CI_Controller {
	public function __construct()
	{
        parent::__construct();
		$this->load->library('gpayment');
	}
    public function initAuth(){
        $resp = $this->gpayment->initAuth();
        echo json_encode($resp);
    }
    public function auth(){
        $resp = $this->gpayment->auth();
        echo json_encode($resp);
    }
    public function getThreeRIResult($serverTransId){
        $resp = $this->gpayment->getThreeRIResult($serverTransId);
        echo json_encode($resp);
    }
     /**
     * Handles the notification event from the iframe.
     * threeDSServerCallbackURL or the challengeUrl.
     */
    public function notifyResult()
    {
        $resp = $this->gpayment->notifyResult();
        echo $this->template->isolated_view('share/loader/gpayments_loader',['callbackFn'=>$resp['callbackFn'],'reqTransId'=>$resp['reqTransId']]);
    }
}