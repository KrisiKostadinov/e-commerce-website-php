<?php
    function getValue(string $key, ?array $array = null, ?string $default = ""): mixed {
        $array = $array ?? $_POST;
        return !empty($array[$key]) ? $array[$key] : $default;
    }
    $errorMessage = $_SESSION["error_message"] ?? null;
?>

<div class="secondary max-w-xl mx-5 lg:mx-auto mt-5 p-10 lg:p-10 border rounded shadow">
    <form action="/auth/login" method="POST" class="mx-auto grid gap-5">

        <?php if ($errorMessage): ?>
            <div class="text-red-500 text-center">
                <?= $errorMessage ?>
            </div>
            <?php unset($_SESSION["error_message"]) ?>
        <?php endif; ?>

        <div class="mb-4">
            <label for="email" class="block text-sm font-bold mb-2">Email: *</label>
            <input type="email" id="email" name="email" value="<?= getValue("email") ?>" placeholder="Пример: info@example.com" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-bold mb-2">Парола: *</label>
            <input type="password" id="password" name="password" value="<?= getValue("password") ?>" placeholder="Пример: ******" required>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Вход
            </button>
        </div>
    </form>
</div>

<div class="max-w-md mx-auto text-center">
    <a href="/auth/register" class="block my-5 text-link">Нямате профил?</a>
</div>
