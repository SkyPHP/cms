<?php

array_walk(vf::$deps, function($dep) {
   if (!class_exists($dep)) include 'lib/vfolder/class.'.$dep.'.php';
});

class vf {

   public static $deps = array(/*'vfolder_client',*/ 'vf_gallery_inc', 'vf_gallery', 'vf_uploader', 'vf_slideshow');

   public static $client = NULL;

   public static $filesDomain = NULL;

   public function __construct(){
      throw new Exception('static class');
   }

   public static function config($config = NULL){
      if(!$config){
         return(NULL);
      }

      $config['skip_request'] = true;

      self::$client = new vfolder_client($config);

      return($client->initialized);
   }

   public static function getItem($items_id = NULL, $params = NULL, $height = NULL, $gravity = NULL){
      $operations = array();

      if($params && !is_array($params)){
         $params = array('height' => $height, 'width' => $width = $params, 'crop' => $gravity);
      }

      if($params || ($params = NULL)){
         if($params['composite']){
            $composite = $params['composite'];
            $composite['type'] = 'composite';
         }

         if($params['width'] || $params['height']){
            if($params['crop']){
               if($params['no_resize'] || !($params['width'] && $params['height'])){
                  $operation = array(
                     'type' => 'crop',
                     'width' => $params['width'],
                     'height' => $params['height'],
                     'gravity' => $params['crop']?$params['crop']:'Center'
                  );
               }else{
                  $operation = array(
                     'type' => 'smart_crop',
                     'width' => $params['width'],
                     'height' => $params['height'],
                     'gravity' => $params['crop']?$params['crop']:'Center'
                  );
               }
            }else{
               $operation = array(
                  'type' => 'resize',
                  'height' => $params['height'],
                  'width' => $params['width']
               );
            }
         }

         if($operation || $composite || ($params = NULL)){
            $params = array();
            $operation && ($params[] = $operation);
            $composite && ($params[] = $composite);
         }
      }

      $extra_params = NULL;

      if(self::$filesDomain){
         $extra_params = array('files_domain' => self::$filesDomain);
      }

      if(!$client &&  !self::$client)
         return ;

      $response = (object) self::$client->get_item($items_id, $params);

      if (!is_array($items_id)) return $response;

      if (is_array($response->items)) {
         $response->items = array_map(function($i) {
            return (object) $i;
         }, $response->items);
      }

      return $response;

      // return((object)self::$client->get_item($items_id, $params));
   }

   public static function getFolder($folders_id = NULL, $params = NULL, $extra_params = NULL){

      // check to see if this has been cached as an empty folder
      $mem_key = vf::getEmptyFolderKey($folders_id);
      $folder = mem($mem_key);
      if ($folder && !$_GET['refresh_empty_folders']) {
         return $folder;
      }
      if(!$client)
         return ;
      // it's not a known empty folder (or we are refreshing)
      // get the folder
      $folder = (object) self::$client->get_folder($folders_id, $params, $extra_params);
      // if the folder is empty/error, cache the empty folder
      if ($folder->error) mem($mem_key, $folder, '6 hours');
      return $folder;
   }

   public static function getRandomItemId($folders_id = NULL) {

      $mem_key = 'getRandomItemId:' . $folders_id;
      $no_items_value = 'no items';

      // get the random item from cache
      $items_id = mem($mem_key);

      // if it's not in the cache, get a truly random item
      if (!$items_id) {

         $folder = self::$client->get_folder($folders_id, array('random' => 1, 'limit' => 1));
         if(!(is_array($folder) && is_array($folder['items']) && is_array($folder['items'][0]))){
            return(false);
         }
         $items_id = $folder['items'][0]['_id'];

         if (!$items_id) $items_id = $no_items_value;

         // save the random item to cache for a day
         mem($mem_key, $items_id, '1 day');

      }
      if ($items_id == $no_items_value) $items = null;
      return $items_id;
   }

