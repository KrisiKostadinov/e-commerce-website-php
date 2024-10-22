<?php
    function getValue(string $key, ?array $array = null, ?string $default = ""): mixed {
        $array = $array ?? $_POST;
        return !empty($array[$key]) ? $array[$key] : $default;
    }
    $errorMessage = $_SESSION["error_message"] ?? null;
?>

<div class="secondary max-w-4xl mx-5 lg:mx-auto mt-5 p-5 lg:p-10 border rounded shadow">
    <form action="/auth/register" method="POST" class="mx-auto grid gap-5">

        <?php if ($errorMessage): ?>
            <div class="text-red-500 text-center">
                <?= $errorMessage ?>
            </div>
            <?php unset($_SESSION["error_message"]) ?>
        <?php endif; ?>

        <div class="grid lg:grid-cols-2 gap-10">
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">Email: *</label>
                <input type="email" id="email" name="email" value="<?= getValue("email") ?>" placeholder="Пример: info@example.com" required autofocus>
            </div>
    
            <div class="mb-4">
                <label for="password" class="block text-sm font-bold mb-2">Парола: *</label>
                <input type="password" id="password" name="password" value="<?= getValue("password") ?>" placeholder="Пример: ******" required>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10">
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-bold mb-2">Име: *</label>
                <input type="text" id="first_name" value="<?= getValue("first_name") ?>" name="first_name" placeholder="Пример: Петър" required>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-bold mb-2">Фамилия: *</label>
                <input type="text" id="last_name" value="<?= getValue("last_name") ?>" name="last_name" placeholder="Пример: Петров" required>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10">
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-bold mb-2">Телефонен номер:</label>
                <input type="tel" id="phone_number" value="<?= getValue("phone_number") ?>" placeholder="Пример: 0123456789" name="phone_number">
            </div>
    
            <div class="mb-4">
                <label for="address" class="block text-sm font-bold mb-2">Адрес:</label>
                <input type="text" id="address" name="address" value="<?= getValue("address") ?>" placeholder="Пример: ул. Слотска 22">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-10">
            <div class="mb-4">
                <label for="country" class="block text-sm font-bold mb-2">Държава:</label>
                <input type="text" id="country" name="country" value="<?= getValue("country") ?>" placeholder="Пример: България">
            </div>

            <div class="mb-4">
                <label for="state" class="block text-sm font-bold mb-2">Областен град:</label>
                <input type="text" id="state" name="state" value="<?= getValue("state") ?>" placeholder="Пример: София">
            </div>

            <div class="mb-4">
                <label for="city" class="block text-sm font-bold mb-2">Град:</label>
                <input type="text" id="city" name="city" value="<?= getValue("city") ?>" placeholder="Пример: София град">
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Регистрация
            </button>
        </div>
    </form>
</div>

<div class="max-w-md mx-auto text-center">
    <a href="/auth/login" class="block my-5 text-link">Вече имате профил?</a>

    <div class="px-10 mb-5">
        <p>С регистрацията си, вие се съгласявате с нашите <a href="your_privacy_policy_link.php" class="text-link">Правила за поверителност</a>.</p>
    </div>
</div>
