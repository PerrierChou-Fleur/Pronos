<?php
 #install.php#

require_once('classes/general.php');
require_once('classes/file_manager.php');
require_once('classes/install_manager.php');

function installLoop($install_cfg) {
   require_once('config/config.php');
   $step = $install_cfg->fileParsing(null, 'constant', "install");
   switch ((int)$step[1]) {
      case 1:
         $lang_form = new installManager('lang/timezone');
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
         $db_form = new installManager('dbinfos');
         if(!$db_form->getDbInfos()) {
            return $db_form->askDbInfos();
         } elseif($db_form->getDbInfos() instanceof PDOException) {
            return $db_form->askDbInfos($db_form->dbInfos);
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
               CREATE TABLE IF NOT EXISTS Users (user_id int unsigned NOT NULL AUTO_INCREMENT, user_private_name varchar(18) NOT NULL, user_public_name varchar(12) NOT NULL, user_lang varchar(15), user_timezone varchar(255), user_pass varchar(255) NOT NULL, user_active boolean NOT NULL, PRIMARY KEY (user_id), CONSTRAINT unique_user UNIQUE (user_private_name, user_public_name));
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
         $admin_form = new installManager('createAdmin');
         if(!$admin_form->getAdminInfos()) {
            return $admin_form->askAdminInfos();
         } else {
            $install_cfg->fileUpdate('constant', 'install', 5);
         }
         installLoop($install_cfg);
         break;
      case 5:
         $register_form = new installManager('registerInfos');
         if(!$register_form->getRegisterInfos()) {
            return $register_form->askRegisterInfos();
         } else {
            $install_cfg->fileUpdate('comment', "registration params");
            $install_cfg->fileUpdate('constant', "reg_validation", $register_form->registerInfos['validation']);
            $install_cfg->fileUpdate('constant', "reg_invitekey", $register_form->registerInfos['private_key']);
            $key = new DateTime("now");
            $install_cfg->fileUpdate('constant', "reg_privatekey", str_replace(['$', '/'], ['£', '~'], password_hash($key->format('YFjlGisu'), PASSWORD_DEFAULT)));
            $install_cfg->fileUpdate('constant', "reg_recaptcha", $register_form->registerInfos['recaptcha']);
            $install_cfg->fileUpdate('constant', "recaptcha_publickey", $register_form->registerInfos['recaptcha_publickey']);
            $install_cfg->fileUpdate('constant', "recaptcha_privatekey", $register_form->registerInfos['recaptcha_privatekey']);
            $install_cfg->fileUpdate('constant', 'install', 6);
         }
         installLoop($install_cfg);
         break;
      case 6:
         $install_completed = new installManager('completed');
         $install_completed->askSetupCompleted();
         $install_cfg->fileUpdate('constant', 'max_error_lvl_show', "1");
         $install_cfg->findPosition('comment', 'errors param \/1: user \/2: admin \/3: developper');
         $install_cfg->fileUpdate('method', 'ini_set', ['display_errors', '0'], true);
         $install_cfg->fileUpdate('constant', 'install', "locked");
         break;
      case "locked":
         header('HTTP/1.0 404 Not Found');
         require_once('error404.php');
         exit;
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
   $e->createErrorView();
}
?>