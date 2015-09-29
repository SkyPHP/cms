<?php

/**
 * @package SkyPHP
 * @todo    Add ability to to send Attachments
 */
class Mailer
{


    public $result = null ;

    /**
     * @var string
     */
    public static $from_default = null;

    /**
     * @var string
     */
    public static $inc_dir = null;

    /**
     * @var array
     */
    public static $contents = array(
        'html' => "MIME-Verson: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n",
        'text' => ''
    );




    /**
    * @var string
    * 
    * localhost : the traditional PHP's mail method
    * mandrill  : mandrill's API, require $credentials with username/password 
    */
    public $method = 'localhost' ;


    /**
    * @var object 
    */ 
    public $credentials = null; 


    /**
     * @var array
     */
    public $to = array();

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $reply_to;

    /**
     * @var array
     */
    public $cc = array();

    /**
     * @var array
     */
    public $bcc = array();

     /**
     * @var string
     */
    public $headers;

    /**
     * @var string
     */
    public $content_type;

    /**
    *  @var object
    */
    public $data; 


    /**
     * Sets properties based on args if they are set
     * @param   mixed   $to
     * @param   string  $subject
     * @param   string  $body
     * @param   string  $from
     */
    public function __construct($to = null, $subject = null, $body = null, $from = null)
    {
        $this->from = self::$from_default;

        if ($to) {
            $this->addTo($to);
        }

        if ($subject) {
            $this->subject = $subject;
        }

        if ($body) {
            $this->body = $body;
        }

        if ($from) {
            $this->from = $from;
        }
    }

    /**
     * Sets the default FROM value
     * @param   string  $from
     */
    public static function setDefaultFrom($from)
    {
        self::$from_default = $from;
    }

    /**
     * Sets from
     * @param   string  $s
     * @return  $this
     */
    public function setFrom($s)
    {
        $this->from = $s;
        return $this;
    }

    /**
     * Sets reply to
     * @param   string  $s
     * @return  $this
     */
    public function setReplyTo($s)
    {
        $this->reply_to = $s;
        return $this;
    }

    /**
     * Sets the subject
     * @param   string  $s
     * @return  $this
     */
    public function setSubject($s)
    {
        $this->subject = $s;
        return $this;
    }



    /**
     * Sets the delivery method
     * @param   string  $s
     * @return  $this
     */
    public function setMethod($s)
    {
        $this->method = $s;
        return $this;
    }

    /**
     * Sets the body
     * @param   string  $s
     * @return  $this
     */
    public function setBody($s)
    {
        
        $this->body = $s;

        return $this;
    }

    /**
     * Makes the headers string and returns it... sets $this->headers property
     * @return  string
     * @throws  Exception   if FROM not set
     */
    public function makeHeaders()
    {
        if ($this->headers) {
            return $this->headers;
        }

        if (!$this->from) {
            throw new Exception('Mailer expects from to be specified before sending an email.');
        }

        $this->headers = $this->content_type;

        if ($this->from) {
            $this->headers .= 'From: '.$this->from."\r\n";
        }

        if ($this->reply_to) {
            $this->headers .= 'Reply-To: ' . $this->reply_to . "\r\n";
        }

        foreach ($this->cc as $cc) {
            $this->headers .= 'Cc: '.$cc."\r\n";
        }

        foreach ($this->bcc as $bcc) {
            $this->headers .= 'Bcc: '.$bcc."\r\n";
        }

        return $this->headers;
    }

    /**
     * Sets the content type
     * @param string  $type
     * @return  $this
     */
    public function setContentType($type)
    {
        $this->content_type = self::$contents[$type];
        return $this;
    }

    /**
     * @param   ...     emails
     * @return  $this
     */
    public function addCc()
    {
        return $this->_append('cc', func_get_args());
    }

    /**
     * @param   ...     emails
     * @return  $this
     */
    public function addBcc()
    {
        return $this->_append('bcc', func_get_args());
    }

    /**
     * @param   ...     emails
     * @return  $this
     */
    public function addTo()
    {
        return $this->_append('to', func_get_args());
    }

