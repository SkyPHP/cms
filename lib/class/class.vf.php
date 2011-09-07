<?

/*
 var_dump((object)array('aa' => 'bb' , 'cc' => 'dd' , 'ee' => 'ff'));


die();

*/

array_walk(vf::$deps, function($dep) {
   if (!class_exists($dep)) include 'lib/vfolder/class.'.$dep.'.php';
});

class vf {

   public static $deps = array('vfolder_client', 'vf_gallery_inc', 'vf_gallery', 'vf_uploader', 'vf_slideshow'); 

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

   public static function getItem($items_id = NULL, $params = NULL, $width = NULL, $gravity = NULL){
      $operations = array();
 
      if($params && !is_array($params)){
         $params = array('height' => $height = $params, 'width' => $width, 'crop' => $gravity);
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

      return((object)self::$client->get_item($items_id, $params));
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
      return((object) self::$client->get_item(self::getRandomItemId($folders_id), $width, $height, $crop));
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
