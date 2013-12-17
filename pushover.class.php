<?php

class pushover
{

    /**
     * @const LIB_ERROR_TYPE can be exception or error
     */
    const LIB_ERROR_TYPE = 'error';

    /**
     * @const service api base url
     */
    const BASE_URL = 'https://api.pushover.net/1/';

    /**
     * toggles on debugging
     *
     * @var bool
     */
    public $debug = false;

    public $lastStatus = false;
    /**
     * @var bool|string
     */
    protected $apiToken = false;

    // Message data
    public $user;
    public $message;
    public $options = array();

    protected $error_codes
        = array(
            200     => 'Notification submitted.',
            400     => 'The data supplied is in the wrong format, invalid length or null.',
            401     => 'None of the API keys provided were valid.',
            402     => 'Maximum number of API calls per hour exceeded.',
            500     => 'Internal server error. Please contact our support if the problem persists.'
        );

    /**
     * @param array $options
     */
    function __construct($options = array())
    {
        if (!isset($options['apiToken'])) {
            return $this->error('You must supply a Application\'s API Token / Key');
        } else {
            $this->apiToken = $options['apiToken'];
        }

        if (isset($options['debug'])) {
            $this->debug = true;
        }

    }


    
    /**
     * @param string $application
     * @param string $event
     * @param string $description
     * @param int    $priority
     * @param bool   $apiTokens
     * @param array  $options
     *
     * @return bool|mixed|SimpleXMLElement|string
     */
    public function notify($user = '', $message = '', $options = array())
    {
        $url = self::BASE_URL.'messages.json';
       
        if(!empty($user) && !empty($message)){
            $data = array(
                'user'    => $user,
                'message' => $message
            );

            if(!empty($this->title)){
                $data['title'] = $title;
            }

            $data = array_merge($data, $options);

            return $this->makeApiCall($url, $data);
        } else {
            $this->urlfetch_response = 'User & Message are required to send notification.';
            return false;
        }

    }

    /**
     * @param string $key Key user/group key for validation.
     *
     * @return bool
     */
    public function validate($userKey = false)
    {
        $url = self::BASE_URL.'users/validate.json';
        $options = array();

        if ($userKey !== false) {
            $options['user'] = $userKey;
        } else {
            $this->error("Key for user/group not supplied.");
        }
        
        $responseByAPI = $this->makeApiCall($url, $options);
       
        if ($responseByAPI['status'] ==  1) {
            return true;
        } else 
            return false;
    }

    /**
     * @param        $url
     * @param null   $params
     * @param string $method
     * @param string $format
     *
     * @return bool|mixed|SimpleXMLElement|string
     * @throws Exception
     */
    protected function makeApiCall($url, $data = null, $method = 'POST', $format = 'json')
    {

        $contextParam = array(
            'http' => array(
                'method'        => $method,
                'ignore_errors' => true
            )
        );
        if ($data !== null && !empty($data)) {
            
            $params = array(
                'token' => $this->apiToken
            );

            $params = array_merge($params, $data);

            $params = http_build_query($params, '', '&');
            
            if ($method == 'POST') {
                $contextParam["http"]['header'] = 'Content-Type: application/x-www-form-urlencoded';
                $contextParam['http']['content'] = $params;
            } else {
                $url .= '?' . $params;
            }
        } else {
            return $this->error(
                'Data, essential to make api calls, is not provide' . $this->debug ? ', you provided: ' . var_dump($params)
                    : '.'
            );
        }

        $context = stream_context_create($contextParam);
        $responseByFO = fopen($url, 'rb', false, $context);
        if (!$responseByFO) {
            $res = false;
        } else {

            if ($this->debug) {
                $meta = stream_get_meta_data($responseByFO);
                $this->error('var dump of http headers' . var_dump($meta['wrapper_data']));
            }

            $res = stream_get_contents($responseByFO);
        }

        if ($res === false) {
            return $this->error("$method $url failed: $php_errormsg");
        }

        switch ($format) {
            case 'json':            
                $res = json_decode($res, TRUE);
                if ($res === null) {
                    return $this->error("failed to decode $res as json");
                }
                break;
            default:
                return $this->error('Invalid format asked for. -pushover/makeApiCall ');
        }
        return $res;
    }

    /**
     * @param     $message
     * @param int $type
     *
     * @return bool
     * @throws Exception
     */
    private function error($message, $type = E_USER_NOTICE)
    {
        if (self::LIB_ERROR_TYPE == 'error') {
            trigger_error($message, $type);
            exit();
        } else {
            throw new Exception($message, $type);
        }
    }

}