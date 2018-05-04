<?php
 #en-US.php#

//date format
define("dateformat", 'm/d/Y h:i:s a');

//errors
define("err_err", "Oops an error");
define("err_404", "404 Not Found");
define("err_default_err", "Oops an error occured! Please try again later.");
define("err_error404", "Error 404: page not found!");
define("err_cannotwritefile", "Can't write in the file");
define("err_cannotcreatefile", "Can't create file");
define("err_cannotmodifyfile", "Can't modify file");
define("err_cannotreadfile", "Can't read file");
define("err_cannotudaptefile", "Can't update file");
define("err_cannotinstall", "Installation failed");
define("err_noheadpath", "No head path has been defined before creating the view");
define("err_cannotloadfile", "Can't load file");
define("err_undefinedlang", "Undefined language");
define("err_undefinedtimezone", "Undefined timezone");
define("err_invalidfields", "Invalid field(s)");
define("err_bothnamesexist", "Both names are already used, please choose others.");
define("err_privatenameexists", "Login ID is already used, please choose another one.");
define("err_publicnameexists", "Public ID is already used, please choose another one.");
define("err_passdiff", "The password doesn't match with the confirmation");

//setup
define("install_config", "Setup");
define("install_welcome", "Welcome on the setup!");
define("install_explanation", "Please follow the different steps to install the website.");
define("install_lang", "Define the default language of the website:");
define("install_timezone", "Define the default timezone of the website:");
define("install_sitetitle_explanation", "The website's name is displayed in the tab.");
define("install_db_explanation", "The website is intended for using a MySQL databse.");
define("install_db_descr", "The user must have the following privileges: all of Data privileges, all of Structure privileges and REFERENCES in Admininistration privileges.");
define("install_sitetitle", "Define the website's name:");
define("install_sitetitle_tip", "Website's name, from 1 to 16 caracters (non-accented letters, digits and underscore \"_\").");
define("install_askdbhost", "What is the database's address?");
define("install_askdbhost_tip", "If the database is located on the same server, use the value: localhost.");
define("install_askdbname", "What is the name of the database which the website has to connect?");
define("install_askdbname_tip", "Name of the databse which has to connect, from 1 to 32 characters (non-accented letters, digits and underscore \"_\").");
define("install_askdbuser", "What is the user name of the database which the website has to connect?");
define("install_askdbuser_tip", "User name of the databse which has to connect, from 1 to 32 characters (non-accented letters, digits and underscore \"_\").");
define("install_askdbpass", "What is the password of the database which the website has to connect?");
define("install_askdbpass2", "Confirm the password:");
define("install_admin_explanation", "Create your admin account.");

define("install_success", "Setup completed!");

//forms
define("form_send", "Send   >");
define("form_authname", "Choose a login ID:");
define("form_authname_tip", "Your login ID will only be used to login, from 3 to 18 characters (non-accented letters, digits, and underscore \"_\").");
define("form_privatename", "Choose a public ID:");
define("form_privatename_tip", "Your public ID will allow other users to recognize you, from 2 to 12 characters (non-accented letters, digits, and underscore \"_\").");
define("form_pass1", "Choose a password:");
define("form_pass1_tip", "Your password, from 6 to 18 characters (all characters are allowed).");
define("form_pass2", "Confirm your password:");
define("form_pass2_tip", "Enter your password again to confirm that you have not made a typing error.");
?>