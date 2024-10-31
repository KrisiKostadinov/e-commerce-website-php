<?php

class AuthService
{
    public static function register(?string $email, ?string $password, ?string $cpassword): array
    {
        global $db;
        $db->beginTransaction();

        $validationResult = AuthValidator::validateRegister($email, $password, $cpassword);
        if (!$validationResult["success"]) {
            return $validationResult;
        }

        if (self::get("email", $email)["success"]) {
            return ["success" => false, "error" => LANGUAGE["email_exists"]];
        }

        $isEmptyTable = self::getAll(1, 0);

        try {
            $data = [
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "role_access" => count($isEmptyTable) > 0 ? "user" : "admin",
            ];

            $db->create("users", $data);

            $tokenAndLink = self::generateEmailConfirmationLink($db->getLastInsertedId());

            $db->update("users", [
                "email_confirmation_token" => $tokenAndLink["token"]
            ], ["id" => $db->getLastInsertedId()]);

            $emailResult = self::sendSuccessRegistrationEmail($email);
            if (!$emailResult["success"]) {
                $db->rollBack();
                return $emailResult;
            }

            $emailResult = self::sendConfirmationEmail($email, $tokenAndLink["link"]);
            if (!$emailResult["success"]) {
                $db->rollBack();
                return $emailResult;
            }

            $db->commit();

            unset($data["password"]);
            $cleanedUser = Validations::removeNullFields($data);

            return ["success" => true, "data" => $cleanedUser];
        } catch (Exception $e) {
            $db->rollBack();
            Response::serverError($e->getMessage(), $e->getTrace())->send();
            return ["success" => false, "error" => "Registration failed. Please try again later."];
        }
    }

    public static function login(?string $email, ?string $password): array
    {
        $validationResult = AuthValidator::validateLogin($email, $password);
        if (!$validationResult["success"]) {
            return $validationResult;
        }

        $result = self::get("email", $email);
        if ($result["success"] === false) {
            return ["success" => false, "error" => LANGUAGE["invalid_credentials"]];
        }

        $user = $result["data"];

        if (!$user["is_email_confirmed"]) {
            return ["success" => false, "error" => LANGUAGE["email_not_confirmed"]];
        }

        if ($user["status"] !== "active") {
            return ["success" => false, "error" => LANGUAGE["temporary_forbidden"]];
        }

        if (!password_verify($password, $user["password"])) {
            return ["success" => false, "error" => LANGUAGE["invalid_credentials"]];
        }

        $jsonWebToken = new JsonWebToken(SETTINGS["jwt_secret_key"]);
        $payload = [
            "id" => $user["id"],
            "expires" => time() + SETTINGS["jwt_expires"]
        ];
        $token = $jsonWebToken->createToken($payload);

        setcookie("token", $token, $payload["expires"], "/", "", true, true);

        return ["success" => true, "token" => $token];
    }

    public static function getAll(int $limit, int $offset, string $column = "email", string $value = ""): array {
        global $db;
        
        $conditions = [];
        if ($value !== "") {
            $conditions[$column] = $value;
        }
        
        $users = $db->read("users", $conditions);
        return array_slice($users, $offset, $limit);
    }    

    public static function forgotPassword($email): array
    {
        $result = self::get("email", $email);
        if ($result["success"] === false) {
            return ["success" => false, "error" => LANGUAGE["invalid_email"]];
        }

        $user = $result["data"];

        $resetToken = bin2hex(random_bytes(16));
        $expiryTime = time() + 3600;

        global $db;
        $db->update("users", [
            "password_reset_token" => $resetToken,
            "token_expiry" => $expiryTime
        ], ["id" => $user["id"]]);

        $resetLink = SETTINGS["website_link"] . "/auth/password-recovery?token=" . urlencode($resetToken);

        $emailResult = self::sendPasswordResetEmail($email, $resetLink);
        if (!$emailResult["success"]) {
            return $emailResult;
        }

        $db->commit();

        return ["success" => true, "message" => LANGUAGE["reset_email_sent"]];
    }

