<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'admin';
}

function isCustomer() {
    return isLoggedIn()
        && isset($_SESSION['role'])
        && $_SESSION['role'] === 'customer';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../login.php");
        exit;
    }
}

function requireCustomer() {
    if (!isCustomer()) {
        header("Location: login.php");
        exit;
    }
}

function formatPrice($price) {
    return '€' . number_format((float)$price, 2);
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function generateOrderNumber() {
    return 'SNK-' . strtoupper(bin2hex(random_bytes(5)));
}

function isEmpty($value) {
    return empty(trim((string)$value));
}

?>