<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * provab
 * @package		Provab - gpayment
 * @author		suresh babu<sureshbabu.provab@gmail.com>
 */
// require 'gpayments/vendor/autoload.php';
require __DIR__.'/../../gpayments/vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
class Gpayment {
	// const CA_CERTS_FILE_PATH = APP_ROOT_DIR."/gpayments/certs/cacerts.pem";
	const CA_CERTS_FILE_PATH = __DIR__.'/../../gpayments/certs/cacerts.pem';
    private $client;
    private $headers = [];
    private $merchantId= '123456789012345';
    private $transType=''; //prod
	private $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->client = new Client([
            'base_uri' => 'https://kapido-test.api.as1.gpayments.net',
            'cert' => [__DIR__.'/../../gpayments/cert-12cd135f-5965-4335-8526-8a566c39419d/crt.pem', ''],
            'ssl_key' => __DIR__.'/../../gpayments/cert-12cd135f-5965-4335-8526-8a566c39419d/key_no_pass.pem',
            "verify" => self::CA_CERTS_FILE_PATH,
        ]);
		
	}
	public function initAuth(){
		$resp = array('status'=>false);
		$post = $this->CI->input->post();
        $cardNumber = $post['accountID'];
        if($cardNumber){
            $request_uri = '/api/v2/auth/brw/init';
            if ($this->transType == "prod") {
                $request_uri = $request_uri . "?trans-type=prod";
            }
            $threeDSRequestorTransID = self::_getUUId();
            $post_data = array(
                'acctNumber'=>trim(str_replace(" ","",$cardNumber)),
                'merchantId'=>$this->merchantId,
                'threeDSRequestorTransID'=>$threeDSRequestorTransID,
                'eventCallbackUrl'=>base_url('index.php/gpayments/notifyResult')
            );
            $insert = array(
                'requestorTransId'=>$threeDSRequestorTransID,
                'init_auth_request'=>provab_encrypt(json_encode($post_data)),
                'init_auth_req_at'=>date('Y-m-d H:i:s')
            );
            if($this->CI->db->insert('gpayment_logs', $insert)){
                $lastInsertId = $this->CI->db->insert_id();
                $resp = $this->post($request_uri, $post_data);
                $update = array(
                    'init_auth_response'=>provab_encrypt(json_encode($resp)),
                    'serverTransId'=>isset($resp['threeDSServerTransID']) ? $resp['threeDSServerTransID'] : '',
                    'authUrl'=>isset($resp['authUrl']) ? $resp['authUrl'] : '',
                    'init_auth_resp_at'=>date('Y-m-d H:i:s')
                );
                $this->CI->db->update('gpayment_logs',$update, ['id'=>$lastInsertId]);
            }
        }
		return $resp;
    }
	public function auth(){
		$resp = array('status'=>false);
        $data = $this->CI->input->post();
        $this->CI->form_validation->set_rules('reqTransId', 'reqTransId', 'required');
        $this->CI->form_validation->set_rules('acctNumber', 'acctNumber', 'required');
        $this->CI->form_validation->set_rules('amount', 'amount', 'required');
        $this->CI->form_validation->set_rules('expYear', 'expYear', 'required');
        $this->CI->form_validation->set_rules('expMonth', 'expMonth', 'required');
        $this->CI->form_validation->set_rules('ownrName', 'ownrName', 'required');
        if ($this->CI->form_validation->run() == TRUE) {
            $requestorTransId = $data['reqTransId'];
            $rec = $this->CI->db->get_where('gpayment_logs',['requestorTransId'=>$requestorTransId])->row_array();
            if($rec){
               // debug($rec);
               $currency_code = $this->CI->db->select('code')->get_where('gpayment_currency',['currency'=>get_application_currency_preference()])->row('code');
				$acctNumber = trim(str_replace(" ","",$data['acctNumber']));
                $amount = (number_format($data['amount'], 2, '.', '') * 100) + 0;
				$post_data = [
					"merchantId"=>$this->merchantId,
					"authenticationInd"=>"01",
					"purchaseDate"=>date('YmdHis'),
					"purchaseAmount"=> $amount,
					"acctNumber"=>$acctNumber,
					"cardExpiryDate"=>(string) substr($data['expYear'], -2).sprintf('%02d', $data['expMonth']),
					"purchaseCurrency"=> $currency_code ? (string) $currency_code : '124', // "036",
					"cardHolderInfo"=>[
						// "addrMatch"=>"Y",
						// "billAddrLine1"=>"Fortune Serene",
						// "billAddrLine2"=>"Electronic City",
						// "billAddrLine3"=>"Phase 1",
						// "billAddrCity"=>"Sydney",
						// "billAddrState"=>"NSW",
						// "billAddrCountry"=>"036",
						// "billAddrPostCode"=>"2000",
						// "shipAddrLine1"=>"Fortune Serene",
						// "shipAddrLine2"=>"Electronic City",
						// "shipAddrLine3"=>"Phase 1",
						// "shipAddrCity"=>"Sydney",
						// "shipAddrState"=>"NSW",
						// "shipAddrCountry"=>"036",
						// "shipAddrPostCode"=>"2000",
						"cardholderName"=>$data['ownrName']
					],
					"messageCategory"=>"pa",
					"browserInfo"=>$rec['param'],
					"threeDSRequestorTransID"=>$rec['requestorTransId'],
					"threeDSServerTransID"=>$rec['serverTransId']
				];
				// debug($post_data);
				$update = array(
					'auth_request'=>base64_encode(json_encode($post_data)),
					'auth_req_at'=>date('Y-m-d H:i:s')
				);
				$this->CI->db->update('gpayment_logs', $update, ['id'=>$rec['id']]);
				$resp = $this->post($rec['authUrl'], $post_data);
				$update = array(
					'auth_response'=>base64_encode(json_encode($resp)),
					'auth_resp_at'=>date('Y-m-d H:i:s')
				);
				$this->CI->db->update('gpayment_logs', $update, ['id'=>$rec['id']]);
				// debug($resp);
				if(isset($resp['transStatusReason'])){
					$resp['Status'] = self::get_transStatusReason($resp['transStatusReason']);
				}
				if(isset($resp['transStatus']) && $resp['transStatus'] == 'Y'){
					$resp['status'] = 'OK';
					$resp['msg'] = 'Transaction verified by GPayments.';
				}else if(isset($resp['transStatus']) && $resp['transStatus'] == 'C'){
					// $resp['status'] = 'OK';
					// $resp['msg'] = '<div class="alert alert-success"><strong>Success!</strong> Transaction verified by GPayments.</div>';
				}else if(isset($resp['transStatus']) && in_array($resp['transStatus'],['R','N','U','A'])){
					$resp['error'] = !empty($resp['Status']) ? $resp['Status']:'Invalid Payment Details!';
				}

                $resp['amount'] = $amount;
            }else{
				//
			}
            
        } else {
            //
        }
		return $resp;
    }
	public function getThreeRIResult($serverTransId){
		$resp = array('status'=>false);
        //ActiveServer url for Retrieve Results
        $resultUrl = "/api/v2/auth/3ri/result?threeDSServerTransID=" . $serverTransId;
        $resp = $this->get($resultUrl);
        if(isset($resp['transStatusReason'])){
            $resp['Status'] = self::get_transStatusReason($resp['transStatusReason']);
        }
        if(isset($resp['transStatus']) && $resp['transStatus'] == 'Y'){
            $resp['status'] = 'OK';
            $resp['msg'] = 'Transaction verified by GPayments.';
        }else if(isset($resp['transStatus']) && $resp['transStatus'] == 'C'){
            // $resp['status'] = 'OK';
            // $resp['msg'] = '<div class="alert alert-success"><strong>Success!</strong> Transaction verified by GPayments.</div>';
        }else if(isset($resp['transStatus']) && in_array($resp['transStatus'],['R','N','U','A'])){
            $resp['error'] = !empty($resp['Status']) ? $resp['Status']:'Invalid Payment Details!';
        }
        return $resp;
    }
	public function notifyResult(){
        file_put_contents(FCPATH ."logs/gpay_logs_". date("Y-m-d") .".txt", date("Y-m-d H:i:s") . json_encode(['post'=>$_POST,'get'=>$_GET]) . PHP_EOL, FILE_APPEND);
        $post = $this->CI->input->post();
        $requestorTransId = $post["requestorTransId"];
        $callbackType = $post["event"];
		switch($callbackType){
			case '3DSMethodFinished':
			case '3DSMethodSkipped':
			case 'InitAuthTimedOut':
				$callbackName = "gpaymentAuth";
				break;
			case 'AuthResultReady':
				$callbackName = "_onAuthResult";
				break;
			default:
				$callbackName = "_NA";
		}
        $update = array(
            'callback_response'=>provab_encrypt(json_encode($post)),
            'event'=>isset($post['event']) ? $post['event'] : '',
            'param'=>isset($post['param']) ? $post['param'] : '',
            'callback_resp_at'=>date('Y-m-d H:i:s')
        );
        $this->CI->db->update('gpayment_logs',$update, ['requestorTransId'=>$requestorTransId]);
		return ['callbackFn'=>$callbackName,'reqTransId'=>$requestorTransId];
    }
	private function post($request_uri, $post_data){
        try {
            $response = $this->client->request(
                "POST",
                $request_uri,
                ['json' => $post_data, 'headers' => $this->headers]);
            // debug($response->getStatusCode());
            $resp = json_decode($response->getBody(), true);
            return $resp;

        } catch (BadResponseException $e) {
            $resp = self::_returnJson($e->getResponse()->getBody(), $e->getCode());
            return $resp;
        } catch (GuzzleException $e) {
            $resp = self::_returnJson($e->getMessage(), 500);
            return $resp;
        }
    }
    private function get($request_uri){
        try {
            $response = $this->client->request("GET", $request_uri, ['headers' => $this->headers]);
            $resp = json_decode($response->getBody(), true);
            return $resp;
        } catch (BadResponseException $e) {
            $resp = self::_returnJson($e->getResponse()->getBody(), $e->getCode());
            return $resp;
        } catch (GuzzleException $e) {
            $resp = self::_returnJson($e->getMessage(), 500);
            return $resp;
        }
    }
	public static function _getUUId(){
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
	public static function _returnJson($response, $statusCode = 200)
    {
        header('Content-Type: application/json;charset=utf-8', true, $statusCode);
        return json_decode($response);
    }
	private static function get_transStatusReason($status_code){
        $status_codes = array(
            "01" => "Card authentication failed",
            "02" => "Unknown Device",
            "03" => "Unsupported Device",
            "04" => "Exceeds authentication frequency limit",
            "05" => "Expired card",
            "06" => "Invalid card number",
            "07" => "Invalid transaction",
            "08" => "No Card record",
            "09" => "Security failure",
            "10" => "Stolen card",
            "11" => "Suspected fraud",
            "12" => "Transaction not permitted to cardholder",
            "13" => "Cardholder not enrolled in service",
            "14" => "Transaction timed out at the ACS",
            "15" => "Low confidence",
            "16" => "Medium confidence",
            "17" => "High confidence",
            "18" => "Very High confidence",
            "19" => "Exceeds ACS maximum challenges",
            "20" => "Non-Payment transaction not supported",
            "21" => "3RI transaction not supported",
            "22" => "ACS technical issue [From V2.2.0]",
            "23" => "Decoupled Authentication required by ACS but not requested by 3DS Requestor [From V2.2.0]",
            "24" => "3DS Requestor Decoupled Max Expiry Time exceeded [From V2.2.0]",
            "25" => "Decoupled Authentication was provided insufficient time to authenticate cardholder. ACS will not make attempt [From V2.2.0]",
            "26" => "Authentication attempted but not performed by the cardholder [From V2.2.0]"
        );
        return isset($status_codes[$status_code]) ? $status_codes[$status_code] : '';
    }
}