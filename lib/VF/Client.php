<?php

namespace VF;

/**
 * @package VF
 */
class Client
{

    /**
     * Configuration array for what methods belong to what resource and if they are static
     * or not.
     *
     * This configuration is used in `__call()`
     *
     * The only method that will be defined will be addItem, because we have to
     * send multipart data and upload the file.
     *
     * @see Client::__call()
     * @see Client::makeRequest()
     * @var array
     */
    protected static $valid_calls = array(
        'items' => array(
            'getItem' => true,
            'getItems' => array(
                'endpoint' => 'get',
                'static' => true
            ),
            'addItem' => array(             // this method will be overridden
                'endpoint' => 'add',        // because of multipart headers
                'static' => true
            ),
            'editItem' => array(
                'endpoint' => 'edit'
            ),
            'moveItem' => array(
                'endpoint' => 'move'
            ),
            'deleteItem' => array(
                'endpoint' => 'delete'
            )
        ),
        'folders' => array(
            'getFolder' => true,
            'getFolderByPath' => array(
                'static' => true,
                'endpoint' => 'get-by-path'
            ),
            'addFolder' => array(
                'static' => true,
                'endpoint' => 'add'
            ),
            'moveFolder' => array(
                'endpoint' => 'move'
            ),
            'renameFolder' => array(
                'endpoint' => 'rename'
            ),
            'deleteFolder' => array(
                'endpoint' => 'delete'
            ),
            'getFolderItems' => array(
                'endpoint' => 'get-items'
            )
        )
    );

    /**
     * Hostname of the vfolder api
     * example: 'api.vfolder.net/v1'
     * @see static::getRequestUrl()
     * @var string
     */
    protected $api_url = '';

    /**
     * Oauth token to be apppended to the request
     * @see static::getRequestUrl()
     * @var string
     */
    protected $oauth_token = '';

    /**
     * Sets the configuration for generating the request url
     * and makes sure the object is configured
     * @param   array
     */
    public function __construct(array $config = array())
    {
        $this->api_url = $config['api_url'];
        $this->oauth_token = $config['oauth_token'];

        $this->validateConfiguration();
    }

    /**
     * Gets the response from the API
     * Uses static::$valid_calls to figure out what action is being taken automatically
     * @param   string  $method
     * @param   array   $args
     * @return  array
     */
    public function __call($method, $args)
    {
        $info = $this->getMethodInfo($method);
        $url = $this->getMethodUrl($method, $args);

        // set what arguments will be posted
        $post = ($info->static ? $args[0] : $args[1]) ?: array();

        return $this->makeRequest($url, $post);
    }

    /**
     * Uploads the item to the server or sends a url (depending if userfile is set),
     * otherwise $args[filename] should be a url.
     * @param   array   $args
     * @param   string  $userfile
     * @return  \stdClass
     * @throws  \Exception  if userfile specified and it is NOT a file
     */
    public function addItem(array $args = array(), $userfile = null)
    {
        $extra = array();

        if ($userfile) {

            if (!is_file($userfile)) {
                throw new \Exception('Not a valid file, cannot send addItem');
            }

            $args['userfile'] = "@$userfile";

            $extra = array(
                'HTTPHEADER' => array(
                    'Content-type: multipart/form-data'
                )
            );
        }

        return $this->makeRequest('items/add', $args, $extra);
    }

    /**
     * Validates the current state of the object
     * @throws  \Exception if not valid
     */
    protected function validateConfiguration()
    {
        if (!$this->api_url) {
            throw new \Exception('VF\Client needs an api_url.');
        }
    }

    /**
     * Gets method info based on the configuration array
     * @param   string
     * @return  \stdClass
     * @throws  \Exception  if method not found in config
     */
    protected function getMethodInfo($name)
    {
        foreach (static::$valid_calls as $resource => $info) {
            if (array_key_exists($name, $info)) {
                $info = is_array($info[$name]) ? $info[$name] : array();

                return (object) array_merge($info, array(
                    'resource' => $resource
                ));
            }
        }

        throw new \Exception('Invalid VF\Client method.');
    }

