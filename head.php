<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php if(isset($style)) echo $style; ?>">
    <title><?php if(isset($title)) echo $title; ?></title>
    <?php include("styleFooter.php"); ?>
</head>
<body>
    <?php
    session_start();
    ?>