    /**
     * Pushes args onto the array
     * @param   string  $arr
     * @param   array   $args
     * @return  $this
     */
    private function _append($arr, $args)
    {
        if(!$args)
            return ;

        foreach ($args as $arg) {
            $arg = arrayify($arg);
            foreach ($arg as $a) {
                $this->{$arr}[] = $a;
            }
        }

        return $this;
    }

    /**
     * @return  string
     */
    public function makeSubject()
    {
        return $this->subject ?: '(no subject)';
    }

    /**
     * @return  string
     */
    public function makeTo()
    {
        return implode(',', $this->to);
    }

    /**
     * Sends the email
     * @return  Boolean
     */
    public function send()
    {
        $mail = new stdClass;
        $mail->to = $this->makeTo();
        $mail->subject = $this->makeSubject();
        $mail->body = $this->body;
        $mail->headers = $this->makeHeaders();
        $mail->from = $this->from ; 
        

        if($this->method == 'mandrill'){
            return $this->send_mandrill($mail);
        }
        
        return $this->send_local($mail);
    }


    /**
    * Send email using localhost
    * @param    string  $mail   well formed mail object  - must contain : to, subject, body 
    */
    function send_local ($mail){
        return @mail(
            $mail->to,
            $mail->subject,
            $mail->body,
            $mail->headers
        );
    }

    /**
    * Send email using mandrill
    * @param    string  $mail   well formed mail object  - must contain : to, subject, body 
    */
    function send_mandrill ($mail){

        global $message; 

        
        // add the mandrill's API library 
        require_once 'lib/mandrill/Mandrill.php';

        $mandrill = new Mandrill($this->credentials->api);

        
            $message = array(
                'html' => $mail->body,

                'from_email' => $mail->from,
                'from_name' => "Crave Tickets",

                //'text' => 'Example text content',
                'subject' => $mail->subject,
                'to' => array(
                    array(
                        'email' => $mail->to,
                        )
                    )
                );


            if($this->bcc && count($this->bcc)){

                array_walk($this->bcc , function ($value){
                    global $message;

                    if($value && stristr($value, '@')){


                        $message['to'][] = [
                            'email' => $value, 
                            'type' => 'bcc'
                        ];
                    }

                });
            }


            if($this->cc && count($this->cc)){

                array_walk($this->cc , function ($value){
                    global $message;

                    if($value && stristr($value, '@')){


                        $message['to'][] = [
                            'email' => $value, 
                            'type' => 'cc'
                        ];
                    }

                });
            }
            
            
            $result = $mandrill->messages->send($message, true);

            if($_GET['debug'] && $_GET['elapsed']) {
                d($result, $mandril, $result);
            }
            
            if ($result[0] && $result[0]['status'] == "sent") {
                return $result;
                //return 1 ;
            }else {
                $this->result = $result; 
            }
            

    }


    /**
    * @param    object  $creds  credentials are specific for delivery method
    */
    function setCredentials ($creds){

        $this->credentials = $creds;
        return $this;
    }


    /**
     * Includes the template and sets the body of the email with it
     * @param   string  $name   name of template or path to php file
     * @param   array   $data
     * @return  $this
     * @throws  Exception   if using a Mailer template and there is no inc_dir
     * @throws  Excpetion   if the file to include does not exist
     */


    public function inc($name,  $data )
    {


        // if(!is_array($data))
        //         $data = \Sky\DataConversion::objectToArray($data);

        //     d($data);
        $this->data = (object)$data;


        if (strpos($name, '.php')) {
            $include = $name;
        } else {
            if (!self::$inc_dir) {
                throw new Exception('Mailer::$inc_dir not set.');
            }

            $include = self::$inc_dir . $name . '.php';
        }

        if (!file_exists_incpath($include)) {
            throw new Exception('Mailer "' . $include . '" does not exist');
        }

        return $this->setBody($this->_includeTemplate($include, $data));
    }

    /**
     * Includes the path in the scope of the Mailer
     * @param   string  $_include
     * @param   mixed   $data   should be an associative array or stdClass
     * @return  string
     */
    private function _includeTemplate($_include, $data)
    {
        ob_start();
        include $_include;
        $r = ob_get_contents();
        ob_end_clean();
        return $r;
    }

}