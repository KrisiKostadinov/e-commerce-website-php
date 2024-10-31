<?php require "views/includes/header.php"; ?>

<header>
    <?php require "views/includes/navigation.php"; ?>
</header>

<main>
    <h1 class="text-2xl font-bold text-center mt-5"><?= LANGUAGE["forgot_password"] ?></h1>

    <p class="text-center">Полетата със звездичка са задължителни.</p>

    <?php require "form.php"; ?>
</main>

<?php require "views/includes/footer.php"; ?>
