<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Provab_Mailer
{

	public $CI;                     //instance of codeigniter super object
	public $mailer_status;         //mailer status which indicates if the mail should be sent or not
	public $mail_configuration;    //mail configurations defined by user

	/**
	 * Constructor - Loads configurations and get ci super object reference
	 */
	public function __construct($data = '')
	{

		$this->mail_configuration = $this->decrypt_email_config_data();
		// error_reporting(0);
		// error_reporting(E_ALL);
	}

	/**
	 *Initialize mailer to send mail
	 *
	 *return array containing the status of initialization
	 */
	public function initialize_mailer()
	{
		
		$status = false;
		$message = 'Please Contact Admin To Setup Mail Configuration';
		$this->mailer_status = false;
		//$this->mail_configuration['host_type'] = 'ssl';
		// debug($this->mail_configuration);exit;
		if (is_array($this->mail_configuration) == true and count($this->mail_configuration) > 0) {
			if (intval($this->mail_configuration['status']) == 1 ) {
				if (isset($this->mail_configuration['username']) == true and empty($this->mail_configuration['username']) == false and
				isset($this->mail_configuration['password']) == true and empty($this->mail_configuration['password']) == false
				) {
					$config['useragent'] = 'PHPMailer';
					
					$config['smtp_user'] = $this->mail_configuration['username'];
					$config['smtp_pass'] = $this->mail_configuration['password']; // khaoogebqetlknpi
					$config['smtp_port'] = $this->mail_configuration['port'];
					$config['smtp_host'] = $this->mail_configuration['host'];
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					$config['charset'] = 'iso-8859-1';
					// $config['charset'] = 'utf-8';
					$config['crlf'] = "\n";
					// $config['starttls'] = true;
					$config['newline'] = "\r\n";
					// $config['protocol'] = 'gsmtp';
					$config['protocol'] = 'smtp';

					/* $config = array(
						'useragent'  => 'PHPMailer',
						'protocol'   => 'smtp',
						'smtp_host'  => 'smtp.gmail.com',         // ✅ Remove "ssl://"
						'smtp_port'  => 465,                      // ✅ OK for SSL
						'smtp_user'  => 'noreplyinfo@starlegends.com.ph',
						'smtp_pass'  => 'kcxolumhyraepkkh',       // ✅ This must be an App Password
						'smtp_crypto'=> 'ssl',                    // ✅ REQUIRED for port 465
						'mailtype'   => 'html',
						'charset'    => 'utf-8',
						'wordwrap'   => TRUE,
						'newline'    => "\r\n",                   // ✅ Required
						'crlf'       => "\r\n"                    // ✅ Required
					); */


					$this->CI->load->library('email', $config);
					$this->CI->load->helper(array('custom/app_helper'));
					$from = isset($this->CI->entity_domain_name) == true ? $this->CI->entity_domain_name: $this->mail_configuration['from'];
					$this->CI->email->from($this->mail_configuration['username'], $from);
					$this->CI->email->set_newline("\r\n");
					$this->CI->email->set_crlf("\n");

					//set cc and bcc
					if (isset($this->mail_configuration['bcc']) == true and empty($this->mail_configuration['bcc']) == false) {
						$this->CI->email->bcc($this->mail_configuration['bcc']);
					}
					if (isset($this->mail_configuration['cc']) == true and empty($this->mail_configuration['cc']) == false) {
						$this->CI->email->cc($this->mail_configuration['cc']);
					}
					$this->mailer_status = true;
					
					$status = true;
					$message = 'Continue To Send Mail';
				}
			}
		}
		return array('status' => $status, 'message' => $message);
	}
	/**
	 *send mail to the user
	 *
	 *@param string $to_email     email id to which the mail has to be delivered
	 *@param string $mail_subject mail subject which has to be sent in the mail
	 *@param string $mail_message mail message which has to be sent in the mail body
	 *@return boolean status of sending mail
	 *@$attachment for single attachment pass file name , for multiple pass as array of filenames
	 *$cc and $bcc pass as array
	 */

	 public function send_mail($to_email, $mail_subject, $mail_message, $attachment='', $cc='', $bcc='')
	{
		//$attachment='';
			// debug($to_email);
			// debug($mail_subject);
			// debug($mail_message);
			// debug($attachment);
			// debug($cc);
			// debug($bcc); die;

		//echo "exxitt";exit;
		//$mail_subject = 'Ticket';
		// $mail_subject = base64_encode($mail_subject);
		// $mail_subject = 'Booking Confirmation for Hebron  Bagdogra Oneway flight  Booking Reservation Code  Reservation code';
		// $mail_subject = '=?UTF-8?B?'.base64_encode($mail_subject).'?=';
		$status = false;
		$ini_status = $this->initialize_mailer();
		$message = $ini_status['message'];
		// debug($to_email);         
        // debug($mail_subject);
        // debug($mail_message);
        // exit;
        
		//sending mail based on mailer status
		if ($this->mailer_status == true) {

			if ($to_email != '' && $mail_message != '' && $mail_subject != '') {
				//set mail data
				//echo "hereee"; exit;
				// debug($to_email); die;
				$this->CI->email->to(trim(strip_tags($to_email)));
				$this->CI->email->subject(trim($mail_subject));
				$this->CI->email->message($mail_message);
				//and attachment
				if (empty($attachment) == false) {
					if(valid_array($attachment)) {
						//for multple attachements
						foreach($attachment as $k => $v) {
							if(empty($v) == false) {
								$this->CI->email->attach($v);
							}
						}
					} else if(strlen($attachment) > 1){
						//for single attachements
						$this->CI->email->attach($attachment);
					}
				}
				//add CC
				if(empty($cc) == false && valid_array($cc)) {
					// debug($cc);  
					$ccEmail = '';
					//Validating Email
					foreach($cc as $k => $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
							$ccEmail[] = trim($v);							
						}
					}
					if(valid_array($ccEmail)) {
						$this->CI->email->cc($ccEmail);
					}					
				}				
			    //add BCC
				if(empty($bcc) == false && valid_array($bcc)) {
						// debug($bcc);  
				    $bccEmail = '';
					//Validating Email
					foreach($bcc as $k => $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
							$bccEmail[] = trim($v);							
						}
					}
					if(valid_array($bccEmail)) {
						$this->CI->email->bcc($bccEmail);
					}
				}
				$result = $this->CI->email->send();
				// debug($result);
				echo "<pre>test result";print_r($result);
				debug($this->CI->email->print_debugger());die;
				
				if($result) {
					$status = true;
					$message = 'Mail Sent Successfully'; 
					// debug($status);
					// exit;
					
				} else {
					$status = false;
					$message = $this->CI->email->print_debugger(); 
					debug($message);
					debug($status);
					exit;
				}

			} else {
				$status = false;
				$message = 'Please Provide To Email Address, Mail Subject And Mail Message';
				// debug($status);
				// 	exit;
			}
		}
		return array('status' => $status, 'message' => $message);
	}


	public function insert_log($data)
	{
		$this->CI = &get_instance();

		$sql = "INSERT INTO email_logs (from_email, to_email, subject, error) VALUES (?, ?, ?, ?)";

		$values = array(
			$data['from_email'],
			$data['to_email'],
			$data['subject'],
			isset($data['error']) ? $data['error'] : null // Handle case if 'error' is not provided
		);

		$this->CI->db->query($sql, $values);

		return $this->CI->db->affected_rows() > 0;
	}


	public function decrypt_email_config_data()
	{
		$this->CI = &get_instance();
		$id = '1';
		$secret_iv = PROVAB_SECRET_IV;
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$email_config_data = $this->CI->custom_db->single_table_records('email_configuration', '*', array('origin' => $id));
		if ($email_config_data['status'] == true) {
			foreach ($email_config_data['data'] as $data) {

				if (!empty($data['username'])) {
					$md5_key = PROVAB_MD5_SECRET;
					$encrypt_key = PROVAB_ENC_KEY;
					$decrypt_password = $this->CI->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('" . $md5_key . "',512)) AS decrypt_data");
					$db_data = $decrypt_password->row();
					$secret_key = trim($db_data->decrypt_data);
					$key = hash('sha256', $secret_key);
					$iv = substr(hash('sha256', $secret_iv), 0, 16);
					$username = openssl_decrypt(base64_decode($data['username']), $encrypt_method, $key, 0, $iv);
					$password = openssl_decrypt(base64_decode($data['password']), $encrypt_method, $key, 0, $iv);
					$cc = openssl_decrypt(base64_decode($data['cc']), $encrypt_method, $key, 0, $iv);
					$bcc = openssl_decrypt(base64_decode($data['bcc']), $encrypt_method, $key, 0, $iv);
					$host = openssl_decrypt(base64_decode($data['host']), $encrypt_method, $key, 0, $iv);
					$port = openssl_decrypt(base64_decode($data['port']), $encrypt_method, $key, 0, $iv);

					$mail_configuration['domain_origin'] = $data['domain_origin'];
					$mail_configuration['username'] = $username;
					$mail_configuration['password'] = $password;
					$mail_configuration['cc'] = $cc;
					$mail_configuration['from'] = $data['from'];
					$mail_configuration['bcc'] = $bcc;
					$mail_configuration['port'] = $port;
					$mail_configuration['host'] = $host;
					$mail_configuration['status'] = $data['status'];
					//debug($mail_configuration);exit;
					return $mail_configuration;
				}
			}
		}
	}
}
