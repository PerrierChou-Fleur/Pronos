<?php
 #general.php#
   /* class userErrorManager */
       //__construct
       //createMessage
       //getMyMessage
       //createErrorView

   /* class userManager */
       //__construct
       //checkIsLoggedIn
       //createUser

   /* class languageManager */
       //__construct
       //getFileName
       //getLanguages
       //defineLanguage

   /* class datetimeManager */
       //__construct

   /* class postManager */
       //getPost

   /* class cookieManager */
       //

   /* class viewManager */
       //createView

class userErrorManager extends Exception {

   protected $message;
   protected $lvl;
   public $err_message;
   
   public function __construct($message, $lvl) {
      $this->message = $message;
      $this->lvl = $lvl;
      $this->err_message = "";
      
   }

   protected function createMessage() {
      switch ($this->lvl) {
         case 1:
            $this->err_message = $this->message;
            break;
         case 2:
            $this->err_message = "Error of severity 2: ".$this->message;
            break;
         case 3:
            $this->err_message = "Error of severity 3: ".$this->message;
            break;
         default:
            $this->err_message = $this->message;
      }
      return $this->err_message;
   }

   public function getMyMessage() {
      if(!defined('max_error_lvl_show')) {
         echo $this->createMessage();
         return null;
      } elseif($this->lvl <= (int)max_error_lvl_show) {
         echo $this->createMessage();
         return null;
      } else {
         return err_default_err;
      }
   }

   public function createErrorView() {
      if(!defined('max_error_lvl_show')) {
         echo $this->createMessage();
         return null;
      } elseif($this->lvl <= (int)max_error_lvl_show) {
         $lang = new languageManager();
         $lang->defineLanguage();
         try {
            $view = new viewManager();
            $view->variable = $this->createMessage();
            $view->head_path = 'head';
            $view->head_title = err_err;
            $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>'')];
            $view->head_js = array('path'=>[''], 'init'=>['']);
            $view->createView('header', 'error', 'footer');
         } catch (userErrorManager $e) {
            echo $this->createMessage();
         }
         return null;
      } else {
         echo err_default_err;
         return null;
      } 
   }
}

class userManager {

   public $userInfos;

   public function __construct() {
      $this->userInfos = null;
   }

   public function checkIsLoggedIn() {
      if(isset($_SESSION['user'])) {
         $this->userInfos = $_SESSION['user'];
         return true;
      } else {
         return false;
      }
   }

   public function getTime() {
      if(isset($this->userInfos['timezone'])) {
         $datetime = new DateTime("now", new DateTimeZone($this->userInfos['timezone']));
         return $datetime->format(dateformat);
      } else {
         $datetime = new DateTime("now", new DateTimeZone(default_timezone));
         return $datetime->format(dateformat);
      }
   }

