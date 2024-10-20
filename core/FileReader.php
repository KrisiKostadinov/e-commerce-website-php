<?php

class FileReader
{
    public static function read(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ["status" => "reject", "message" => "Файлът не съществува: " . $filePath];
        }
        
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);
        
        if ($data === null) {
            return ["status" => "reject", "message" => "Неуспешно декодиране на JSON файла."];
        }
        
        return ["status" => "success", "data" => $data];
    }
}