<?php
 #error404.php#

require_once('config/config.php');
require_once('classes/general.php');
require_once('classes/file_manager.php');

header('HTTP/1.0 404 Not Found');

try {
   $lang = new languageManager();
   $lang->defineLanguage();
   $view = new viewManager();
   $view->variable = null;
   $view->head_path = 'head404';
   $view->head_title = err_404;
   $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>'')];
   $view->head_js = array('path'=>[], 'init'=>[]);
   $view->createView('header', 'error404notfound', 'footer');
} catch(userErrorManager $e) {
   $e->createView();
}
?>