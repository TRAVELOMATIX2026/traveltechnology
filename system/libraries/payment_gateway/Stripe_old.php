<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

// Stripe singleton
// require(dirname(__FILE__) . '/stripe/lib/Stripe.php');

// Utilities
require(dirname(__FILE__) . '/lib/Util/AutoPagingIterator.php');
require(dirname(__FILE__) . '/lib/Util/LoggerInterface.php');
require(dirname(__FILE__) . '/lib/Util/DefaultLogger.php');
require(dirname(__FILE__) . '/lib/Util/RequestOptions.php');
require(dirname(__FILE__) . '/lib/Util/Set.php');
require(dirname(__FILE__) . '/lib/Util/Util.php');

// HttpClient
require(dirname(__FILE__) . '/lib/HttpClient/ClientInterface.php');
require(dirname(__FILE__) . '/lib/HttpClient/CurlClient.php');

// Errors
require(dirname(__FILE__) . '/lib/Error/Base.php');
require(dirname(__FILE__) . '/lib/Error/Api.php');
require(dirname(__FILE__) . '/lib/Error/ApiConnection.php');
require(dirname(__FILE__) . '/lib/Error/Authentication.php');
require(dirname(__FILE__) . '/lib/Error/Card.php');
require(dirname(__FILE__) . '/lib/Error/InvalidRequest.php');
require(dirname(__FILE__) . '/lib/Error/Permission.php');
require(dirname(__FILE__) . '/lib/Error/RateLimit.php');
require(dirname(__FILE__) . '/lib/Error/SignatureVerification.php');

// OAuth errors
require(dirname(__FILE__) . '/lib/Error/OAuth/OAuthBase.php');
require(dirname(__FILE__) . '/lib/Error/OAuth/InvalidGrant.php');
require(dirname(__FILE__) . '/lib/Error/OAuth/InvalidRequest.php');
require(dirname(__FILE__) . '/lib/Error/OAuth/InvalidScope.php');
require(dirname(__FILE__) . '/lib/Error/OAuth/UnsupportedGrantType.php');
require(dirname(__FILE__) . '/lib/Error/OAuth/UnsupportedResponseType.php');

// Plumbing
require(dirname(__FILE__) . '/lib/ApiResponse.php');
require(dirname(__FILE__) . '/lib/JsonSerializable.php');
require(dirname(__FILE__) . '/lib/StripeObject.php');
require(dirname(__FILE__) . '/lib/ApiRequestor.php');
require(dirname(__FILE__) . '/lib/ApiResource.php');
require(dirname(__FILE__) . '/lib/SingletonApiResource.php');
require(dirname(__FILE__) . '/lib/AttachedObject.php');
require(dirname(__FILE__) . '/lib/ExternalAccount.php');

// Stripe API Resources
require(dirname(__FILE__) . '/lib/Account.php');
require(dirname(__FILE__) . '/lib/AlipayAccount.php');
require(dirname(__FILE__) . '/lib/ApplePayDomain.php');
require(dirname(__FILE__) . '/lib/ApplicationFee.php');
require(dirname(__FILE__) . '/lib/ApplicationFeeRefund.php');
require(dirname(__FILE__) . '/lib/Balance.php');
require(dirname(__FILE__) . '/lib/BalanceTransaction.php');
require(dirname(__FILE__) . '/lib/BankAccount.php');
require(dirname(__FILE__) . '/lib/BitcoinReceiver.php');
require(dirname(__FILE__) . '/lib/BitcoinTransaction.php');
require(dirname(__FILE__) . '/lib/Card.php');
require(dirname(__FILE__) . '/lib/Charge.php');
require(dirname(__FILE__) . '/lib/Collection.php');
require(dirname(__FILE__) . '/lib/CountrySpec.php');
require(dirname(__FILE__) . '/lib/Coupon.php');
require(dirname(__FILE__) . '/lib/Customer.php');
require(dirname(__FILE__) . '/lib/Dispute.php');
require(dirname(__FILE__) . '/lib/EphemeralKey.php');
require(dirname(__FILE__) . '/lib/Event.php');
require(dirname(__FILE__) . '/lib/FileUpload.php');
require(dirname(__FILE__) . '/lib/Invoice.php');
require(dirname(__FILE__) . '/lib/InvoiceItem.php');
require(dirname(__FILE__) . '/lib/LoginLink.php');
require(dirname(__FILE__) . '/lib/Order.php');
require(dirname(__FILE__) . '/lib/OrderReturn.php');
require(dirname(__FILE__) . '/lib/Payout.php');
require(dirname(__FILE__) . '/lib/Plan.php');
require(dirname(__FILE__) . '/lib/Product.php');
require(dirname(__FILE__) . '/lib/Recipient.php');
require(dirname(__FILE__) . '/lib/RecipientTransfer.php');
require(dirname(__FILE__) . '/lib/Refund.php');
require(dirname(__FILE__) . '/lib/SKU.php');
require(dirname(__FILE__) . '/lib/Source.php');
require(dirname(__FILE__) . '/lib/Subscription.php');
require(dirname(__FILE__) . '/lib/SubscriptionItem.php');
require(dirname(__FILE__) . '/lib/ThreeDSecure.php');
require(dirname(__FILE__) . '/lib/Token.php');
require(dirname(__FILE__) . '/lib/Transfer.php');
require(dirname(__FILE__) . '/lib/TransferReversal.php');

