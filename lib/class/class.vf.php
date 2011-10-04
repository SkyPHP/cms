<?

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

   public static function getFolder($folders_id = NULL, $params = NULL){
      return((object)self::$client->get_folder($folders_id, $params));
   }

   public static function getRandomItemId($folders_id = NULL){
      $folder = self::$client->get_folder($folders_id, array('random' => 1, 'limit' => 1));

      if(!(is_array($folder) && is_array($folder['items']) && is_array($folder['items'][0]))){ 
         return(false);
      }

      $items_id = $folder['items'][0]['_id'];
      return($items_id);
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

      $folder = self::$client->get_folder($folders_id, $request_array);
      return $folder['items'];
   }

   public static function removeItem($items_id = NULL) {
      return ((object) self::$client->remove_item($items_id));
   }
 
   #this is a pretty specialized and dangerous function, it is best not to allow any syntax variations
   public static function alterItem($items_id = NULL, $operations = NULL){
      return((object) self::$client->alter_item($items_id, $operations));
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

}

?>
