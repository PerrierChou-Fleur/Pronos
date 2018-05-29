<?php
 #register.php

session_start();

require_once('config/config.php');
require_once('classes/general.php');
require_once('classes/file_manager.php');

try {
   $view = new viewManager();
   if(isset($_POST['name']) && $_POST['name'] == "login") {
      $view->defineLanguage();
      $user = new userManager();
      if($user->connectUser() === true) {
         header('Location: /index.html');
      }
   } elseif(isset($_POST['name']) && $_POST['name'] == "language") {
      
   } elseif(isset($_POST['name']) && $_POST['name'] == "register") {
      $view->defineLanguage();
      $user = new userManager();
      $test = $user->createUser();
      if($test === true) {
         header('Location: /index.html');
      } else {
         $view->defineLanguage();
         $view->variable['userExists'] = $test;
         $view->head_path = 'head';
         $view->head_title = register_title;
         $view->variable['currentpage'] = "register";
         $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
         $view->head_js = array('path'=>['main', 'animation_form', 'pass_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();']);
         $view->createView('head', 'header', 'registerform', 'footer');
      }
   } elseif($view->checkIsLoggedIn() === true) {
      header('Location: /index.html');
   } else {
      $view->defineLanguage();
      $view->head_path = 'head';
      $view->head_title = register_title;
      $view->variable['currentpage'] = "register";
      $view->head_css = [array('path'=>'main', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'form-slidein', 'rel'=>'stylesheet', 'media'=>''), array('path'=>'large-tooltip', 'rel'=>'stylesheet', 'media'=>'')];
      $view->head_js = array('path'=>['main', 'animation_form', 'pass_form'], 'init'=>["changeDate('".dateformat."');", 'animationForm();']);
      $view->createView('head', 'header', 'registerform', 'footer');
   }
} catch(userErrorManager $e) {
   $e->createErrorView();
}
?>