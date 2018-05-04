<?php
 #install.php#

require_once('classes/general.php');
require_once('classes/file_manager.php');
require_once('classes/form_manager.php');

function success() {
   echo install_success;
}

function lock() {
   header('HTTP/1.0 404 Not Found');
   return null;
}

function installLoop($install_cfg) {
   require_once('config/config.php');
   $step = $install_cfg->fileParsing(null, 'constant', "install");
   switch ((int)$step[1]) {
      case 1:
         $lang_form = new formManager('lang/timezone');
         if(!$lang_form->getLang() || !$lang_form->getTimezone()) {
            return $lang_form->askLangTimezone();
         } else {
            $install_cfg->fileUpdate('comment', "language params");
            $install_cfg->fileUpdate('constant', 'lang_default', $lang_form->langInfos);
            $install_cfg->fileUpdate('comment', "timezone params");
            $install_cfg->fileUpdate('constant', 'default_timezone', $lang_form->timezoneInfos);
            $install_cfg->fileUpdate('constant', 'install', 2);
         }
         header('Location: install.html');
         break;
      case 2:
         $db_form = new formManager('dbinfos');
         if(!$db_form->getDbInfos()) {
            return $db_form->askDbInfos();
         } else {
            $install_cfg->fileUpdate('comment', "site title");
            $install_cfg->fileUpdate('constant', "site_title", $db_form->dbInfos['site_title']);
            $install_cfg->fileUpdate('comment', "database params");
            foreach($db_form->dbInfos as $key => $val) {
               if($key != 'site_title') {
                  $install_cfg->fileUpdate('constant', $key, $val);
               }
            }
            $install_cfg->fileUpdate('constant', 'install', 3);
         }
         header('Location: install.html');
         break;
      case 3:
         try {
            $db = new PDO('mysql:host='.db_host.';dbname='.db_name, db_user, db_pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $req = $db->query('
               CREATE TABLE IF NOT EXISTS Users (user_id int unsigned NOT NULL AUTO_INCREMENT, user_private_name varchar(18) NOT NULL, user_public_name varchar(12) NOT NULL, user_lang varchar(15), user_timezone varchar(255), user_pass varchar(255) NOT NULL, user_active boolean NOT NULL, PRIMARY KEY (user_id));
               CREATE TABLE IF NOT EXISTS Admins (admin_id int unsigned NOT NULL, PRIMARY KEY (admin_id));
               CREATE TABLE IF NOT EXISTS Tournaments (tournament_id int unsigned NOT NULL AUTO_INCREMENT, tournament_name varchar(18) NOT NULL, tournament_model varchar(18) NOT NULL, tournament_access tinyint unsigned NOT NULL, tournament_pass varchar(255) NOT NULL, tournament_owner int unsigned NOT NULL, PRIMARY KEY (tournament_id), CONSTRAINT fk_owner_id FOREIGN KEY (tournament_owner) REFERENCES Users(user_id));
               CREATE TABLE IF NOT EXISTS Subscriptions (sub_id int unsigned NOT NULL AUTO_INCREMENT, sub_user int unsigned NOT NULL, sub_tournament int unsigned NOT NULL, PRIMARY KEY (sub_id), CONSTRAINT fk_user_id FOREIGN KEY (sub_user) REFERENCES Users(user_id), CONSTRAINT fk_tournament_id FOREIGN KEY (sub_tournament) REFERENCES Tournaments(tournament_id));
            ');
            $req = null;
            $db = null;
         } catch (PDOException $e) {
            throw new userErrorManager($e->getMessage(), 2);
            return false;
         }
         $install_cfg->fileUpdate('constant', 'install', 4);
         installLoop($install_cfg);
         break;
      case 4:
         $admin_form = new formManager('createAdmin');
         if(!$admin_form->getAdminInfos()) {
            return $admin_form->askAdminInfos();
         } else {
            $install_cfg->fileUpdate('constant', 'install', 5);
         }
         installLoop($install_cfg);
         break;
      case 5:
         return success();
         installLoop($install_cfg);
         break;
      case "locked":
         return lock();
         break;
      default:
         throw new userErrorManager(err_cannotinstall."!", 2);
   }
}

try {
   $lang = new languageManager();
   $lang->defineLanguage();
   $install_cfg = new fileManager('config/config.php');
   if(!file_exists('config/config.php')) {
      $install_cfg->fileCreate();
   }
   installLoop($install_cfg);
} catch(userErrorManager $e) {
   $e->createView();
}
?>