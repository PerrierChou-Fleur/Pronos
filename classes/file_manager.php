<?php
 #file_manager.php#
  /* class fileManager */
       //__construct
       //fileCreate
       //fileParsing
       //findPosition
       //fileUpdate

class fileManager {

   protected $path;
   protected $position_copy;
   protected $position_paste;
   protected $fileExists;

   public function __construct($path) {
      $this->path = $path;
      $this->position_copy = null;
      $this->position_paste = null;
      if(is_file($this->path)) {
         $this->fileExists = true;
      } else {
         $this->fileExists = false;
      }
   }

   public function fileCreate() {
      $create = false;
      $handle = fopen($this->path, 'x');
      if($handle) {
         $txt = "<?php\n #Config.php#\n\n //installation param\ndefine(\"install\", \"1\");\n\n //errors param /1: user /2: admin /3: developper\ndefine(\"max_error_lvl_show\", \"2\");\n?>";
         if(fwrite($handle, $txt)) {
            $this->fileExists = true;
            $create = true;
         } else {
            throw new userErrorManager(err_cannotwritefile.": ".$this->path, 2);
         }
         fclose($handle);
      } else {
         throw new userErrorManager(err_cannotcreatefile.": ".$this->path, 2);
      }
      if($create) {
         return $create;
      } else {
         throw new userErrorManager(err_cannotcreatefile."!", 1);
      }
   }

   public function fileParsing($handle = null, $type, $name, $value = null) {
      if($this->fileExists) {
         switch($type) {
            case "constant":
               $pattern = '/define\("'.$name.'", "(.*)"\);/';
               break;
            case "comment":
               $pattern = '/ \/\/'.$name.'/';
               break;
            default:
               throw new userErrorManager("Invalid type!", 3);
         }
         $close = false;
         if(!$handle) {
            $handle = fopen($this->path, 'r');
            $close = true;
         }
         if($handle) {
            while(($line = fgets($handle, 4096)) !== false) {
               $this->position_copy = ftell($handle);
               if(preg_match($pattern, $line, $match)) {
                  if($close) {
                     fclose($handle);
                  }
                  return $match;
               } else {
                  $this->position_paste = ftell($handle);
               }
            }
            if($close) {
               fclose($handle);
            }
            return null;
         } else {
            throw new userErrorManager(err_cannotreadfile.": ".$this->path, 2);
         }
         return null;
      } else {
         return false;
      }
   }

   public function findPosition($type, $name, $value = null) {
      $found = false;
      $handle = fopen($this->path, 'r');
      if($handle) {
         $this->position_copy = ftell($handle);
         $this->position_paste = ftell($handle);
         $this->fileParsing($handle, $type, $name, $value);
         if(feof($handle)) {
            fseek($handle, -2, SEEK_END);
            $this->position_copy = ftell($handle);
            $this->position_paste = ftell($handle);
         }
         fclose($handle);
      } else {
         throw new userErrorManager(err_cannotreadfile.": ".$this->path, 2);
      }
      return $found;
   }

   public function fileUpdate($type, $name, $value = null, $last_position = null) {
      $update = false;
      if(!$last_position) {
         $this->findPosition($type, $name, $value);
      } elseif($type == 'comment') {
         $this->position_paste -= 1;
      }
      switch ($type) {
         case "constant":
            $txt = "define(\"".$name."\", \"".$value."\");\n";
            break;
         case "comment":
            $txt = "\n //".$name."\n";
            break;
         default:
            throw new userErrorManager("Invalid type !", 3);
      }
      if($txt) {
         $handle = fopen($this->path, 'r+');
         if($handle) {
            if($last_position && $type == 'comment') {
               fseek($handle, $this->position_paste);
            } else {
               fseek($handle, $this->position_copy);
            }
            $contents = "";
            while(($line = fgets($handle, 4096)) !== false) {
               $contents .= $line;
            }
            if($last_position && $type != 'comment') {
               fseek($handle, $this->position_copy);
            } else {
               fseek($handle, $this->position_paste);
            }
            if(fwrite($handle, $txt.$contents)) {
               $update = true;
            }
            fclose($handle);
         } else {
            throw new userErrorManager(err_cannotmodifyfile.": ".$this->path, 2);
         }
      }
      if($update) {
         return $update;
      } else {
         throw new userErrorManager(err_cannotudaptefile."!", 1);
      }
   }
}
?>