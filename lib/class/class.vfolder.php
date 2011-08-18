<?

#vfolder client class
class vfolder{
   public $username = NULL;
   protected $password = NULL;

   public $accounts_id = NULL;
 
   public $secure = NULL;
   public $server_url = NULL;

   public $log = NULL;
   public $errors = NULL;

   public $max_log_size = NULL;

   public $func_boilerplate = NULL;

   public $initialized = NULL;

   public function __construct($params = NULL){
      $this->log = array();
      $this->errors = array();

      #this should be in a config
      global $vfolder_max_log_size;
      $vfolder_max_log_size || ($vfolder_max_log_size = 50);
      $this->max_log_size = $vfolder_max_log_size;
 
      if(func_num_args() > 1){
         $args = func_get_args(); 

         $username = array_shift($args);
         $password = array_shift($args);
         $server_url = array_shift($args);
      }else{
         $username = $params['username'];

         $username || ($username = $params['accounts_id']);

         $password = $params['password'];
         $server_url = $params['server_url'];
         $secure = $params['secure'];
         $files_domain = $params['files_domain'];
         if($params['max_log_size']){
            $this->max_log_size = $params['max_log_size'];
         }
      }

      if(!($username && $password)){
         $this->write_log('Username or Password not given, can not authenticate', true);

         return(NULL);
      }

      if($files_domain){
         $this->func_boilerplate = array();

         $this->func_boilerplate['items'] = $this->func_boilerplate['items/edit'] = array(
            'files_domain' => $files_domain
         );
      }

      if(!$server_url){
         #this should be in a config
         global $vfolder_url;

         if(!($server_url = $vfolder_url)){
            $server_url = 'vfolder.net/v1';
         }
      }

      if($secure){
         if(strpos($server_url, 'http://') === 0){
            $server_url = substr($server_url, 7);
            $server_url = "https://$server_url";
         }elseif(strpos($server_url, 'https://') !== 0){
            $server_url = "https://$server_url";
         }
      }else{
         if(strpos($server_url, 'http://') !== 0){
            if(strpos($server_url, 'https://') === 0){
               $secure = true;
            }else{
               $server_url = "http://$server_url";
            }
         }
      }

      $this->secure = $secure;

      $this->server_url = $server_url;

      if(self::is_MongoId($username)){
         $this->accounts_id = $username;
      }else{
         $this->username = $username;
      }

      $this->password = $password;

      $this->initialized = true;

      #this should be in a config
      global $vfolder_skip_request;
  
      if(!$vfolder_skip_request){ 
         $request = $this->make_request();
    
         if(!(is_array($request) && $request['authenticated'])){
            $this->write_log('Authentication test failed', true);
         }
      }

      return(true);
   }

   public function generate_signature($time = NULL){
      if(!($password = $this->password)){
         $this->write_log('No password specified, will not generate signature', true);

         return(NULL);
      }

      if(!$time){
         $time = time();
      }

      return(md5("$time $password"));
   }

