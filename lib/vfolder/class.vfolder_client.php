<?

/*
 * Warning about using memcache features!
 *
 * if you are using this client from more than one location
 * all clients *should* be using the same memcached server
 * otherwise there is no guarantee that cached vfolder server responses are up-to-date
 *
 * Note about memcache features
 *
 * get_item and get_folder store to memcache differently than make_request
 * this is optimize speed for common requests
 * in order to use different key nomenclature with this class, in a class extension or otherwise
 * you must pass the custom key to as a the 'memcache_key' parameter to make_request 
 * it is advised that you also pass 'skip_memcache_before' as true, although this is not required
 * setting 'skip_memcache_after' to true will cause keys to not be deleted when they become out of date, this is not advised
 *
 */

#vfolder client class
class vfolder_client{
   public $username = NULL;
   protected $password = NULL;

   public $accounts_id = NULL;
 
   public $secure = NULL;
   public $server_url = NULL;

   public $log = NULL;
   public $errors = NULL;

   public $max_log_size = NULL;

   public $func_boilerplate = NULL;

   public $MAX_FILE_SIZE = NULL;

   public $default_gravity = NULL;

   public $memcache = NULL;
   public $memcache_key_prefix = NULL;
   public $memcache_refresh = NULL;

   public $initialized = NULL;

