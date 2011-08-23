<?

if (!class_exists('vfolder_client')) {
   include 'lib/vfolder/class.vfolder_client.php';
   include 'lib/vfolder/class.vf_gallery_inc.php';
   include 'lib/vfolder/class.vf_gallery.php';
   include 'lib/vfolder/class.vf_uploader.php';
   include 'lib/vfolder/class.vf_slideshow.php';
}

class vf{
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

   public static function getItem($items_id = NULL, $params = NULL){
      $operations = array();
 
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
      $folder = self::$client->get_folder($folders_id, array('random' => 1));
      $items_id = $folder['items'][0]['_id'];
      return($items_id);
   }

   public static function getRandomItem($folders_id = NULL, $width = NULL, $height = NULL, $crop = NULL){
      return((object)self::$client->get_item(self::getRandomItemId($folders_id), $width, $height, $crop));
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