   public function make_request($func = NULL, $id = NULL, $json = NULL, $return_text = NULL, $CURLOPT_HTTPHEADER = NULL){
      if(!(($this->username || $this->accounts_id) && $this->password)){
         $this->write_log('Username or password not given, can not authenticate', true);

         return(NULL);
      }

      $this->write_log('Attempting to make request');

      $post = array();

      if(is_array($json) && $json['json']){
         $post = $json;      
      }else{
         $post['json'] = $json;
      }

      if(is_array($this->func_boilerplate) && is_array($this->func_boilerplate[$func])){
         is_array($post['json']) || (is_string($post['json']) && is_array($post['json'] = json_decode($post['json'], true))) || ($post['json'] = array());

         foreach($this->func_boilerplate[$func] as $key => $value){
            !$post['json'][$key] && ($post['json'][$key] = $value);
         }
         unset($key, $value);
      }

      $func && ($post['func'] = $func);
      $id && ($post['id'] = $id);

      if($post['json']){
         if(is_array($post['json'])){
            if(!($post['json'] = json_encode($post['json']))){
               $this->write_log('Invalid json given, continuing', true);
               unset($post['json']);
            }
         }
      }

      if(!($curl_handle = curl_init($this->server_url))){
         $this->write_log('curl_init failed, can not make request', true);

         return(NULL);
      }

      if($CURLOPT_HTTPHEADER){
         if(!curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER)){
            $this->write_log('curl_setopt CURL_HTTPHEADER failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', will not make request', true);

            return(NULL);
         }
      }

      if(!curl_setopt($curl_handle, CURLOPT_POST, 1)){
         $this->write_log('curl_setopt CURLOPT_POST failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', can not make request', true);

         curl_close($curl_handle);
         return(NULL);
      }

      $this->accounts_id?$post['accounts_id'] = $this->accounts_id:$post['username'] = $this->username;
      $post['timestamp'] = time();
      if(!($post['signature'] = $this->generate_signature($time))){
         $this->write_log('No signature generated, will not make request', true);
    
         curl_close($curl_handle);
         return(NULL);
      }

      if(!curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post)){
         $this->write_log('curl_setopt CURLOPT_POSTFIELDS failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', can not make request', true);

         curl_close($curl_handle);
         return(NULL);
      }

      if(!curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1)){
         $this->write_log('curl_setopt CURLOPT_RETURNTRANSFER failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', can not make request', true);

         curl_close($curl_handle);
         return(NULL);
      }

      $this->write_log('Sending request...');

      $start_time = microtime(true);

      if(($response = curl_exec($curl_handle)) === false){
         $this->write_log('curl_exec failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', can not make request', true);

         curl_close($curl_handle);
         return(NULL);
      }

      $this->write_log('Successfully sent request');

      curl_close($curl_handle);
 
      $end_time = microtime(true);

      if(!($decoded = json_decode($response, true))){
         $this->write_log('Server response was not a valid json string', true);
         $this->write_log("Response: $response", true);
      }else{
         is_array($decoded) && (
            $decoded['_client'] = array(
               'duration' => $end_time - $start_time
            )
         );
      }

      return($return_text?$response:($decoded?$decoded:$response));	
   }

   public function upload_to_server($file = NULL, $json = NULL){
      if(!file_exists($file)){
         $this->write_log("File '$file' does not exist, can not upload to server", true);
      
         return(NULL);
      }

      if(is_dir($file)){
         $this->write_log("'$file' is a directory, can not upload to server", true);

         return(NULL);
      }

      #this should be in a config
      global $vfolder_MAX_FILE_SIZE;
      $vfolder_MAX_FILE_SIZE || ($vfolder_MAX_FILE_SIZE = 300000);

      if(filesize($file) > $vfolder_MAX_FILE_SIZE){
         $this->write_log("File '$file' exceeds MAX_FILE_SIZE of $vfolder_MAX_FILE_SIZE, will not upload to server", true);

         return(NULL);
      }

      $post = array(
         'MAX_FILE_SIZE' => $vfolder_MAX_FILE_SIZE,
         'userfile' => "@$file"
      );

      if($json){ 
         $post['json'] = $json;
      } 

      $this->write_log("Attempting to upload file '$file'");

      return($this->make_request('items/add', NULL, $post, NULL, array('Content-type: multipart/form-data')));
   }

   public function add_item($source = NULL, $json = NULL){
      if(!$source){
         $this->write_log('No source specified, can not add item', true);

         return(NULL);
      }

      if(strpos($source, 'http://') === 0){
         $json['url'] = $source;

         $this->write_log("Attempting to add item from url '$source'");

         return($this->make_request('items/add', NULL, $json));
      }

      return($this->upload_to_server($source, $json));      
   }

   public function get_item($items_id = NULL, $params = NULL, $extra_params = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not get item', true);

         return(NULL);
      }

      #this is to account for the short-hand syntaxes for this function
      if(func_num_args() > 1 && !is_array($params) && !is_array($extra_params)){
         $args = func_get_args();
         array_shift($args);

         unset($extra_params);

         switch(count($args)){
            case(1):
               if(!($args[0] = (int) $args[0])){
                  $this->write_log('Invalid parameters given, will not get item', true);
                  return(NULL);
               }

               $params = array(
                  array(
                     'type' => 'resize',
                     'width' => $args[0]
                  )
               );
               break;
            case(2):
               if(!(($args[0] = (int) $args[0]) && ($args[1] = (int) $args[1]))){
                  $this->write_log('Invalid parameters given, will not get item', true);
                  return(NULL);
               }

               $params = array(
                  array(
                     'type' => 'resize',
                     'width' => $args[0],
                     'height' => $args[1]
                  )
               );
               break;
            case(3):
               $params = array(
                  array(
                     'type' => 'smart_crop',
                     'width' => $width = $args[0],
                     'height' => $height = $args[1]
                  )
               );
 
               if(($height && !is_int($height)) || ($width && !is_int($width)) || (!$height && !$width)){
                  $params = false;
                  break;
               }

               if(!is_array($args[2]) && !$args[2]){
                  return($this->get_item($items_id, $args[0], $args[1]));
               }

               if(is_int($args[2]) || is_float($args[2]) || is_object($args[2])){
                  $this->write_log('Invalid parameters given, will not get item', true);
                  return(NULL);
               }

               if($args[2] === true){
                  #this should be defined in a config
                  global $vfolder_default_gravity;
                  $vfolder_default_gravity || ($vfolder_default_gravity = 'Center');
                  $params[0]['gravity'] = $vfolder_default_gravity;
               }elseif(is_string($args[2])){
                  $params[0]['gravity'] = $args[2];
               }elseif(is_array($args[2])){
                  $params = array(
                     $args[2]
                  );

                  if($width && !$params[0]['width']){
                     $params[0]['width'] = $width;
                  }

                  if($height && !$params[0]['height']){
                     $params[0]['height'] = $height;
                  }

                  if(!$params[0]['type']){
                     if($params[0]['x'] || $params[0]['y'] && !$params[0]['coefficient']){ 
                        $params[0]['type'] = 'crop';
                     }else{
                        $params[0]['type'] = 'smart_crop';
                     }
                  }else{
                     $allowed_types = array('crop', 'smart_crop');

                     if(!in_array($params[0]['type'], $allowed_types)){
                        $this->write_log('Invalid parameters given, will not get item', true);
                        return(NULL);
                     }

                     unset($allowed_type);
                  }
               }

               unset($height, $width);
               break;
            default:
               $this->write_log('Unknown parameters, will not get item', true);
               return(NULL);
         }

         unset($args);
      }

      if($params !== NULL && !is_array($params)){
         $this->write_log('Malformed parameters, will not get item', true);
      }

      $query = NULL;

      if(is_array($params)){
         $query = array();

         $query['operations'] = $params;
      }

      if(is_array($extra_params)){
         unset($extra_params['operations']);

         is_array($query) || ($query = array());

         foreach($extra_params as $extra => $value){
            $query[$extra] = $value;
         }
      }

      return($this->make_request('items', $items_id, $query?$query:NULL));
   }

   public function edit_item($items_id = NULL, $params = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not edit item', true);

         return(NULL);
      }

      if(!$params){
         $this->write_log('Nothing given to edit, will not edit item', true);

         return(NULL);
      }

      if(!is_array($params)){
         $this->write_log('Malformed input, will not edit item', true);

         return(NULL);
      }

      return($this->make_request('items/edit', $items_id, json_encode($params)));
   }

