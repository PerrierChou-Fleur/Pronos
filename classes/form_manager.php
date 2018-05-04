<?php
 #form_manager.php#
  /* class formManager */
       //__construct
       //askLang
       //getLang
       //askDbInfos
       //getDbInfos
       //askAdminInfos

class formManager extends viewManager {

   public $langInfos;
   public $timezoneInfos;
   public $dbInfos;
   public $adminInfos;
   public $userExists;

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
            $this->adminInfos = null;
            $this->userExists = null;
            break;
         default:
            throw new userErrorManager("Invalid type!", 3);
      }
   }

   public function askLangTimezone() {
      $this->variable = null;
      $this->head_path = 'notitle-head';
      $this->head_title = '';
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['animation_form', 'timezone_form'], 'init'=>['animationForm();', 'timezone();']);
      return $this->createView('install-header', 'install-langform', 'footer');
   }

   public function getLang() {
      $this->langInfos = postManager::getPost('default_lang', '/^[a-z]{2,3}(?:-[a-z]{1,5})+$/i');
      if($this->langInfos && in_array($this->langInfos, $this->getLanguages())) {
         return $this->langInfos;
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
         return $this->timezoneInfos;
      } elseif($this->timezoneInfos) {
         $this->timezoneInfos = null;
         throw new userErrorManager(err_undefinedtimezone."!", 2);
      } else {
         return false;
      }
   }

   public function askDbInfos() {
      $this->variable = null;
      $this->head_path = 'notitle-head';
      $this->head_title = '';
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['animation_form', 'pass_form'], 'init'=>['animationForm();']);
      return $this->createView('install-header', 'install-dbform', 'footer');
   }

   public function getDbInfos() {
      $this->dbInfos['site_title'] = postManager::getPost('site_title', '/^\w{1,16}$/');
      $this->dbInfos['db_host'] = postManager::getPost('db_host', '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.)(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|(?:localhost)$/i');
      $this->dbInfos['db_name'] = postManager::getPost('db_name', '/^\w{1,32}$/');
      $this->dbInfos['db_user'] = postManager::getPost('db_user', '/^\w{1,32}$/');
      $this->dbInfos['db_pass'] = postManager::getPost('pass1', '/.*/');
      $this->dbInfos['pass2'] = postManager::getPost('pass2', '/.*/');
      $verif = array_filter($this->dbInfos, function($val) { if($val === false) { return true; } else { return false; } });
      if(count($verif) == count($this->dbInfos)) {
         $this->dbInfos = [];
         return false;
      } elseif(count($verif) > 0) {
         $fields = implode(", ", array_keys($verif));
         throw new userErrorManager(err_invalidfields.": ".$fields, 2);
      } elseif($this->dbInfos['db_pass'] != $this->dbInfos['pass2']) {
         throw new userErrorManager(err_passdiff."!", 1);
      } else {
         $this->dbInfos['db_pass'] = addslashes($this->dbInfos['db_pass']);
         unset($this->dbInfos['pass2']);
         return $this->dbInfos;
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
      $this->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $this->head_js = array('path'=>['animation_form', 'pass_form'], 'init'=>['animationForm();']);
      return $this->createView('install-header', 'install-adminform', 'footer');
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
}
?>