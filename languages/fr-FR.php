<?php
 #fr-FR.php#

//format date
define("dateformat", 'd/m/Y H:i:s');

//erreurs
define("err_err", "Erreur");
define("err_404", "404 Not Found");
define("err_default_err", "Oups une erreur est apparue ! Veuillez essayer de nouveau plus tard.");
define("err_error404", "Erreur 404 : page non trouvée !");
define("err_cannotwritefile", "Impossible d'écrire dans le fichier ");
define("err_cannotcreatefile", "Impossible de créer le fichier ");
define("err_cannotmodifyfile", "Impossible de modifier le fichier ");
define("err_cannotreadfile", "Impossible de lire le fichier ");
define("err_cannotudaptefile", "Impossible de mettre à jour le fichier ");
define("err_cannotinstall", "L'installation a échoué ");
define("err_noheadpath", "Aucun chemin d'en-tête spécifié avant de créer la vue ");
define("err_cannotloadfile", "Impossible de charger le fichier ");
define("err_undefinedlang", "Langue non définie ");
define("err_undefinedtimezone", "Fuseau horaire non défini ");
define("err_activevalue", "Valeur d'activation invalide ");
define("err_invalidfields", "Champ(s) invalide(s) ");
define("err_cannotlogtodb", "Impossible d'établir la connexion à la base de données, veuillez vérifier les données saisies.");
define("err_bothnamesexist", "Les deux noms sont déjà utilisés, veuillez en choisir d'autres.");
define("err_privatenameexists", "L'identifiant de connexion est déjà utilisé, veuillez en choisir un autre.");
define("err_publicnameexists", "L'identifiant publique est déjà utilisé, veuillez en choisir un autre.");
define("err_passdiff", "Le mot de passe saisi ne correspond pas à la confirmation ");
define("err_invitekey", "Désolé vous devez posséder la clé d'enregistrement pour vous inscrire.");

//installation
define("install_config", "Configuration de l'installation");
define("install_welcome", "Bienvenue sur l'installation !");
define("install_explanation", "Veuillez suivre les différentes étapes pour installer le site.");
define("install_lang", "Définissez la langue par défaut du site :");
define("install_timezone", "Définissez le fuseau horaire par défaut du site :");
define("install_sitetitle_explanation", "Le nom du site s'affiche dans l'onglet.");
define("install_db_explanation", "Le site est destiné à utiliser une base de données MySQL.");
define("install_db_descr", "L'utilisateur doit avoir les privilèges suivants : tous les privilèges de Data, tous les privilèges de Structure et les REFERENCES dans les privilèges d'Administration.");
define("install_sitetitle", "Définissez le nom du site :");
define("install_sitetitle_tip", "Nom du site, de 1 à 16 caractères (lettres non accentuées, chiffres et tiret \"_\").");
define("install_askdbhost", "Quelle est l'adresse de la base de données ?");
define("install_askdbhost_tip", "Si la base de données se trouve sur le même serveur, utilisez la valeur : localhost.");
define("install_askdbname", "Quel est le nom de la base de données à laquelle le site doit se connecter ?");
define("install_askdbname_tip", "Nom de la base de données à laquelle se connecter, de 1 à 32 caractères (lettres non accentuées, chiffres et tiret \"_\").");
define("install_askdbuser", "Quel est le nom d'utilisateur de la base de données à laquelle le site doit se connecter ?");
define("install_askdbuser_tip", "Utilisateur de la base de données à laquelle se connecter, de 1 à 32 caractères (lettres non accentuées, chiffres et tiret \"_\").");
define("install_askdbpass", "Quel est le mot de passe de la base de données à laquelle le site doit se connecter ?");
define("install_askdbpass2", "Confirmez le mot de passe :");
define("install_admin_explanation", "Créez votre compte admin.");
define("install_register_explanation", "Paramétrez les options d'inscription.");
define("install_yes", "Oui");
define("install_no", "Non");
define("install_regvalidation", "Voulez-vous activer la validation des inscriptions par un administrateur ?");
define("install_regvalidation_tip", "Chaque utilisateur devra attendre après son inscription que son compte soit activé par un administrateur pour pouvoir l'utilisier.");
define("install_regprivatekey", "Voulez-vous activer l'inscription uniquement sur invitation ?");
define("install_regprivatekey_tip", "Seuls les utilisateurs ayant reçu la clé d'inscription pourront s'inscrire.");
define("install_regrecaptcha", "Voulez-vous activer le système de vérification reCAPTCHA v2 ?");
define("install_regrecaptcha_tip", "Le système <a href=\"https://www.google.com/recaptcha/\">reCAPTCHA v2</a> permet de vérifier que l'utilisateur est une personne et non un robot.");
define("install_regrecaptcha_publickey", "Votre clé publique reCAPTCHA v2 :");
define("install_regrecaptcha_publickey_tip", "Veuillez renseigner la clé publique reCAPTCHA v2 fournie par Google pour votre domaine.");
define("install_regrecaptcha_privatekey", "Votre clé privée reCAPTCHA v2 :");
define("install_regrecaptcha_privatekey_tip", "Veuillez renseigner la clé privée reCAPTCHA v2 fournie par Google pour votre domaine.");
define("install_completed_explanation", "Installation terminée avec succès !");
define("install_completed_descr", "L'installation est maintennat terminée, vous ne pourrez plus accèder à ce fichier à moins de supprimer le fichier /config/config.php. Vous pouvez maintenant accèder à <a href=\"/\">votre site</a>.");

//formulaires
define("form_send", "Envoyer  >");
define("form_authname", "Choisissez un identifiant de connexion :");
define("form_authname_tip", "Votre identifiant de connexion vous servira uniquement à vous connecter, de 3 à 18 caractères (lettres non accentuées, chiffres et tiret \"_\").");
define("form_privatename", "Choisissez un identifiant publique :");
define("form_privatename_tip", "Votre identifiant publique permettra aux autres utilisateurs de vous reconnaître, de 2 à 12 caractères (lettres non accentuées, chiffres et tiret \"_\").");
define("form_pass1", "Choisissez votre mot de passe :");
define("form_pass1_tip", "Votre mot de passe, de 6 à 18 caractères (tous les caractères sont autorisés).");
define("form_pass2", "Confirmez votre mot de passe :");
define("form_pass2_tip", "Saisissez de nouveau votre mot de passe pour confirmer que vous n'avez pas fait d'erreur de saisie.");

//header
define("header_title", "Bienvenue sur le site des Pronos de la CdM 2018");

//index.php
define("index_title", "Accueil");
?>