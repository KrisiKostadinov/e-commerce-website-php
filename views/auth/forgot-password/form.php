<?php
    function getValue(string $key, ?array $array = null, ?string $default = ""): mixed {
        $array = $array ?? $_POST;
        return !empty($array[$key]) ? $array[$key] : $default;
    }
    $errorMessage = $_SESSION["error_message"] ?? null;
    $successMessage = $_SESSION["success_message"] ?? null;
?>

<div class="secondary max-w-xl mx-5 lg:mx-auto mt-5 p-10 lg:p-10 border rounded shadow">
    <form id="form" action="/auth/forgot-password" method="POST" class="mx-auto">

        <?php if ($errorMessage): ?>
            <div class="text-red-500 text-center mb-5">
                <?= $errorMessage ?>
            </div>
            <?php unset($_SESSION["error_message"]) ?>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="text-green-500 text-center mb-5">
                <?= $successMessage ?>
            </div>
            <?php unset($_SESSION["success_message"]) ?>
        <?php endif; ?>

        <div class="mb-4">
            <label for="email" class="block text-sm font-bold mb-2">Email: <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" value="<?= getValue("email") ?>" required autofocus>
        </div>

        <input type="text" name="secure_token" value="<?= $_SESSION["secure_token"] ?? "" ?>" hidden>
        
        <div>
            <button type="submit" class="button primary">
                Изпращане на линк
            </button>
        </div>

        <div class="mt-5">
            <a href="/auth/login" class="text-link">Отиване към вход</a>
        </div>
    </form>
</div>