    /**
     * Gets the url of the method using the given arguments and method config
     * @param   string  $name
     * @param   array   $args
     * @return  string
     * @throws  \InvalidArgumentException   if this is a non "static" call
     *                                      and there is no ID looking param
     */
    protected function getMethodUrl($name, array $args)
    {
        $info = $this->getMethodInfo($name);

        if (!$info->static) {
            $arg = $args[0];
            if (!$arg || is_array($arg) || is_object($arg)) {
                throw new \InvalidArgumentException("Invalid args for $name");
            }
        }

        $url = array_filter(array(
            $info->resource,
            $info->static ? null : reset($args),
            $info->endpoint ?: null
        ));

        return implode('/', $url);
    }

    /**
     * Makes the request to the API via cURL and the arguments given
     * @param   string  $url
     * @param   array   $post
     * @param   array   $extra  extra curloptions, so far only HTTPHEADER supported
     * @return  \stdClass
     */
    protected function makeRequest($url, $post = array(), $opts = array())
    {
        $url = $this->getRequestUrl($url);
        $curl = curl_init($url);

        if ($opts['HTTPHEADER']) {
            if (!curl_setopt($curl, CURLOPT_HTTPHEADER, $opts['HTTPHEADER'])) {
                static::handleCurlError($curl, 'CURLOPT_HTTPHEADER');
            }
        }

        define(CURLOPT_TIMEOUT_MS, 155);
        if (!curl_setopt($curl, CURLOPT_TIMEOUT_MS, 15000)) {
            static::handleCurlError($curl, 'CURLOPT_TIMEOUT_MS');
        }

        if (!curl_setopt($curl, CURLOPT_POST, true)) {
            static::handleCurlError($curl, 'CURLOPT_POST');
        }

        if (!curl_setopt($curl, CURLOPT_POSTFIELDS, $post)) {
            static::handleCurlError($curl, 'CURLOPT_POSTFIELDS');
        }

        if (!curl_setopt($curl, CURLOPT_RETURNTRANSFER, true)) {
            static::handleCurlError($culr, 'CURLOPT_RETURNTRANSFER');
        }

        $name = '\VF\Client::makeRequest: ' . $url;
        elapsed('begin ' . $name);

        if ($_GET['vf_debug']) {

            echo $url . '<br />POST:';
            krumo($post);
        }

        $response = curl_exec($curl);
        elapsed('end ' . $name);

        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            // there was a curl transmission error
            return (object) array(
                'request' => array(
                    'url' => $url,
                    'post' => $post
                ),
                'errors' => array($error)
            );
        }

        $data = json_decode($response);

        if (!$data) {
            // the server did not respond with valid json
            // return the unformatted output as an error
            // there was a curl transmission error
            $data = (object) array(
                'request' => array(
                    'url' => $url,
                    'post' => $post
                ),
                'errors' => array($response)
            );
        }

        if ($_GET['vf_debug']) {
            echo 'response:<br />';
            krumo($data);
            echo '<br />';
        }

        return $data;

    }

    /**
     * Closes the curl connection and throws an Exception with the error
     * @param   resource    $curl
     * @param   string      $opt
     * @throws  \Exception  whatever the curl error was
     */
    protected function handleCurlError($curl, $opt)
    {
        $e = curl_error($curl) ?: 'UNKNOWN ERROR';
        curl_close($curl);

        throw new \Exception(
            "Cannot make Request, curl error in curl_setopt({$opt}): '{$e}'."
        );
    }

    /**
     * Gets the url that is being used to make the request
     * @param   string  $action
     * @return  string
     */
    protected function getRequestUrl($action)
    {
        return sprintf(
            '%s/%s?oauth_token=%s',
            rtrim($this->api_url, '/'),
            rtrim($action, '/'),
            $this->oauth_token
        );
    }

}
