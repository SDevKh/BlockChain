<?php
session_start();
include 'db.php';  // Include the database connection

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserName() {
    global $pdo;
    if (isLoggedIn()) {
        // Debugging: Check if user_id is set
        if (!isset($_SESSION['user_id'])) {
            error_log("Session user_id is not set.");
            return "Guest";
        }

        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        // Debugging: Check if the query returned a result
        if (!$user) {
            error_log("No user found for user_id: " . $_SESSION['user_id']);
            return "Guest";
        }

        if (isset($user['name'])) {
            return $user['name'];
        }
    }
    return "Guest"; // Default fallback if the user is not found
}

function getUserBalance() {
    global $pdo;
    if (isLoggedIn()) {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        // Check if the query returned a result
        if ($user && isset($user['balance'])) {
            return $user['balance'];
        }
    }
    return 0; // Default balance if not found
}

function getUserAddress() {
    global $pdo;
    if (isLoggedIn()) {
        $stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        // Check if the query returned a result
        if ($user && isset($user['address'])) {
            return $user['address'];
        }
    }
    return ""; // Default address if not found
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function getUserEmail() {
    return isset($_SESSION["email"]) ? $_SESSION["email"] : "";
}

function getUserId() {
    return isset($_SESSION["id"]) ? $_SESSION["id"] : 0;
}

function logoutUser() {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>