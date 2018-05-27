<?php
 #index.php

session_start();

require_once('config/config.php');
require_once('classes/general.php');
require_once('classes/file_manager.php');

try {
   $view = new viewManager();
   if(isset($_POST['auth_name']) || isset($_POST['pass'])) {
      $view->defineLanguage();
      $user = new userManager();
      if($user->connectUser() === true) {
         header('Location: index.html');
      }
   } elseif(isset($_POST['default_lang'])) {
      
   } elseif(isset($_POST['signout'])) {
      $view->defineLanguage();
      $user = new userManager();
      if($user->disconnectUser()) {
        header('Location: index.html');
      } 
   } elseif($view->checkIsLoggedIn() === true) {
      $view->defineLanguage();
      $view->head_path = 'head';
      $view->head_title = index_title;
      $view->variable['currentpage'] = "index";
      $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>'')];
      $view->head_js = array('path'=>['main', 'pass_form'], 'init'=>["changeDate('".dateformat."');"]);
      $view->createView('head', 'header-logged', 'index', 'footer');
   } else {
      $view->defineLanguage();
      $view->head_path = 'head';
      $view->head_title = index_title;
      $view->variable['currentpage'] = "index";
      $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>'')];
      $view->head_js = array('path'=>['main', 'pass_form'], 'init'=>["changeDate('".dateformat."');"]);
      $view->createView('head', 'header', 'index', 'footer');
   }
} catch(userErrorManager $e) {
   $e->createErrorView();
}
?>