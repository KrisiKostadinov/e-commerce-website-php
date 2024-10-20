<?php

class IndexPostController
{
    public static function Contact(): void
    {
        $data = Setup::getJsonData();

        if (empty($data["fullname"]) || empty($data["email"]) || empty($data["subject"]) || empty($data["message"])) {
            $error = LANGUAGE["email_sending_reject"];
            Response::badRequest($error)->send();
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $error = LANGUAGE["invalid_email"];
            Response::badRequest($error)->send();
        }

        $mailManager = new MailService(
            SETTINGS["website_email"],
            $data["email"],
            $data["subject"],
        );

        $variables = [
            "fullname" => $data["fullname"],
            "message" => $data["message"],
            "subject" => $data["subject"],
            "website_email" => SETTINGS["website_email"],
            "website_phone" => SETTINGS["website_phone"],
            "website_display_name" => SETTINGS["website_display_name"],
        ];
        $mailManager->loadTemplate("contact", $variables);

        $result = $mailManager->send();
        if ($result["status"] === "reject") {
            $error = $result["message"];
            Response::badRequest($error)->send();
        }

        Response::ok(["message" => LANGUAGE["email_sending_success"]])->send();
    }
}
