<?php

/**
 * Simple PHP AdobeDigitalMarketing API
 *
 * @tutorial  http://AdobeDigitalMarketing.com/ornicar/php-AdobeDigitalMarketing-api/blob/master/README.markdown
 * @version   2.6
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 *
 * Website: http://AdobeDigitalMarketing.com/ornicar/php-AdobeDigitalMarketing-api
 * Tickets: http://AdobeDigitalMarketing.com/ornicar/php-AdobeDigitalMarketing-api/issues
 */
class AdobeDigitalMarketing_Client
{
    /**
     * The request instance used to communicate with AdobeDigitalMarketing
     * @var AdobeDigitalMarketing_HttpClient
     */
    protected $httpClient  = null;

    /**
     * The list of loaded API instances
     * @var array
     */
    protected $apis     = array();

    /**
     * Use debug mode (prints debug messages)
     * @var bool
     */
    protected $debug;

    /**
     * Instanciate a new AdobeDigitalMarketing client
     *
     * @param  AdobeDigitalMarketing_HttpClient_Interface $httpClient custom http client
     */
    public function __construct(AdobeDigitalMarketing_HttpClientInterface $httpClient = null)
    {
        if (null === $httpClient) {
            $this->httpClient = new AdobeDigitalMarketing_HttpClient_Curl();
        } else {
            $this->httpClient = $httpClient;
        }
    }

    /**
     * Authenticate a user for all next requests
     *
     * @param  string         $username      AdobeDigitalMarketing Web Services username
     * @param  string         $secret        AdobeDigitalMarketing Web Services secret
     * @return AdobeDigitalMarketingApi               fluent interface
     */
    public function authenticate($username, $secret)
    {
        $this->getHttpClient()
            ->setOption('username', $username)
            ->setOption('secret', $secret);

        return $this;
    }

    /**
     * Deauthenticate a user for all next requests
     *
     * @return AdobeDigitalMarketingApi               fluent interface
     */
    public function deAuthenticate()
    {
        return $this->authenticate(null, null);
    }

    /**
     * Call any route, GET method
     * Ex: $api->get('repos/show/my-username/my-repo')
     *
     * @param   string  $route            the AdobeDigitalMarketing route
     * @param   array   $parameters       GET parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function get($route, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->get($route, $parameters, $requestOptions);
    }

    /**
     * Call any route, POST method
     * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
     *
     * @param   string  $route            the AdobeDigitalMarketing route
     * @param   array   $parameters       POST parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function post($route, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->post($route, $parameters, $requestOptions);
    }

    /**
     * Get the httpClient
     *
     * @return  AdobeDigitalMarketing_HttpClient_Interface   an httpClient instance
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Inject another request
     *
     * @param   AdobeDigitalMarketing_HttpClient_Interface   a httpClient instance
     * @return  AdobeDigitalMarketingApi          fluent interface
     */
    public function setHttpClient(AdobeDigitalMarketing_HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Get the report API
     *
     * @return  AdobeDigitalMarketing_Api_Report  the report API
     */
    public function getReportApi($options = array())
    {
        if(!isset($this->apis['report']))
        {
            $this->apis['report'] = new AdobeDigitalMarketing_Api_Report($this, $options);
        }

        return $this->apis['report'];
    }

    /**
     * Inject another API instance
     *
     * @param   string                $name the API name
     * @param   AdobeDigitalMarketingApiAbstract   $api  the API instance
     * @return  AdobeDigitalMarketing_Client       fluent interface
     */
    public function setApi($name, AdobeDigitalMarketing_ApiInterface $instance)
    {
        $this->apis[$name] = $instance;

        return $this;
    }

    /**
     * Get any API
     *
     * @param   string                    $name the API name
     * @return  AdobeDigitalMarketing_Api_Abstract     the API instance
     */
    public function getApi($name)
    {
        return $this->apis[$name];
    }
}