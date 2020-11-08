<?php

// GLOBALNI NASTAVENI APLIKACE //

// aplikace

/** klic  defaultni webove stranky */
const DEFAULT_WEB_PAGE_KEY = "index";

/** webove stranky aplikace */
const WEB_PAGES = array(
    "index" => array(
        "file_name" => "IndexController.class.php",
        "class_name" => "IndexController",
        "title" => "Vítejte"
    ),
    "login" => array(
        "file_name" => "LoginController.class.php",
        "class_name" => "LoginController",
        "title" => "Přihlášení"
    ),
    "registration" => array(
        "file_name" => "RegistrationController.class.php",
        "class_name" => "RegistrationController",
        "title" => "Registrace"
    ),
    "user_management" => array(
        "file_name" => "UserManagementController.class.php",
        "class_name" => "UserManagementController",
        "title" => "Správa uživatele"
    ),
    "articles" => array(
        "file_name" => "ArticlesController.class.php",
        "class_name" => "ArticlesController",
        "title" => "Články"
    ),
    "admin" => array(
        "file_name" => "AdminController.class.php",
        "class_name" => "AdminController",
        "title" => "Admin"
    )
);

/** adresar ovladacu (Controllers) */
const DIR_CONTROLLERS = "Controllers";
/** adresar sablon (Views) */
const DIR_VIEWS = "Views";
/** adresar modelu (Models)*/
const DIR_MODELS = "Models";

// databaze

/** server databaze */
define("DB_SERVER", "localhost");
/** nazev databaze */
define("DB_NAME", "web_semestralka");
/** jmeno uzivatele */
define("DB_USER", "root");
/** heslo uzivatele */
define("DB_PASS", "");

/** uzivatele */
define("TABLE_UZIVATEL", "mjakubas_uzivatel");
/** prava uzivatelu */
define("TABLE_PRAVO", "mjakubas_pravo");
/** clanky uzivatelu */
define("TABLE_CLANEK", "mjakubas_clanek");
/** recenze na clanky */
define("TABLE_RECENZE", "mjakubas_recenze");

/** identifikator prihlaseneho uzivatele */
define("SESSION_USER_KEY", "session_user_key");

// nahravani souboru

/** povoleny typ souboru */
define("ALLOWED_FILE_TYPE", "pdf");

/** cesta k adresari se clanky */
define("ARTICLES_PATH", "/KIV_WEB_SEMESTRALKA/Uploads/Articles/");