   public function createUser($active = null, $admin = false) {
      if(defined('reg_invitekey') && defined('reg_privatekey') && reg_invitekey == 1) {
         $this->userInfos['privatekey'] = postManager::getPost('private_key', '/^.+$/');
         if($this->userInfos['privatekey'] == reg_privatekey) {
            $this->userInfos['privatekey'] = true;
         } else {
            $this->userInfos['privatekey'] = false;
         }
      } else {
         $this->userInfos['privatekey'] = true;
      }
      if($this->userInfos['privatekey']) {
         $this->userInfos['privatename'] = postManager::getPost('auth_name', '/^\w{3,18}$/');
         $this->userInfos['publicname'] = postManager::getPost('public_name', '/^\w{2,12}$/');
         $this->userInfos['pass'] = postManager::getPost('pass1', '/^.{6,18}$/');
         $this->userInfos['pass2'] = postManager::getPost('pass2', '/^.{6,18}$/');
         if(defined('reg_validation') && (int)reg_validation == 1) {
            if($active === null) {
               $this->userInfos['active'] = "0";
            } elseif($active === false) {
               $this->userInfos['active'] = "0";
            } elseif($active === true) {
               $this->userInfos['active'] = "1";
            } else {
               throw new userErrorManager(err_activevalue."!", 2);
            }
         } elseif(defined('reg_validation') && (int)reg_validation == 0) {
            if($active === null) {
               $this->userInfos['active'] = "1";
            } elseif($active === false) {
               $this->userInfos['active'] = "0";
            } elseif($active === true) {
               $this->userInfos['active'] = "1";
            } else {
               throw new userErrorManager(err_activevalue."!", 2);
            }
         } else {
            if($active === null) {
               $this->userInfos['active'] = "0";
            } elseif($active === false) {
               $this->userInfos['active'] = "0";
            } elseif($active === true) {
               $this->userInfos['active'] = "1";
            } else {
               throw new userErrorManager(err_activevalue."!", 2);
            }
         }
         $verif = array_keys($this->userInfos, false, true);
         if(count($verif) == 0 && count($this->userInfos) > 2 && $this->userInfos['pass'] == $this->userInfos['pass2']) {
            unset($this->userInfos['pass2']);
            try {
               $db = new PDO('mysql:host='.db_host.';dbname='.db_name, db_user, db_pass);
               $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               $req = $db->prepare('SELECT user_id,
                  CASE
                     WHEN LOWER(user_private_name) = :private_name AND LOWER(user_public_name) = :public_name THEN 3
                     WHEN LOWER(user_private_name) = :private_name THEN 1
                     WHEN LOWER(user_public_name) = :public_name THEN 2
                     ELSE 0
                  END
                  FROM Users WHERE LOWER(user_private_name) = :private_name OR LOWER(user_public_name) = :public_name');
               $req->execute(array(':private_name' => strtolower($this->userInfos['privatename']), ":public_name" => strtolower($this->userInfos['publicname'])));
               $res = $req->fetchAll();
               $req->closeCursor();
               $req = null;
               $test = [false, false, false];
               foreach($res as $line) {
                  if($line[1] == 3) {
                     $test[0] = true;
                     break;
                  } elseif($line[1] == 1) {
                     $test[2] = true;
                  } elseif($line[1] == 2) {
                     $test[1] = true;
                  }
               }
               $test = array_search(true, $test);
               if($test !== false) {
                  return 2 - $test;
               } else {
                  $req = $db->prepare('INSERT INTO Users (user_private_name, user_public_name, user_pass, user_active) VALUES (:private_name, :public_name, :pass, :active)');
                  $req->execute(array(':private_name' => $this->userInfos['privatename'], ':public_name' => $this->userInfos['publicname'], ':pass' => password_hash($this->userInfos['pass'], PASSWORD_DEFAULT), ':active' => (int)$this->userInfos['active']));
                  $req->closeCursor();
                  $req = null;
                  if($admin) {
                     $req = $db->prepare('INSERT INTO Admins (admin_id) VALUES (:id)');
                     $req->execute(array(':id' => $db->lastInsertId()));
                     $req->closeCursor();
                     $req = null;
                  }
                  return true;
               }
            } catch (PDOException $e) {
               throw new userErrorManager($e->getMessage(), 2);
            }
         } elseif($verif == 0 && count($this->userInfos) > 2 && $this->userInfos['pass'] != $this->userInfos['pass2']) {
            return 3;
         } elseif(count($verif) < (count($this->userInfos) - 2)) {
            $fields = implode(", ", $verif);
            throw new userErrorManager(count($verif)." ".err_invalidfields.": ".$fields, 2);
         } else {
            $this->userInfos = [];
            return false;
         }
      } else {
         $this->userInfos = [];
         throw new userErrorManager(err_invitekey, 1);
      }
   }
}

class languageManager extends userManager {

   public $languages;
   public $definedLanguage;
   public $preferedLanguage;

   public function __construct() {
      $this->languages = $this->getLanguages();
      if(in_array('en-GB', $this->languages)) {
         $this->definedLanguage = 'en-GB';
      } elseif(in_array('fr-FR', $this->languages)) {
         $this->definedLanguage = 'fr-FR';
      } elseif(in_array('en-US', $this->languages)) {
         $this->definedLanguage = 'en-US';
      } else {
         $this->definedLanguage = $this->languages[0];
      }
   }

   protected function getFileName($val) {
      if(preg_match('/^([a-z]{2,3}(?:-[a-z]{1,5})+)\.php$/i', $val, $val)) {
         return $val[1];
      } else {
         return false;
      }
   }

   protected function getLanguages() {
      $arr = scandir('languages/');
      return array_values(array_filter(array_map([$this, 'getFileName'], $arr)));
   }

   public function getPreferedLanguage() {
      $lang = postManager::getPost('preferedLanguage', '/^[a-z]{2,3}(?:-[a-z]{1,5})+$/i');
      if($lang && in_array($lang, $this->languages)) {
         $this->preferedLanguage = $lang;
         //update SQL
      }
   }

   public function defineLanguage() {
      $this->getPreferedLanguage();
      if($this->preferedLanguage) {
         $this->definedLanguage = $this->preferedLanguage;
      } else {
         $cfg = new fileManager('config/config.php');
         if($lang = $cfg->fileParsing(null, 'constant', 'lang_default')[1]) {
            $this->definedLanguage = $lang;
         }
      }
      require_once('languages/'.$this->definedLanguage.'.php');
   }
}

class datetimeManager {

   public $usertimezone;

   public function __construct() {
      $this->usertimezone = null;
   }
}

class postManager {

   public static function getPost($var, $pattern) {
      if(isset($_POST[$var]) && preg_match($pattern, $_POST[$var])) {
         return $_POST[$var];
      } else {
         return false;
      }
   }
}

class cookieManager {
   
}

class viewManager extends languageManager {

   public $variable;
   public $head_path;
   public $head_title;
   public $head_css;
   public $head_js;

   public function createView(...$files) {
      if($this->head_path) {
         array_unshift($files, $this->head_path);
      } else {
         throw new userErrorManager(err_noheadpath."!", 2);
      }
      foreach($files as $file) {
         if(!include_once('theme/'.$file.'.html')) {
            throw new userErrorManager(err_cannotloadfile.": theme/".$file.".html", 2);
         }
      }
      $this->variable = null;
      $this->head_path = null;
      $this->head_title = null;
      $this->head_css = null;
      $this->head_js = null;
      return true;
   }
}
?>