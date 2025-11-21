<?php


function login_required() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }
}

function logged_in() {
    if (isset($_SESSION['username'])) {
        
        header("Location: home.php");
        exit;
    }
}