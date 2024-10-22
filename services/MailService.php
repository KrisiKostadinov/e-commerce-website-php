<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

class MailService
{
    private PHPMailer $mailer;
    private string $to;
    private string $subject;
    private string $message;

    public function __construct(string $to, string $from, string $subject)
    {
        $this->to = $to;
        $this->subject = $subject;

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = SETTINGS["mail_host"];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SETTINGS["mail_username"];
        $this->mailer->Password = SETTINGS["mail_password"];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = SETTINGS["mail_port"];
        $this->mailer->setFrom($from, $subject);
        $this->mailer->addAddress($this->to);

        $this->mailer->isHTML(true);
        $this->mailer->CharSet = SETTINGS["mail_encoding"];
        $this->mailer->Subject = $this->subject;
    }

    public function send(): array
    {
        if (!filter_var($this->to, FILTER_VALIDATE_EMAIL)) {
            return ["status" => "reject", "message" => LANGUAGE["email_sending_success"]];
        }

        $this->mailer->send();
        return ["success" => true];
    }

    public function loadTemplate($templateName, $variables = [])
    {
        $templatePath = "email-templates/" . $templateName . ".html";

        if (!file_exists($templatePath)) {
            return ["status" => "reject", "error" => LANGUAGE["email_sending_success"]];
        }

        $templateContent = file_get_contents($templatePath);

        foreach ($variables as $key => $value) {
            $templateContent = str_replace("{{" . $key . "}}", $value, $templateContent);
        }

        $this->message = $templateContent;
        $this->mailer->Body = $this->message;
    }
}