    public static function PasswordRecovery(string $password, string $cpassword, string $token): array
    {
        global $db;
        $db->beginTransaction();

        $validationResult = AuthValidator::validatePasswordRecovery($password, $cpassword);
        if (!$validationResult["success"]) {
            return $validationResult;
        }

        $userResult = self::get("password_reset_token", $token);
        if ($userResult["success"] === false) {
            return ["success" => false, "error" => LANGUAGE["invalid_token"]];
        }

        $user = $userResult["data"];

        if ($user["token_expiry"] < time()) {
            return ["success" => false, "error" => LANGUAGE["expired_token"]];
        }

        try {
            $data = [
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "password_reset_token" => null,
                "token_expiry" => null,
                "is_email_confirmed" => 1,
            ];

            self::sendPasswordChangeConfirmationEmail($user["email"]);

            $db->update("users", $data, ["id" => $user["id"]]);
            $db->commit();

            return ["success" => true, "message" => LANGUAGE["password_reset_success"]];
        } catch (Exception $e) {
            $db->rollBack();
            Response::serverError($e->getMessage(), $e->getTrace())->send();
            return ["success" => false, "error" => "Password reset failed. Please try again later."];
        }
    }

    public static function get(string $column, string $value): array
    {
        global $db;

        if (empty($column) || empty($value)) {
            return ["success" => false, "error" => "Invalid 'column' or 'value'."];
        }

        $users = $db->read("users", [$column => $value]);

        if (!empty($users) && count($users) > 0) {
            return ["success" => true, "data" => $users[0]];
        }

        return ["success" => false];
    }

    public static function isAuth(): array|bool
    {
        $token = $_COOKIE["token"] ?? null;

        if (!$token) {
            return false;
        }

        $jsonWebToken = new JsonWebToken(SETTINGS["jwt_secret_key"]);
        $payload = $jsonWebToken->decodeToken($token);

        if (!$payload) {
            return false;
        }

        $user = self::get("id", $payload["id"]);

        if (!$user) {
            return false;
        }

        return $user;
    }

    public static function logout()
    {
        setcookie("token", "", time() - 3600, "/", "", true, true);
    }

    public static function generateEmailConfirmationLink(string $id): array
    {
        $token = Generations::generateToken($id);

        $baseUrl = SETTINGS["website_link"] . "/auth/verify-email";
        $confirmationLink = $baseUrl . "?token=" . urlencode($token);

        return ["link" => $confirmationLink, "token" => $token];
    }

    // mails
    private static function sendSuccessRegistrationEmail(
        string $email,
    ): array {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["success_registration"],
        );

        $variables = [
            "website_link" => SETTINGS["website_link"],
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_display_name" => SETTINGS["website_display_name"],
            "website_phone" => SETTINGS["website_phone"],
        ];

        $mailManager->loadTemplate("success-registration", $variables);

        $result = $mailManager->send();
        return $result;
    }

    private static function sendConfirmationEmail(
        string $email,
        string $confirmationLink,
    ): array {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["confirmation_email"] . " - " . SETTINGS["website_display_name"],
        );

        $variables = [
            "website_link" => SETTINGS["website_link"],
            "confirmation_link" => $confirmationLink,
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_phone" => SETTINGS["website_phone"],
            "website_display_name" => SETTINGS["website_display_name"],
        ];

        $mailManager->loadTemplate("email-confirmation", $variables);

        $result = $mailManager->send();
        return $result;
    }

    private static function sendPasswordResetEmail(string $email, string $resetLink): array
    {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["password_reset_request"] . " - " . SETTINGS["website_display_name"],
        );

        $variables = [
            "reset_link" => $resetLink,
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_display_name" => SETTINGS["website_display_name"],
            "website_phone" => SETTINGS["website_phone"],
        ];

        $mailManager->loadTemplate("password-reset", $variables);

        $result = $mailManager->send();
        return $result;
    }

    private static function sendPasswordChangeConfirmationEmail(string $email): array
    {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["password_reset_success"] . " - " . SETTINGS["website_display_name"]
        );

        $variables = [
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_display_name" => SETTINGS["website_display_name"],
            "website_phone" => SETTINGS["website_phone"],
        ];

        $mailManager->loadTemplate("success-change-password", $variables);

        $result = $mailManager->send();
        return $result;
    }

    public static function sendEmailConfirmationSuccessEmail(string $email): array
    {
        $mailManager = new MailService(
            $email,
            SETTINGS["website_email"],
            LANGUAGE["email_confirmation_success"] . " - " . SETTINGS["website_display_name"]
        );

        $variables = [
            "email" => $email,
            "website_email" => SETTINGS["website_email"],
            "website_display_name" => SETTINGS["website_display_name"],
            "website_phone" => SETTINGS["website_phone"],
        ];

        $mailManager->loadTemplate("success-email-confirmation", $variables);

        $result = $mailManager->send();
        return $result;
    }
}
