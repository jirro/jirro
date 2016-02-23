<?php
    $applicationTitle = $applicationName;
    $pageTitle        = $applicationTitle;
    $pageHeader       = $applicationTitle;

    if (isset($title)) {
        $pageTitle  = $applicationName . ' - ' . $title;
        $pageHeader = $title;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Rendy Eko Prastiyo">

    <title><?php print $this->e($pageTitle); ?></title>

</head>

<body>

    <?php print $this->section('content'); ?>

    <!-- Debug Bar Plugin -->
    <?php
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            print $this->debugBar()->renderHead();
            print $this->debugBar()->render();
        }
    ?>

</body>

</html>
