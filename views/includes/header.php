<!DOCTYPE html>
<html lang="<?= $_SESSION["language"] ?>">

<head>
    <meta charset="<?= SETTINGS["charset"] ?? "UTF-8" ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/css/styles.css">

    <?php if (!empty($metaTags)): ?>
        <?= $metaTags ?>
    <?php endif; ?>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body data-theme="<?= $_SESSION["theme"] ?? "dark" ?>">