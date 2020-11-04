<?php

//spusteni aplikace
$app = new Application();
$app->launch();

/**
 * Vstupni bod webove aplikace
 */
class Application
{
    public function __construct()
    {
        require_once("settings.inc.php");
    }

    /**
     * Spusteni webove aplikace
     */
    public function launch()
    {
        $pageKey = DEFAULT_WEB_PAGE_KEY;
        if (isset($_GET["page"]) && array_key_exists($_GET["page"], WEB_PAGES))
            $pageKey = $_GET["page"];

        $pageInfo = WEB_PAGES[$pageKey];

        require_once(DIR_CONTROLLERS . "/" . $pageInfo["file_name"]);
        /** @var IController $controller ovladac stranky */
        $controller = new $pageInfo["class_name"];
        echo $controller->show($pageInfo["title"]);
    }
}