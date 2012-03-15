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
   public $server_host = NULL;
   public $server_path = NULL;

   public $log = NULL;
   public $errors = NULL;

   public $max_log_size = NULL;

   public $func_boilerplate = NULL;

   public $MAX_FILE_SIZE = NULL;

   public $default_gravity = NULL;

   public $memcache = NULL;
   public $memcache_key_prefix = NULL;
   public $memcache_refresh = NULL;
   public $memcache_cache_error = NULL;
   
   public $config_memcache_signature = NULL;

   public $initialized = NULL;

   public $debug = NULL;
   public $debug_callback = NULL;

   public $retries = NULL;
   public $retry_error_codes = NULL;

   public $start_time = NULL;

   public $set_next_host_on_failure = NULL;
 
   public function __construct($params = NULL){
      global $disable_vf;
      if ($disable_vf) {
         $this->initialized = true;
         return;
      }

      $this->start_time = microtime(true);
 
      $this->log = array();
      $this->errors = array();

      $default_max_log_size = 50;
      $this->max_log_size = $default_max_log_size;
 
      $this->write_log('config: ' . var_export($params, true));

      $skip_request = true;

      if(func_num_args() > 1){
         $args = func_get_args(); 

         $username = array_shift($args);
         $password = array_shift($args);
         $server_url = array_shift($args);
      }else{
         #the following are the base line configs
         $username = $params['username'];
         $username || ($username = $params['accounts_id']);
         $password = $params['password'];
 
         #the following are extra configs

         #these values need to be set to defaults if they are not set here
         $this->MAX_FILE_SIZE = $params['MAX_FILE_SIZE'];
         $this->default_gravity = $params['default_gravity'];
         $this->retries = $params['retries'];
         $this->retry_error_codes = $params['retry_error_codes'];
         $this->server_url = $params['server_url']; #this config option is depracated, use 'server_host' and 'server_path' instead.  The variable itself however is not deprecated
         $this->set_next_host_on_failure = array_key_exists('set_next_host_on_failure', $params)?$params['set_next_host_on_failure']:true;
         $this->config_memcache_signature = $params['config_memcache_signature'];

         #all following values do not need defaults set if not here
         $this->secure = $params['secure'];

         $params['server_host'] && ($this->server_host = $params['server_host']);
         $params['server_path'] && ($this->server_path = $params['server_path']);

         $files_domain = $params['files_domain'];

         $this->memcache = &$params['memcache'];
         ($this->memcache_key_prefix = $params['memcache_key_prefix']) || ($this->memcache_key_prefix = 'vfolder:');
         $this->memcache_refresh = (bool)$params['memcache_refresh'];
         $this->memcache_debug = (bool)$params['memcache_debug'];         
         ($this->memcache_cache_error = false) || $params['memcache_cache_error'] && ($this->memcache_cache_error = true);

         $this->debug = (bool)$params['debug'];
         $this->debug_callback = $params['debug_callback'];

         $return_log = $params['return_log'];
         $params['test_request'] && ($skip_request = false );

         $params['max_log_size'] && ($this->max_log_size = $params['max_log_size']);
      }

      unset($default_max_log_size);

      if(!($username && $password)){
         $this->write_log('Username or Password not given, can not authenticate', true);

         return(NULL);
      }

      $this->func_boilerplate = array();

      foreach(array(
         'all',
         'items', 'items/add', 'items/edit', 'items/remove', 'items/alter', 'items/move',
         'folders', 'folders/add', 'folders/edit'
      ) as $i){
         $this->func_boilerplate[$i] = array();
      }
      unset($i);

      if($files_domain){
         $this->func_boilerplate['all']['files_domain'] = $files_domain;
      }
      
      if($return_log){
         $this->func_boilerplate['all']['return_log'] = true;
      }

      if($this->server_host){
         is_array($this->server_host)?shuffle($this->server_host) && $this->set_next_server_host(true):$this->set_server_url($this->server_host . '/' . ltrim($this->server_path, '/'));
      }else{
         $this->set_server_url('volder.net/v1');
      }

      #these configs can affect item retrieval, and memcache needs to be aware of this
      $this->config_memcache_signature || ($this->config_memcache_signature = md5(var_export(array($username, $this->server_url, $files_domain, $return_log), true)));

      if(self::is_MongoId($username)){
         $this->accounts_id = $username;
      }else{
         $this->username = $username;
      }

      $this->password = $password;

      $this->MAX_FILE_SIZE || ($this->MAX_FILE_SIZE = 300000000);
      $this->default_gravity || ($this->default_gravity = 'Center');
      $this->retries || ($this->retries = 5);
      $this->retry_error_codes || ($this->retry_error_codes = array(2));
      #error code 2 usually signifies php/curl bug where POST params are not sent before making a curl request
      #the problem sometimes resolves itself after a few retries, which makes error code 2 desirable possibly to retry
      #it is not recomended that retries are attempted for other error codes

      $this->initialized = true;
  
      if(!$skip_request){ 
         $request = $this->make_request();
    
         if(!(is_array($request) && $request['authenticated'])){
            $this->write_log('Authentication test failed', true);
         }
      }

      return(true);
   }

   public function set_server_url($url = NULL){
      $url && ($this->server_url = $url);
      $server_url = &$this->server_url; #simply for convinience

      if(!$server_url){
         $this->write_log('No input given, will not set server url', true);
 
         return(NULL);
      }

      if($this->secure){
         if(strpos($this->server_url, 'http://') === 0){
            $server_url = substr($server_url, 7);
            $server_url = "https://$server_url";
         }elseif(strpos($server_url, 'https://') !== 0){
            $server_url = "https://$server_url";
         }
      }else{
         if(strpos($server_url, 'http://') !== 0){
            if(strpos($server_url, 'https://') === 0){
               $this->$secure = true;
            }else{
               $server_url = "http://$server_url";
            }
         }
      }

      $this->write_log("\$server_url set to {$this->server_url}");

      return($this->server_url);  #do not want to return a reference
   }

   #if $die evaluates true, die will be called if this function fails
   public function set_next_server_host($die = NULL){
      if($this->server_host && is_array($this->server_host) && ($server_host = array_shift($this->server_host))){
         $old_server_url = $this->server_url;
         $this->set_server_url($server_host . '/' . ltrim($this->server_path, '/'));

         $this->write_log("\$server_url changed from $old_server_url to {$this->server_url}");

         return($this->server_url);
      }else{
         $this->write_log($error = '$server_host is not a proper array', true);

         if($die){die($error);} 
      }

      return(NULL);
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
   protected function generate_post($json = NULL, $func = NULL){
      $post = array();

      if(is_array($json) && array_key_exists('json', $json)){
         $post = $json;
      }else{
         $post['json'] = $json;
      }

      if(is_array($this->func_boilerplate)){
         is_array($post['json']) || (is_string($post['json']) && is_array($post['json'] = json_decode($post['json'], true))) || ($post['json'] = array()) && true;
 
         if(is_array($this->func_boilerplate['all'])){
            foreach($this->func_boilerplate['all'] as $key => $value){
               $post['json'][$key] || ($post['json'][$key] = $value);
            }
            unset($key, $value);
         }

         if(is_array($this->func_boilerplate[$func])){
            foreach($this->func_boilerplate[$func] as $key => $value){
               $post['json'][$key] || ($post['json'][$key] = $value);
            }
            unset($key, $value);
         }
     }

     return($post);
   }

   protected function memcache_delete_keys($keys_key = NULL){
      $this->write_log('memcache_delete_keys');
  
      if(!($memcache = &$this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!$keys_key){
         $this->write_log('No keys given, can not delete keys', true);
      }

      $this->write_log('memcached read: ' . $keys_key);

      if($this->memcache_debug){
         ?>read: <?=$keys_key?> <?     
      }

      $keys = $memcache->get($keys_key);

      $this->write_log('memcached keys to delete: ' . var_export($keys, true));

      $count = 0;

      foreach(explode("\n", $keys) as $_count => $key){
         if(!$key){
            continue;
         }

         $this->write_log('memcached delete: ' . $key);

         if($this->memcache_debug){
            ?>delete: <?=$key?> <?     
         }

         #the memcache plugin is notoriously buggy, delete is a particularly problematic function
         #we should log the status of each delete so we can track down memcache problems right away
         #passing NULL to delete will often work around delete function bugs
         if($memcache->delete($key, NULL)){
            $count++;
            $this->write_log('successfully deleted key: ' . $key);
         }else{
            $this->write_log('failed to delete key: ' . $key);
         }
      }
      $_count++;

      if($count == $_count){
         $this->write_log("successfully deleted all $count keys");

         $this->write_log('memcached delete: ' . $keys_key);

         if($this->memcache_debug){
           ?>delete: <?=$keys_key?> <?     
         } 
 
         if($memcache->delete($keys_key, NULL)){
            $count++;
            $this->write_log('successfully deleted key: ' . $keys_key);
         }else{
            $this->write_log('failed to delete key: ' . $keys_key);
         }
      }else{
         $this->write_log("failed to delete all $_count keys, only $count deleted");
      }

      return($count);
   }

   protected function memcache_add_key($keys_key = NULL, $key = NULL, $obj = NULL){
      $this->write_log("memcache_add_key $keys_key : $key");      

      if(!($memcache = &$this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!($keys_key && $key && $obj)){
         $this->write_log('Missing parameters, will not add memcached key', true);

         return(NULL);
      }

      if(is_array($obj) && !$obj['success'] && !$this->memcache_cache_error){
         $this->write_log('Return object indicates failures, will not store item in memcached');

         return(NULL);
      }

      $this->write_log('memcached read: ' . $keys_key);

      if($this->memcache_debug){
         ?>read: <?=$keys_key?> <?      
      }

      $keys = $memcache->get($keys_key);

      $keys_arr = explode("\n", $keys);

      $this->write_log('keys: ' . var_export($keys, true));
   
      if(!in_array($key, $keys_arr)){
         $this->write_log('adding memcache key');
         $keys .= ($keys?"\n":'') . $key;

         $this->write_log('memcached write: ' . $keys_key . ' : ' . var_export($keys, true));

         if($this->memcache_debug){
            ?>write: <?=$keys_key?> <?
         }

         ($memcache->replace($keys_key, $keys)) || ($memcache->set($keys_key, $keys));
      }else{
         $this->write_log('key already exists');
      }

      $this->write_log('memcached write: ' . $key);# . ' : ' . var_export($obj, true));

      ($memcache->replace($key, $obj)) || ($memcache->set($key, $obj));

      if($this->memcache_debug){
         ?>write: <?=$key?> <?
      }

      return;
   }

   protected function memcache_before_request($func = NULL, $id = NULL, $post = NULL){
      $this->write_log('memcache_before_request');

      if(!($memcache = &$this->memcache)){
         $this->write_log('No memcache object defined, can not interface with memcached', true);

         return(NULL);
      }

      if(!$func || !$id){
         $this->write_log('No function or id given, will not interface with memcached', true);

         return(NULL);
      }

      is_array($id) && ($id = implode(',', $id));

      strpos($id, ',') && ($id = md5($id)); #has $id if it appears to be a csv

      $key_prefix = $this->memcache_key_prefix . $id;
      $key_suffix = '_' . md5(var_export($post, true)) . '_' . $this->config_memcache_signature;
      $key = $key_prefix . $key_suffix;

      $keys_key = $key_prefix . '_keys';

      switch($func){
         case('folders'):
         case('items'):
            if(!$this->memcache_refresh){
               $this->write_log('memcached read: ' . $key);

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
      $this->write_log('memcache_after_request');

      if(!($memcache = &$this->memcache)){
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
         if(!$this->memcache_cache_error){
            $this->write_log('Response given indicated failure, will not interface with memcached', true);
            return(NULL);
         }
      }

      if(strpos($func, 'folders') === 0){
         $id = $folders_id = $response['folders_id']; 
         $folders_path = $response['folders_path'];
      }

      strpos($id, ',') && ($id = md5($id)); #has $id if it appears to be a csv

      $key_prefix = $this->memcache_key_prefix . $id;
      $key_suffix = '_' . md5(var_export($post, true)) . '_'. $this->config_memcache_signature;
      $key || ($key = $key_prefix . $key_suffix);

      $keys_key = $key_prefix . '_keys';

      switch($func){
         case('folders'):
            $this->memcache_add_key($keys_key, $this->memcache_key_prefix . $folders_path . $key_suffix, $response);
         case('items'):
            $this->memcache_add_key($keys_key, $key, $response);
            break;
         case('folders/edit'):
         case('items/edit'):
            $this->memcache_delete_keys($keys_key);
            break;
         case('items/move'):
            $this->memcache_delete_keys($this->memcache_key_prefix . $response['old_folders_id'] . "_keys");
         case('items/remove'):
            $this->memcache_delete_keys($keys_key);
         case('items/add'):
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
      global $disable_vf;
      if ($disable_vf) return;

      $original_args = func_get_args();

      for($retry = 0; $retry <= $this->retries; $retry++){
         if($retry){
            $func = $original_args[0];
            $id = $original_args[1];
            $json = $original_args[2];
            $params = $original_args[3];
         }

         if(!(($this->username || $this->accounts_id) && $this->password)){
            $this->write_log('Username or password not given, can not authenticate', true);

            return(NULL);
         }

         $this->write_log("Attempting to make request $func : $id : " . var_export($json, true));

         if(is_array($params)){
            $return_text = $params['return_text'];
            $CURLOPT_HTTPHEADER = $params['CURLOPT_HTTPHEADER'];
            $skip_memcache = $params['skip_memcache'];
            $skip_memcache_before = ($this->memcache_refresh || $params['skip_memcache_before'] || $params['refresh_memcached']);
            $skip_memcache_after = $params['skip_memcache_after']; #this is not recomended
            $memcache_key = $params['memcache_key'];
         }

         $_post = $post = $this->generate_post($json, $func);

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
         ((is_array($id) && ($id = implode(',', $id))) || $id) && ($post['id'] = $id);

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

         $this->write_log('request POST: ' . var_export($post, true));

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
            #this type of failure is usually not temporary to a host
            #switching servers can potentially resolve this type of error
            #however, if the cause of the failure is not spcific to the server (ie: every curl_exec always fails)
            #then it will not hurt to attempt to switch servers in vain

            $this->write_log('curl_exec failed, ' . (($error = curl_error($curl_handle))?$error:'unknown error') . ', can not make request', true);

            if($this->set_next_host_on_failure){
               $this->write_log('Attempting to change server hosts...');

               if($this->set_next_server_host()){
                  $this->write_log('Retrying request...');
                  
                  curl_close($curl_handle);
                  return(call_user_func_array(array($this, 'make_request'), $original_args));
               }
            }

            curl_close($curl_handle);
            return(NULL);
         }

         $this->write_log('Successfully sent request');

         curl_close($curl_handle);
 
         $end_time = microtime(true);

         if(!($decoded = json_decode($response, true))){
            #this type of error sometimes indicates a problem local to a specific server
            #switching servers can potentially resolve this type of error

            $this->write_log('Server response was not a valid json string', true);
            $this->write_log("Response: " . var_export($response, true), true);
            
            if($this->set_next_host_on_failure){
               $this->write_log('Attempting to change server hosts...');

               if($this->set_next_server_host()){
                  $this->write_log('Retrying request...');
                  
                  return(call_user_func_array(array($this, 'make_request'), $original_args));
               }
            }
         }else{
            $this->write_log('vf_server: ' . $decoded['vf_server']);

            is_array($decoded) && (
               $decoded['_client'] = array(
                  'duration' => $end_time - $start_time
               )
            );
         }

         #if ((returned error code is in retry_error_codes) or (retry_error_codes is true and an error has occurred)) and we want to retry and we haven't already retried retries many times
         if(((is_array($decoded) && $decoded['error'] && ($this->retry_error_codes === true || is_array($this->retry_error_codes) && in_array($decoded['error_code'], $this->retry_error_codes))) || (!is_array($decoded) && $this->retry_error_codes === true)) && $this->retries && $retry < $this->retries){
            $this->write_log('Server response indicated an error that we retry for (' . (is_array($decoded) && $decoded['error_code']?$decoded['error_code']:'true') . ').  ' . ($retry?"This was retry number $retry":"This was the first attempt") . '.', true);
   
            if($this->debug){
               if(is_callable($this->debug_callback)){
                  $this->debug_callback($this->last_error);
               }else{
                  print_r($this->last_error);
               }
            }

            $sleep_time = ($retry + 1) * 100;
            $this->write_log("Sleeping $sleep_time microseconds...");
            usleep($sleep_time);
            unset($sleep_time);

            continue;
         }

         if($this->memcache && $decoded && !($skip_memcache || $skip_memcache_after)){
            $this->memcache_after_request($func, $id, $_post, $decoded, $memcache_key);
         }

         if($this->debug){
            if(is_callable($this->debug_callback)){
               $this->debug_callback($return_text?$response:($decoded?$decoded:$response));
            }else{
               print_r($return_text?$response:($decoded?$decoded:$response));
            }
         }

         return($this->write_log($return_text?$response:($decoded?$decoded:$response)));	
      }

      #execution should never reach this point
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

      $post['json'] = $json;

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

      return($this->make_request('items/alter', $items_id, $params, array('skip_memcache_before' => true)));
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
 
      /*
      if($memcache = $this->memcache){
         $memcache_key = ($this->memcache_key_prefix . md5(var_export(func_get_args(), true))) . '_' . $this->config_memcache_signature;

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
      */

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

      return($this->make_request('items', $items_id, ($query?$query:NULL), $extra_params));
   }

   public function move_item($items_id = NULL, $params = NULL){
      if(!$items_id){
         $this->write_log('No items_id given, can not move item', true);

         return(NULL);
      }

      if(!$params){
         $this->write_log('No folders identifier given, will not move item', true);

         return(NULL);
      }else{
         if(!is_array($params)){
            $folders_identifier = $params;
            $params = array('folder' => $folders_identifier);
         }
      }

      if(!is_array($params)){
         $this->write_log('Malformed input, will not move item', true);

         return(NULL);
      }

      return($this->make_request('items/move', $items_id, json_encode($params), array('skip_memcache_before' => true)));
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

      /*
      if($memcache = $this->memcache){
         $memcache_key = ($this->memcache_key_prefix . md5(var_export(func_get_args(), true))) . '_' . $this->config_memcache_signature;

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
      */
   
      if(!is_array($params)){
         $limit = $params;
         unset($params);

         $json = array();

         ($limit || $limit === 0 || $limit === '0') && ($json['limit'] = $limit);
      }else{
         $json = $params;
      }

      return($this->make_request('folders', $folders_id, json_encode($json), $extra_params));
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
 
      $time_now = microtime(true);

      $this->log[] = number_format($time_now - $this->start_time, 3) . ": " . ((is_string($str) || is_numeric($str) || is_bool($str))?$str:var_export($str, true));

      if($error){
         if(count($this->errors) > $this->max_log_size){
            array_shift($this->errors);
         }

         $this->errors[] = $str;
         $this->last_error = $str;
      }

      return($str);
   }

   public static function is_MongoId($id = NULL){
      return(is_string($id) && preg_match('#[0-9a-f]{24}#', $id));
   }

   #will return an integer regardless of the type of MongoId given
   #return value only accurate if a proper integer-mapped MongoId is given
   #otherwise return value is meaningless
   #typically, this value should fit into a 4-byte integer
   #values greater than 0xffffffff usually indicate meaningless data, although it depends on the size of your database
   public static function MongoId_to_int_id($id = NULL){
      if(!self::is_MongoId($id)){
         return(NULL);
      }

      return((int) hexdec(substr($id, 8)));
   }
}

?>