// OAuth
require(dirname(__FILE__) . '/lib/OAuth.php');

// Webhooks
require(dirname(__FILE__) . '/lib/Webhook.php');
require(dirname(__FILE__) . '/lib/WebhookSignature.php');

// echo "string";exit;

class Stripe {
    
    static $apikey;
    static $clientid;
    static $url;

    var $active_payment_system;
    var $api_error; 
    var $book_id = '';
    var $book_origin = '';
    var $pgi_amount = '';
    var $name = '';
    var $firstname = '';
    var $email = '';
    var $phone = '';
    var $productinfo = '';
    public function __construct() {
        $this->CI = &get_instance ();
        $this->api_error = ''; 
        $this->active_payment_system = $this->CI->config->item('active_payment_system');
    }

    function initialize($data)
    {
        if ($this->active_payment_system == 'test') {
            //test
            self::$apikey = 'pk_test_51SMKwIJSF00UrivH4QJk1kJxBeiOLDYVaoxS2FcxGHWTErxslGfVAaS0UoWAqAk0IFEPO0jdcjrjYwpjksJ4Yfad00t0mA2itK';
            self::$clientid = 'sk_test_51SMKwIJSF00UrivHIuEbdpa2YerwLf9ElbQh4Dd6LAfIt8TJT6rjU1IM0K58pcSoaESnT8aWXIjHr4SAXdICZhto00Ss6YYdtJ';
            self::$url = 'https://connect.stripe.com/oauth/authorize'; 
        } 
        else 
        {
            //live
             self::$apikey = '';
            self::$clientid = '';
            self::$url = 'https://connect.stripe.com/oauth/authorize';  
        }
        $this->book_id = $data['txnid'];
        // $temp = str_replace('.', '', $data['pgi_amount']);
        $temp = $data['pgi_amount'];
        $this->pgi_amount = $temp;
        $this->firstname = $data['firstname'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->productinfo = $data['productinfo'];
        // debug($this);exit;
    }

    function process_payment($book_id){
        $surl = 'success';//base_url().'index.php/payment_gateway/success';
        $furl = 'cancel';//base_url(). 'index.php/payment_gateway/cancel';
        //payumoney base url
        $PAYU_BASE_URL = "https://connect.stripe.com/oauth/authorize";
        
        $post_data=array();
        $post_data['apikey'] = self::$apikey;
        $post_data['txnid'] = $this->book_id;
        $post_data['amount'] = $this->pgi_amount;
        $post_data['firstname'] = $this->firstname;
        $post_data['email'] = $this->email;
        $post_data['phone'] = $this->phone;
        $post_data['productinfo'] = $this->productinfo;
        $post_data['surl'] = $surl;
        $post_data['furl'] = $furl;
        $post_data['service_provider'] = 'stripe';                
        $post_data['pay_target_url'] = self::$url;
        $post_data['client_id'] = self::$clientid;
        $post_data['udf1'] = $book_id;
        return $post_data;
    }
    
    // @var string The Stripe API key to be used for requests.
    public static $apiKey = 'sk_test_51QM0h02KHg2nEhWpTdU9QEDspcJQ2O5xI2BhED9Le2gvXeKobLzIcQyI4Xn5gpeH5HxdffyVr02P3kq3L62TO92v00WT618BRH'; // test publishable keys
    
    // @var string The Stripe client_id to be used for Connect requests.
    public static $clientId = 'sk_test_51QM0h02KHg2nEhWpTdU9QEDspcJQ2O5xI2BhED9Le2gvXeKobLzIcQyI4Xn5gpeH5HxdffyVr02P3kq3L62TO92v00WT618BRH';// test secret key 

    // @var string The base URL for the Stripe API.
    public static $apiBase = 'https://api.stripe.com';

    // @var string The base URL for the OAuth API.
    public static $connectBase = 'https://connect.stripe.com';

    // @var string The base URL for the Stripe API uploads endpoint.
    public static $apiUploadBase = 'https://uploads.stripe.com';

    // @var string|null The version of the Stripe API to use for requests.
    public static $apiVersion = null;

    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;

    // @var boolean Defaults to true.
    public static $verifySslCerts = true;

    // @var array The application's information (name, version, URL)
    public static $appInfo = null;

    // @var Util\LoggerInterface|null The logger to which the library will
    //   produce messages.
    public static $logger = null;

    const VERSION = '5.1.1';

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @return string The client_id used for Connect requests.
     */
    public static function getClientId()
    {
        return self::$clientId;
    }

    /**
     * @return Util\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger()
    {
        if (self::$logger == null) {
            return new lib\Util\DefaultLogger();
        }
        return self::$logger;
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     */
    public static function setLogger($logger)
    {
        self::$logger = $logger;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     */
    public static function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @return boolean
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * @param boolean $verify
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return string | null The Stripe account ID for connected account
     *   requests.
     */
    public static function getAccountId()
    {
        return self::$accountId;
    }

    /**
     * @param string $accountId The Stripe account ID to set for connected
     *   account requests.
     */
    public static function setAccountId($accountId)
    {
        self::$accountId = $accountId;
    }

    /**
     * @return array | null The application's information
     */
    public static function getAppInfo()
    {
        return self::$appInfo;
    }

    /**
     * @param string $appName The application's name
     * @param string $appVersion The application's version
     * @param string $appUrl The application's URL
     */
    public static function setAppInfo($appName, $appVersion = null, $appUrl = null)
    {
        if (self::$appInfo === null) {
            self::$appInfo = array();
        }
        self::$appInfo['name'] = $appName;
        self::$appInfo['version'] = $appVersion;
        self::$appInfo['url'] = $appUrl;
    }

   function create($params = null, $options = null)
    {
        error_reporting(E_ALL);
        try { 
            // Charge a credit or a debit card 
            $charge = \lib\Charge::create($params, $options); 
            // Retrieve charge details 
            $chargeJson = $charge->jsonSerialize(); 
            //debug($chargeJson);
            return $chargeJson;
            //return lib\Charge::create($params, $options);
        }catch(Exception $e) { 
            //$this->api_error = $e->getMessage(); 
            $error = $e->getTrace();
            $this->api_error = $error[0]['args'][0];
            //debug($error);
            return false; 
        }

        //return lib\Charge::create($params, $options);
    }

    function retrieve($params = null, $options = null)
    {
        //error_reporting(E_ALL);
        try { 
            // Charge a credit or a debit card 
            $charge = \lib\Charge::retrieve($params, $options); 
            // Retrieve charge details 
            $chargeJson = $charge->jsonSerialize(); 
            //debug($chargeJson);
            return $chargeJson;
            //return lib\Charge::create($params, $options);
        }catch(Exception $e) { 
            //$this->api_error = $e->getMessage(); 
            $error = $e->getTrace();
            $this->api_error = $error[0]['args'][0];
            //debug($this->api_error);
            return false; 
        } 
        //return lib\Charge::create($params, $options);
    }

}