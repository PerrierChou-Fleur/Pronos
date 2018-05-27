<?php
 #install_manager.php#
  /* class installManager */
       //__construct
       //askLang
       //getLang
       //askDbInfos
       //getDbInfos
       //askAdminInfos
       //getAdminInfos
       //askRegisterInfos
       //getRegisterInfos
       //askSetupCompleted

class installManager extends viewManager {

   public $langInfos;
   public $timezoneInfos;
   public $dbInfos;
   public $userExists;
   public $registerInfos;

   public function __construct($type) {
      switch($type) {
         case 'dbinfos':
            $this->dbInfos = [];
            break;
         case 'lang/timezone':
            $this->langInfos = null;
            $this->timezoneInfos = null;
            break;
         case 'createAdmin':
            $this->userExists = null;
            break;
         case 'registerInfos':
            $this->registerInfos = [];
            break;
         case 'completed':
            break;
         default:
            throw new userErrorManager("Invalid type!", 3);
      }
   }

   public function askLangTimezone() {
      $this->variable = null;
      $this->head_path = 'notitle-head';
      $this->head_title = '';
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['main', 'animation_form', 'timezone_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();', 'timezone();']);
      return $this->createView('header', 'install-langform', 'footer');
   }

   public function getLang() {
      $this->langInfos = postManager::getPost('default_lang', '/^[a-z]{2,3}(?:-[a-z]{1,5})+$/i');
      if($this->langInfos && in_array($this->langInfos, $this->getLanguages())) {
         return true;
      } elseif($this->langInfos) {
         $this->langInfos = null;
         throw new userErrorManager(err_undefinedlang."!", 2);
      } else {
         $this->langInfos = null;
         return false;
      }
   }

   public function getTimezone() {
      $this->timezoneInfos = postManager::getPost('default_timezone', '/^(?:\w+\/\w+)|(?:UTC)$/');
      if($this->timezoneInfos && in_array($this->timezoneInfos, DateTimeZone::listIdentifiers())) {
         return true;
      } elseif($this->timezoneInfos) {
         $this->timezoneInfos = null;
         throw new userErrorManager(err_undefinedtimezone."!", 2);
      } else {
         return false;
      }
   }

   public function askDbInfos($var = null) {
      $this->variable = $var;
      $this->head_path = 'notitle-head';
      $this->head_title = '';
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['main', 'animation_form', 'pass_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();']);
      return $this->createView('header', 'install-dbform', 'footer');
   }

   public function getDbInfos() {
      $this->dbInfos['site_title'] = postManager::getPost('site_title', '/^\w{1,16}$/');
      $this->dbInfos['db_host'] = postManager::getPost('db_host', '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|(?:localhost)$/i');
      $this->dbInfos['db_name'] = postManager::getPost('db_name', '/^\w{1,32}$/');
      $this->dbInfos['db_user'] = postManager::getPost('db_user', '/^\w{1,32}$/');
      $this->dbInfos['db_pass'] = postManager::getPost('pass1', '/.*/');
      $this->dbInfos['pass2'] = postManager::getPost('pass2', '/.*/');
      $verif = array_keys($this->dbInfos, false, true);
      if(count($verif) == count($this->dbInfos)) {
         $this->dbInfos = [];
         return false;
      } elseif(count($verif) > 0) {
         $fields = implode(", ", $verif);
         throw new userErrorManager(err_invalidfields.": ".$fields, 2);
      } elseif($this->dbInfos['db_pass'] != $this->dbInfos['pass2']) {
         throw new userErrorManager(err_passdiff."!", 1);
      } else {
         $this->dbInfos['db_pass'] = addslashes($this->dbInfos['db_pass']);
         unset($this->dbInfos['pass2']);
         try {
            $db = new PDO('mysql:host='.$this->dbInfos['db_host'].';dbname='.$this->dbInfos['db_name'], $this->dbInfos['db_user'], $this->dbInfos['db_pass']);
            return true;
         } catch (PDOException $e) {
            return $e;
         }
      }
   }

   public function askAdminInfos() {
      if($this->userExists) {
         $this->variable = $this->userExists;
      } else {
         $this->variable = null;
      }
      $this->head_path = 'head';
      $this->head_title = install_config;
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['main', 'animation_form', 'pass_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();']);
      return $this->createView('header', 'install-adminform', 'footer');
   }

   public function getAdminInfos() {
      $admin = new userManager();
      $test = $admin->createUser(true, true);
      if($test === true) {
         return true;
      } else {
         $this->userExists = $test;
         return false;
      }
   }

   public function askRegisterInfos() {
      $this->head_path = 'head';
      $this->head_title = install_config;
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['main', 'animation_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();']);
      return $this->createView('header', 'install-registerform', 'footer');
   }

   public function getRegisterInfos() {
      $this->registerInfos['validation'] = postManager::getPost('reg_validation', '/^0|1$/');
      $this->registerInfos['private_key'] = postManager::getPost('reg_privatekey', '/^0|1$/');
      $this->registerInfos['recaptcha'] = postManager::getPost('reg_recaptcha', '/^0|1$/');
      if($this->registerInfos['recaptcha']) {
         $this->registerInfos['recaptcha_publickey'] = postManager::getPost('reg_recaptcha_publickey', '/^[-a-z0-9]{40}$/i');
         $this->registerInfos['recaptcha_privatekey'] = postManager::getPost('reg_recaptcha_privatekey', '/^[-a-z0-9]{40}$/i');
      } else {
         $this->registerInfos['recaptcha_publickey'] = postManager::getPost('reg_recaptcha_publickey', '//');
         $this->registerInfos['recaptcha_privatekey'] = postManager::getPost('reg_recaptcha_privatekey', '//');
      }
      $verif = array_keys($this->registerInfos, false, true);
      if(count($verif) == 0 && count($this->registerInfos) > 0) {
         return true;
      } elseif(count($verif) < count($this->registerInfos)) {
         $fields = implode(", ", $verif);
         throw new userErrorManager(err_invalidfields.": ".$fields, 2);
      } else {
         $this->registerInfos = [];
         return false;
      }
   }

   public function askSetupCompleted() {
      $this->head_path = 'head';
      $this->head_title = install_config;
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['main'], 'init'=>["changeDate('".dateformat."');"]);
      return $this->createView('header', 'install-completed', 'footer');
   }
}
?>