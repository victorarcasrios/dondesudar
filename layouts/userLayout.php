<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>DondeSudar</title>
        <?php require "assets/css/cssStylesManager.php" ?>
    </head>
    <body>
        <div class="container-fluid">
            <header class='page-header'>
                <h1><?= $header ?></h1>
                <div class='pull-right'><?php require "layouts/bars/langSelect.php"; ?></div>
            </header>
            <?php require "layouts/bars/userNav.php"; ?>
            <div class="container">
                <?= $content ?>
            </div>
        </div>
        <!-- JS SCRIPTS -->
        <?php require "assets/js/jsScripts.php"; ?>
    </body>
</html>