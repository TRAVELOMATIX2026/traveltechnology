<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//require_once(dirname(__FILE__) . '/init.php'); // loads full Stripe SDK autoloader

require_once(dirname(__FILE__) . '/stripe/init.php');


class Stripe
{
    private $CI;
    private $secret_key;
    private $publishable_key;
    private $url;

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

    public function __construct()
    {
        //error_reporting(E_ALL);
        $this->CI = &get_instance();
        $this->api_error = ''; 
        $this->active_payment_system = $this->CI->config->item('active_payment_system');
        $this->secret_key = $this->CI->config->item('stripe_secret_key');
            $this->publishable_key = $this->CI->config->item('stripe_publishable_key');
            $this->url = 'https://api.stripe.com';

            // Initialize Stripe SDK
            \Stripe\Stripe::setApiKey($this->secret_key);
        //debug($this);
    }

    function initialize($data)
    {
        if ($this->active_payment_system == 'test') {
            //test
            // Load keys from config
            $this->secret_key = 'sk_test_51SMKwIJSF00UrivHIuEbdpa2YerwLf9ElbQh4Dd6LAfIt8TJT6rjU1IM0K58pcSoaESnT8aWXIjHr4SAXdICZhto00Ss6YYdtJ';
            $this->publishable_key = 'pk_test_51SMKwIJSF00UrivH4QJk1kJxBeiOLDYVaoxS2FcxGHWTErxslGfVAaS0UoWAqAk0IFEPO0jdcjrjYwpjksJ4Yfad00t0mA2itK';

            // Initialize Stripe SDK
            \Stripe\Stripe::setApiKey($this->secret_key); 
            $this->url = 'https://api.stripe.com';
        } 
        else 
        {
            //live
            $this->secret_key = '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';
            $this->publishable_key = 'pk_live_51SMKw1QoPZ1PwnvVueX3E9giuINob4nQY29S7gkABYRz39EFhR2Ufm5AIs2kKhAftHqYLWeEuX1UTbU7HF50N0Vn00anoOOBit';

            // Initialize Stripe SDK
            \Stripe\Stripe::setApiKey($this->secret_key);  
            $this->url = 'https://api.stripe.com';
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


    /**
     * Create PaymentIntent (new API)
     */
    public function create_payment_intent($data, $currency = 'usd', $metadata = [])
    {
        try {
            $currency = $data['currency'];
            $metadata = $data;
            //debug($metadata);
            \Stripe\ApiRequestor::setHttpClient(
    new \Stripe\HttpClient\CurlClient([
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ])
);

            $intent = \Stripe\PaymentIntent::create([
                'amount'   => intval($data['amount']),
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => $metadata,
            ]);

            return [
                'status' => 'success',
                'status_api' => $intent->success,
                'client_secret' => $intent->client_secret,
                'metadata' => $metadata,
                'payment_intent_id' => $intent->id, 
                'amount'        => $intent->amount,
                'description'   => $intent->description,
                'created'       => $intent->created,
                'customer'      => $intent->customer,

            ];

        } catch (\Stripe\Exception\ApiErrorException $e) {

            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve PaymentIntent
     */
    public function get_payment_intent($intent_id)
    {
        try {
            \Stripe\ApiRequestor::setHttpClient(
    new \Stripe\HttpClient\CurlClient([
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ])
);

            $intent = \Stripe\PaymentIntent::retrieve($intent_id);
            return ['status' => 'success', 'data' => $intent];

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function process_payment($book_id){
        $surl = 'success';//base_url().'index.php/payment_gateway/success';
        $furl = 'cancel';//base_url(). 'index.php/payment_gateway/cancel';
        //payumoney base url
        $PAYU_BASE_URL = $this->url;
        
        $post_data=array();
        $post_data['apikey'] = $this->CI->config->item('stripe_publishable_key');
        $post_data['txnid'] = $this->book_id;
        $post_data['amount'] = $this->pgi_amount;
        $post_data['firstname'] = $this->firstname;
        $post_data['email'] = $this->email;
        $post_data['phone'] = $this->phone;
        $post_data['productinfo'] = $this->productinfo;
        $post_data['surl'] = $surl;
        $post_data['furl'] = $furl;
        $post_data['service_provider'] = 'stripe';                
        $post_data['pay_target_url'] = $this->url;
        $post_data['client_id'] = $this->CI->config->item('stripe_secret_key');
        $post_data['udf1'] = $book_id;
        return $post_data;
    }
}