   public function __construct($params = NULL){
      $this->log = array();
      $this->errors = array();

      $this->max_log_size = 50;
 
      if(func_num_args() > 1){
         $args = func_get_args(); 

         $username = array_shift($args);
         $password = array_shift($args);
         $server_url = array_shift($args);
      }else{
         $username = $params['username'];

         $username || ($username = $params['accounts_id']);

         $password = $params['password'];

         ($server_url = $params['server_url']) || ($server_url = 'vfolder.net/v1');

         $secure = $params['secure'];
         $files_domain = $params['files_domain'];

         ($this->max_log_size = $params['max_log_size']) || ($this->max_log_size = 50);

         ($this->MAX_FILE_SIZE = $params['MAX_FILE_SIZE']) || ($this->MAX_FILE_SIZE = 300000000);

         ($this->default_gravity = $params['default_gravity']) || ($this->default_gravity = 'Center');

         $this->memcache = &$params['memcache'];

         ($this->memcache_key_prefix = $params['memcache_key_prefix']) || ($this->memcache_key_prefix = 'vfolder:');
         $this->memcache_refresh = (bool)$params['memcache_refresh'];
         $this->memcache_debug = (bool)$params['memcache_debug'];

         $skip_request = $params['skip_request'];
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
  
      if(!$skip_request){ 
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

   #only generates the 'json' parameter portion of the post, authentication and 'func' and 'id' fields not set
   protected function generate_post($json = NULL){
      if(!$json){
         return(NULL);
      }

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

      return($post);
   }

   protected function memcache_delete_keys($keys_key = NULL){
      if(!($memcache = $this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!$keys_key){
         $this->write_log('No keys given, can not delete keys', true);
      }

      if($this->memcache_debug){
         ?>read: <?=$keys_key?> <?     
      }

      $keys = $memcache->get($keys_key);

      foreach(explode("\n", $keys) as $count=>$key){
         if(!$key){
            continue;
         }

         if($this->memcache_debug){
            ?>delete: <?=$key?> <?     
         }

         $memcache->delete($key);      
      }

      if($this->memcache_debug){
         ?>delete: <?=$keys_key?> <?     
      }

      $memcache->delete($keys_key);

      return($count);
   }

   protected function memcache_add_key($keys_key = NULL, $key = NULL, $obj = NULL){
      if(!($memcache = $this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!($keys_key && $key && $obj)){
         $this->write_log('Missing parameters, will not add memcached key', true);

         return(NULL);
      }

      if(is_array($obj) && !$obj['success']){
         $this->write_log('Return object indicates failures, will not store item in memcached');

         return(NULL);
      }

      if($this->memcache_debug){
         ?>read: <?=$keys_key?> <?      
      }

      $keys = $memcache->get($keys_key);

      $keys_arr = explode("\n", $keys);

      $found_key = false;
      foreach($keys_arr as $_key){
         if($key == $_key){
            $found_key = true;
            break;
         }
      }
      unset($_key);

      if(!$found_key){
         $keys .= ($keys?"\n":'') . $key;
      }

      ($memcache->replace($keys_key, $keys)) || ($memcache->set($keys_key, $keys));
      ($memcache->replace($key, $obj)) || ($memcache->set($key, $obj));

      if($this->memcache_debug){
         ?>write: <?=$keys_key?> <?
         ?>write: <?=$key?> <?
      }

      return;
   }

   protected function memcache_before_request($func = NULL, $id = NULL, $post = NULL){
      if(!($memcache = $this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!$func || !$id){
         $this->write_log('No function or id given, will not interface with memcached', true);

         return(NULL);
      }

      $key_prefix = $this->memcache_key_prefix . $id;
      $key_suffix = "_" . md5(var_export($post, true));
      $key = $key_prefix . $key_suffix;

      $keys_key = $key_prefix . "_keys";

      switch($func){
         case('folders'):
         case('items'):
            if(!$this->memcache_refresh){
               if($this->memcache_debug){
                  ?>read: <?=$key?> <?     
               }

               ($value = $memcache->get($key)) || ($value = NULL);
            }else{
               $value = NULL;
            }
            break;
         default:
            return(NULL);
      }

      return($value);
   }

   protected function memcache_after_request($func = NULL, $id = NULL, $post = NULL, $response = NULL, $key = NULL){
      if(!($memcache = $this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!($func && ($func == 'items/add' || $func == 'folders/add' || $id))){
         $this->write_log('No function given, will not interface with memcached', true);

         return(NULL);
      }

      if(!$response){
         $this->write_log('No response given, will not interface with memcached', true);

         return(NULL);
      }

      if(!is_array($response)){
         $this->write_log('Invalid response given, will not interface with memcached', true);

         return(NULL);
      }

      if(!$response['success']){
         $this->write_log('Response given indicated failure, will not interface with memcached', true);
      }

      $key_prefix = $this->memcache_key_prefix . $id;
      $key_suffix = "_" . md5(var_export($post, true));
      $key || ($key = $key_prefix . $key_suffix);

      $keys_key = $key_prefix . "_keys";

      switch($func){
         case('items'):
         case('folders'):
            $this->memcache_add_key($keys_key, $key, $response);
            break;
         case('folders/edit'):
         case('items/edit'):
            $this->memcache_delete_keys($keys_key);
            break;
         case('items/add'):
         case('items/remove'):
            $this->memcache_delete_keys($this->memcache_key_prefix . $response['folders_id'] . "_keys");
            break;
         case('folders/add'):
            if($response['parent_folder']){
               $this->memcache_delete_keys($this->memcache_key_prefix . $response['parent_folder']['_id'] . "_keys");
            }
            break;
         default:
            return(NULL);
      }

      return(true);
   }

   #$params are params to the make_request call, not to the server
   public function make_request($func = NULL, $id = NULL, $json = NULL, $params = NULL){
      if(!(($this->username || $this->accounts_id) && $this->password)){
         $this->write_log('Username or password not given, can not authenticate', true);

         return(NULL);
      }

      $this->write_log('Attempting to make request');

      if(is_array($params)){
         $return_text = $params['return_text'];
         $CURLOPT_HTTPHEADER = $params['CURLOPT_HTTPHEADER'];
         $skip_memcache = $params['skip_memcache'];
         $skip_memcache_before = ($this->memcache_refresh || $params['skip_memcache_before'] || $params['refresh_memcached']);
         $skip_memcache_after = $params['skip_memcache_after']; #this is not recomended
         $memcache_key = $params['memcache_key'];
      }

      $_post = $post = $this->generate_post($json);

      if($this->memcache && !($skip_memcache || $skip_memcache_before)){
         if($cache = $this->memcache_before_request($func, $id, $post)){
            if(is_array($cache)){
               if(!is_array($cache['_client'])){
                  $cache['_client'] = array();
               }
 
               $cache['_client']['memcached'] = true;
            }

            return($cache);
         }
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

      if($this->memcache && $decoded && !($skip_memcache || $skip_memcache_after)){
         $this->memcache_after_request($func, $id, $_post, $decoded, $memcache_key);
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

      if(filesize($file) > $this->MAX_FILE_SIZE){
         $this->write_log("File '$file' exceeds MAX_FILE_SIZE of {$this->MAX_FILE_SIZE}, will not upload to server", true);

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

      return($this->make_request('items/add', NULL, $post, array('CURLOPT_HTTPHEADER' => array('Content-type: multipart/form-data'), 'skip_memcache_before' => true)));
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

      return($this->upload_to_server($source, $json, array('skip_memcache_before' => true)));      
   }

   public function alter_item($items_id = NULL, $params = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not alter item', true);

         return(NULL);
      }

      if(!$params){
         $this->write_log('No parameters given, will not alter item', true);

         return(NULL);
      }

      return($this->make_request('items/alter', $items_id, NULL, array('skip_memcache_before' => true)));
   }

   public function remove_item($items_id = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not remove item', true);

         return(NULL);
      }
    
      return($this->make_request('items/remove', $items_id, NULL, array('skip_memcache_before' => true)));
   }

   public function get_item($items_id = NULL, $params = NULL, $extra_params = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not get item', true);

         return(NULL);
      }
 
      if($memcache = $this->memcache){
         $memcache_key = ($this->memcache_key_prefix . md5(var_export(func_get_args(), true)));

         if(!($this->memcache_refresh || (is_array($extra_params) && $extra_params['refresh_memcached']))){ 
            if($this->memcache_debug){
               ?>read: <?=$memcache_key?> <?
            }

            if($response = $memcache->get($memcache_key)){
               is_array($response['_client']) || ($response['_client'] = array());
               $response['_client']['memcached'] = true;
               return($response);
            }
         }
      }

      #this is to account for the alternate syntaxes for this function
      if(func_num_args() > 1){
         $_args = func_get_args();

         while(count($_args) && !($element = array_pop($_args))){
         }

         if($element){
            $_args[] = $element;
         }

         $args = $_args;

         unset($element, $_args);
      }
 
      #this is to account for the alternate syntaxes for this function
      if($args && count($args) > 1 && !is_array($params) && !is_array($extra_params)){
         $args = func_get_args();
         array_shift($args);

         unset($element, $_args);

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
               if(!(($args[0] === NULL || ( $args[0] = (int) $args[0])) && ($args[1] === NULL || ($args[1] = (int) $args[1])))){
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
 
               if(($height && !($height = (int) $height)) || ($width && !($width = (int) $width)) || (!$height && !$width)){
                  $this->write_log('Invalid parameters given, will not get item', true);
                  return(NULL);
               }

               if(!is_array($args[2]) && !$args[2]){
                  return($this->get_item($items_id, $args[0], $args[1]));
               }

               if(is_int($args[2]) || is_float($args[2]) || is_object($args[2])){
                  $this->write_log('Invalid parameters given, will not get item', true);
                  return(NULL);
               }

               if($args[2] === true){
                  $params[0]['gravity'] = $this->default_gravity;
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
         is_array($query) || ($query = array());

         foreach($extra_params as $extra => $value){
            $query[$extra] = $value;
         }
      }

      return($this->make_request('items', $items_id, ($query?$query:NULL), array('skip_memcache_before' => true, 'memcache_key' => $memcache_key)));
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

      return($this->make_request('items/edit', $items_id, json_encode($params), array('skip_memcache_before' => true)));
   }

   public function get_folder($folders_id = NULL, $params = NULL, $extra_params = NULL){
      if(!$folders_id){
         $this->write_log('No folders_id given, can not get folder', true);

         return(NULL);
      }

      if($memcache = $this->memcache){
         $memcache_key = ($this->memcache_key_prefix . md5(var_export(func_get_args(), true)));

          if(!($this->memcache_refresh || (is_array($extra_params) && $extra_params['refresh_memcached']))){
            if($this->memcache_debug){
               ?>read: <?=$memcache_key?> <?
            }

            if($response = $memcache->get($memcache_key)){
               is_array($response['_client']) || ($response['_client'] = array());
               $response['_client']['memcached'] = true;
               return($response);
            }
         }
      }

      if(!is_array($params)){
         $limit = $params;
         unset($params);

         $json = array();

         ($limit || $limit === 0 || $limit === '0') && ($json['limit'] = $limit);
      }else{
         $json = $params;
      }

      return($this->make_request('folders', $folders_id, json_encode($json), array('skip_memcache_before' => true, 'memcache_key' => $memcache_key)));
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

      return($this->make_request('folders/edit', $folders_id, json_encode($params), array('skip_memcache_before' => true)));
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

      return($this->make_request('folders/add', $folders_path, $params?json_encode($params):NULL, array('skip_memcache_before' => true)));
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
