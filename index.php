<?php
 #index.php

session_start();

require_once('config/config.php');
require_once('classes/general.php');
require_once('classes/file_manager.php');

try {
   $view = new viewManager();
   if($view->checkIsLoggedIn() === false) {
      $view->defineLanguage();
      $view->variable['datetime'] = $view->getTime();
      $view->head_path = 'head';
      $view->head_title = index_title;
      $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>'')];
      $view->head_js = array('path'=>['main', 'pass_form'], 'init'=>["changeDate('".dateformat."');"]);
      $view->createView('head', 'header', 'index', 'footer');
   }
} catch(userErrorManager $e) {
   $e->createErrorView();
}
?>