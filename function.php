<?php

function debug($variable)
{
    echo '<pre>' . Print_r($variable, true) . '</pre>';
}

function str_random($length)
{
    $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['auth'])) {
        $_SESSION['flash']['danger'] = "Vous n'avez pas droit d'acceder a cette page";
        header('Location: login.php');
        exit();
    }
}
