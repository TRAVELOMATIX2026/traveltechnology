<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Provab_Mailer - Updated for PHP 8.2 and PHPMailer 6+ (CodeIgniter 3)
 *
 * Drop-in replacement for the original Provab_Mailer class.
 * Supports Composer autoload if present, otherwise will attempt to load PHPMailer from
 * application/libraries/PHPMailer/src/*.php
 *
 * Usage: replace your existing application/libraries/Provab_Mailer.php with this file.
 */

// Attempt to include Composer autoload if available. If not, fall back to manual includes inside constructor.

require_once BASEPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require_once BASEPATH . 'libraries/PHPMailer/src/SMTP.php';
require_once BASEPATH . 'libraries/PHPMailer/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Provab_Mailer {

    public $CI;
    public $mailer_status = false;
    public $mail_configuration = [];

    public function __construct($data = '')
    {
        // Get CI instance
        $this->CI =& get_instance();

        // Try to load PHPMailer via Composer or local library later when needed

        // Load mail configuration (decrypts values from DB like original)
        try {
            $this->mail_configuration = $this->decrypt_email_config_data();
        } catch (Exception $e) {
            // Keep configuration empty on failure; initialization will handle this
            $this->mail_configuration = [];
        }
    }

    /**
     * Initialize mailer - sets up CI Email object if desired and validates config.
     * Returns ['status' => bool, 'message' => string]
     */
    public function initialize_mailer()
    {
        error_reporting(1);
        $status = false;
        $message = 'Please Contact Admin To Setup Mail Configuration';
        $this->mailer_status = false;
        //debug($this->mail_configuration);exit;
        if (is_array($this->mail_configuration) && count($this->mail_configuration) > 0) {
            if (isset($this->mail_configuration['status']) && intval($this->mail_configuration['status']) === 1) {
                if (!empty($this->mail_configuration['username']) && !empty($this->mail_configuration['password'])) {
                    // Configure CI Email (optional - keep for backward compatibility)
                    $config = [];
                    $config['useragent'] = 'PHPMailer';
                    $config['smtp_user'] = trim($this->mail_configuration['username']);
                    $config['smtp_pass'] = trim($this->mail_configuration['password']);
                    $config['smtp_port'] = isset($this->mail_configuration['port']) ? $this->mail_configuration['port'] : 465;
                    $config['smtp_host'] = isset($this->mail_configuration['host']) ? $this->mail_configuration['host'] : 'ssl://smtp.gmail.com';
                    $config['wordwrap'] = FALSE;
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'utf-8';
                    $config['crlf'] = "\r\n";
                    $config['newline'] = "\r\n";
                    $config['protocol'] = 'smtp';

                    //debug($config);
                    // Load CI Email (non-fatal)
                    if (isset($this->CI->load)) {
                        $this->CI->load->library('email', $config);
                        if (!empty($this->mail_configuration['from'])) {
                            $from = $this->mail_configuration['from'];
                        } else {
                            $from = defined('PROJECT_NAME') ? PROJECT_NAME : 'Application';
                        }
                        if (isset($this->CI->email)) {
                            $this->CI->email->from($this->mail_configuration['username'], $from);
                            $this->CI->email->set_newline("\r\n");
                        }
                    }

                    // Set CC/BCC defaults
                    $this->mailer_status = true;
                    $status = true;
                    $message = 'Continue To Send Mail';
                }
            }
        }

        return ['status' => $status, 'message' => $message];
    }

    /**
     * Send mail using PHPMailer 6+
     * @param string|array $to_email
     * @param string $mail_subject
     * @param string $mail_message
     * @param string|array $attachment
     * @param array $cc
     * @param array $bcc
     * @return array ['status' => bool, 'message' => string]
     */
    public function send_mail($to_email, $mail_subject, $mail_message, $attachment = '', $cc = [], $bcc = [])
    {
        // Ensure PHPMailer is available (try Composer first, then local files)
        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            // Try Composer autoload
            if (file_exists(APPPATH . 'vendor/autoload.php')) {
                require_once APPPATH . 'vendor/autoload.php';
            } elseif (file_exists(FCPATH . 'vendor/autoload.php')) {
                // in case vendor is in webroot
                require_once FCPATH . 'vendor/autoload.php';
            } else {
                // Fallback to manual inclusion from application/libraries/PHPMailer/src
                $src = APPPATH . 'libraries/PHPMailer/src/';
                if (is_dir($src)) {
                    require_once $src . 'Exception.php';
                    require_once $src . 'PHPMailer.php';
                    require_once $src . 'SMTP.php';
                } else {
                    return ['status' => false, 'message' => 'PHPMailer not found. Install via Composer (composer require phpmailer/phpmailer) or place PHPMailer in application/libraries/PHPMailer/src/'];
                }
            }
        }

        // Validate inputs
        if (empty($to_email) || empty($mail_subject) || empty($mail_message)) {
            return ['status' => false, 'message' => 'Please Provide To Email Address, Mail Subject And Mail Message'];
        }

        // Initialize config and mailer
        $ini_status = $this->initialize_mailer();
        $message = $ini_status['message'];

        if ($this->mailer_status != true) {
            return ['status' => false, 'message' => $message];
        }

        try {
            $mail = new PHPMailer(true);

            // SMTP settings
            $mail->isSMTP();

            // Choose encryption & host defaults
            $host = $this->mail_configuration['host'] ?? 'smtp.office365.com';
            $port = isset($this->mail_configuration['port']) ? intval($this->mail_configuration['port']) : 587;

            // Allow configurations such as "ssl://smtp.gmail.com"
            if (str_starts_with($host, 'ssl://')) {
                $mail->Host = substr($host, 6);
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->Host = $host;
                // Decide TLS vs SMTPS based on port
                if ($port === 465) {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // implicit SSL
                } else {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // STARTTLS
                }
            }

            $mail->SMTPAuth = true;
            $mail->Port = $port;
            $mail->Username = trim($this->mail_configuration['username']);
            $mail->Password = trim($this->mail_configuration['password']);

            // From
            $fromEmail = $this->mail_configuration['from'] ?? $mail->Username;
            $fromName = $this->mail_configuration['from_name'] ?? (defined('PROJECT_NAME') ? PROJECT_NAME : 'Application');
            $mail->setFrom($fromEmail, $fromName);

            // To Address(es)
            if (is_array($to_email)) {
                foreach ($to_email as $email) {
                    $email = trim($email);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mail->addAddress($email);
                    }
                }
            } else {
                $to_email = trim($to_email);
                if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                    $mail->addAddress($to_email);
                }
            }

            // Attachments
            if (!empty($attachment)) {
                if (is_array($attachment)) {
                    foreach ($attachment as $att) {
                        if (!empty($att) && file_exists($att)) {
                            $mail->addAttachment($att);
                        }
                    }
                } else {
                    if (file_exists($attachment)) {
                        $mail->addAttachment($attachment);
                    }
                }
            }

            // CC
            if (!empty($cc) && is_array($cc)) {
                foreach ($cc as $c) {
                    $c = trim($c);
                    if (filter_var($c, FILTER_VALIDATE_EMAIL)) {
                        $mail->addCC($c);
                    }
                }
            }

            // BCC
            if (!empty($bcc) && is_array($bcc)) {
                foreach ($bcc as $b) {
                    $b = trim($b);
                    if (filter_var($b, FILTER_VALIDATE_EMAIL)) {
                        $mail->addBCC($b);
                    }
                }
            }

            // Message
            $mail->Subject = trim($mail_subject);
            $mail->msgHTML($mail_message); // sets Body and AltBody automatically from HTML
            $mail->AltBody = strip_tags($mail_message);

            // Additional default options
            $mail->WordWrap = 500;

            // Send
            $mail->send();

            return ['status' => true, 'message' => 'Mail Sent Successfully'];

        } catch (Exception $e) {
            // If CI Email debugger exists, prefer that output
            $debug = '';
            if (isset($this->CI->email) && method_exists($this->CI->email, 'print_debugger')) {
                $debug = $this->CI->email->print_debugger([], true);
            }
            $err = $e->getMessage();
            $msg = 'Mailer Error: ' . $err . (!empty($debug) ? ' | CI Debug: ' . $debug : '');

            return ['status' => false, 'message' => $msg];
        }
    }

    /**
     * decrypt_email_config_data - Attempts to read encrypted email configuration from DB
     * This keeps the original behavior but wrapped with safer guards for PHP 8.
     */
    public function decrypt_email_config_data()
    {
        $this->CI =& get_instance();
        $id = '1';
        $secret_iv = defined('PROVAB_SECRET_IV') ? PROVAB_SECRET_IV : '';
        $encrypt_method = "AES-256-CBC";

        if (!isset($this->CI->custom_db) || !method_exists($this->CI->custom_db, 'single_table_records')) {
            return [];
        }

        $email_config_data = $this->CI->custom_db->single_table_records('email_configuration', '*', ['origin' => $id]);

        if (empty($email_config_data) || !isset($email_config_data['status']) || $email_config_data['status'] != true) {
        	
            return [];

        }

        foreach ($email_config_data['data'] as $data) {
            if (empty($data['username'])) continue;

            // Attempt to decrypt using the same DB-based key approach as before
            if (!defined('PROVAB_MD5_SECRET') || !defined('PROVAB_ENC_KEY')) {
                // If required constants are missing, attempt to return raw values
                return [
                    'domain_origin' => $data['domain_origin'] ?? '',
                    'username' => $data['username'] ?? '',
                    'password' => $data['password'] ?? '',
                    'cc' => $data['cc'] ?? '',
                    'from' => $data['from'] ?? '',
                    'bcc' => $data['bcc'] ?? '',
                    'port' => $data['port'] ?? '',
                    'host' => $data['host'] ?? '',
                    'status' => $data['status'] ?? 0,
                ];
            }

            $md5_key = PROVAB_MD5_SECRET;
            $encrypt_key = PROVAB_ENC_KEY;

            // The original code relied on a DB query AES_DECRYPT; keep that logic but guard it
            $decrypt_password = $this->CI->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('" . $this->CI->db->escape_str($md5_key) . "',512)) AS decrypt_data");
            $db_data = $decrypt_password->row();
            $secret_key = isset($db_data->decrypt_data) ? trim($db_data->decrypt_data) : '';
            if (empty($secret_key)) {
                return [];
            }

            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            // Helper to safely decrypt if value is base64 encoded
            $safe_decrypt = function ($val) use ($encrypt_method, $key, $iv) {
                if (empty($val)) return '';
                $decoded = base64_decode($val);
                if ($decoded === false) return $val;
                $res = openssl_decrypt($decoded, $encrypt_method, $key, 0, $iv);
                return $res === false ? $val : $res;
            };

            $username = $safe_decrypt($data['username'] ?? '');
            $password = $safe_decrypt($data['password'] ?? '');
            $cc = $safe_decrypt($data['cc'] ?? '');
            $bcc = $safe_decrypt($data['bcc'] ?? '');
            $host = $safe_decrypt($data['host'] ?? '');
            $port = $safe_decrypt($data['port'] ?? '');

            $mail_configuration = [];
            $mail_configuration['domain_origin'] = $data['domain_origin'] ?? '';
            $mail_configuration['username'] = $username;
            $mail_configuration['password'] = $password;
            $mail_configuration['cc'] = $cc;
            $mail_configuration['from'] = $data['from'] ?? $username;
            $mail_configuration['bcc'] = $bcc;
            $mail_configuration['port'] = $port;
            $mail_configuration['host'] = $host;
            $mail_configuration['status'] = $data['status'] ?? 0;
            //debug($mail_configuration);
            return $mail_configuration;
        }

        return [];
    }
}
?>