   public function get_folder($folders_id = NULL, $limit = NULL){
      if(!$folders_id){
         $this->write_log('No folders_id given, can not get folder', true);

         return(NULL);
      }

      if($limit === false){
         $limit = -1;
      }elseif(is_array($limit)){
         $random = $limit['random'];
         $limit = $limit['limit'];

         if($random && $limit){
            unset($limit);
         }
      }

      $json = array();

      ($limit || $limit === 0 || $limit === '0') && ($json['limit'] = $limit);
      $random && ($json['random'] = true);

      return($this->make_request('folders', $folders_id, json_encode($json)));
   }

   public function edit_folder($folders_id = NULL, $params = NULL){
      if(!$folders_id){
         $this->write_log('No folders_id given, can not edit folder', true);

         return(NULL);
      }

      if(!$params){
         $this->write_log('Nothing given to edit, will not edit item', true);

         return(NULL);
      }

      if(!is_array($params)){
         $this->write_log('Malformed input, will not edit item', true);

         return(NULL);
      }

      return($this->make_request('folders/edit', $folders_id, json_encode($params)));
   }

   public function add_folder($folders_path = NULL, $params = NULL){
      if(!$folders_path){
         $this->write_log('No folders_path given, can not add folder', true);

         return(NULL);
      }

      if(!is_array($params)){
         $this->write_log('Malformed input, can not set fields for new folder, continuing', true);

         $params = NULL;
      }

      return($this->make_request('folders/add', $folders_path, $params?json_encode($params):NULL));
   }

   #max_log_size is to prevent unforeseen 'Allowed memory size of n bytes exhausted' errors
   public function write_log($str = NULL, $error = NULL){
      if(count($this->log) > $this->max_log_size){
         array_shift($this->log);
      }
 
      $this->log[] = $str;

      if($error){
         if(count($this->errors) > $this->max_log_size){
            array_shift($this->errors);
         }

         $this->errors[] = $str;
         $this->last_error = $str;
      }

      return(true);
   }

   public static function is_MongoId($id = NULL){
      return(is_string($id) && preg_match('#[0-9a-f]{24}#', $id));
   }
}

?>
