<?php

function loadFile(string $filename): array {
    $result = FileReader::read($filename);

    if ($result["status"] === "reject") {
        Response::badRequest($result["message"])->send();
    }

    return $result["data"];
}

try {
    $currentLanguage = $_SESSION["language"];
    $settingsData = loadFile("settings/index.json");
    $languageData = loadFile("languages/$currentLanguage.json");

    define("SETTINGS", $settingsData);
    define("LANGUAGE", $languageData);

    $db = new DatabaseConnection(
        SETTINGS["host"],
        SETTINGS["db_name"],
        SETTINGS["username"],
        SETTINGS["password"]
    );
    
    $connection = $db->getConnection();
} catch (Exception $e) {
    Response::serverError($e->getMessage())->send();
}