   public static function getRandomItem($folders_id = NULL, $width = NULL, $height = NULL, $crop = NULL){
       $items = self::getRandomItems($folders_id, 1, $width, $height, $crop);
       return (object) $items[0];
   }

   public static function getRandomItems($folders_id = NULL, $limit = NULL, $width = NULL, $height = NULL, $crop = NULL){
      $request_array = array('random' => true, 'limit' => $limit?$limit:10);

      if(is_array($limit)){
         $request_array = $limit;

         $request_array['random'] || ($request_array['random'] = true);
      }else{
         if(is_array($width)){
            $request_array['operations'] = $width;
         }else{
            if($width){
               $operations = array();

               $operations[] = array('type' => ($crop?'smart_crop':'resize'), 'height' => $height, 'width' => $width);

               $crop && ($operations[0]['gravity'] = $crop !== true?$crop:'Center');

               $request_array['operations'] = $operations;
            }
         }
      }

      $mem_key = 'getRandomItems:' . $folders_id . ':' . md5(serialize($request_array));
      $no_items_value = 'no items';
      $items = mem($mem_key);
      if (!$items) {
         $folder = self::$client->get_folder($folders_id, $request_array);
         $items = $folder['items'];
         if (!$items) $items = $no_items_value;
         mem($mem_key, $items, '1 day');
      }
      if ($items == $no_items_value) $items = null;
      return $items;
   }

   public static function removeItem($items_id = NULL) {
      return ((object) self::$client->remove_item($items_id));
   }

   #this is a pretty specialized and dangerous function, it is best not to allow any syntax variations
   public static function alterItem($items_id = NULL, $operations = NULL){
      return((object) self::$client->alter_item($items_id, $operations));
   }

   public static function hasClient(){
      return(is_object(self::$client) && get_class(self::$client) == 'vfolder_client');
   }

   #returns the files_domain as determined bt the vfolder_client
   public static function getFilesDomain(){
      #if a files_domain can be determined, it is stored here
      static $files_domain = NULL;

      if($files_domain){
         #if we have previously determined files_domain, return it
         return($files_domain);
      }

      if(!self::hasClient()){
         #if there is no valid client, return NULL
         return(NULL);
      }

      #function for extracting the host string from a given url
      $get_host_from_url = function($url){
         $matches = array();
         if(preg_match('#^https*://([^/]+)/#', $url, $matches)){
            return($matches[1]);
         }
         return(NULL);
      };

      if(is_array(self::$client->func_boilerplate)){
         if(is_array(self::$client->func_boilerplate['all']) && self::$client->func_boilerplate['all']['files_domain']){
            #first seek the files_domain in the func_boilerplate of 'all'
            return($files_domain = self::$client->func_boilerplate['all']['files_domain']);
         }else{
            #if 'all' does not contain a files_domain (this would indicate poor config) seek files_domain in any of the func_boilerplates
            foreach(self::$client->func_boilerplate as $boilerplate_config){
               if($boilerplate_config['files_domain']){
                  #return the first files_domain found in func_boilerplate
                  return($files_domain = $boilerplate_config['files_domain']);
               }
            }
         }
      }

      #if there is no func_boilerplate or the func_boilerplate does not have a files_domain, we need to get it from the server_url
      #the current host string in use is not stored in the client, therefore we need to retrieve the current host string from the server_url
      $_files_domain = $get_host_from_url(self::$client->server_url);
      if($_files_domain){
         return($files_domain = $_files_domain);
      }

      #if there is no means of determining the files_domain from the client, return NULL
      return(NULL);
   }

   public static function slideshow($args) {
      return new vf_slideshow($args);
   }

   public static function uploader($args) {
      return new vf_uploader($args);
   }

   public static function gallery($args) {
      return new vf_gallery($args);
   }

   public static function getEmptyFolderKey($folders_id) {
      return 'vf:empty-folder:' . $folders_id;
   }

}
