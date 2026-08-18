<?php
// ============================================
// ADMIN: Logout - Subhan Printers
// ============================================

session_start();

// Clear all admin session data
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);
session_destroy();

// Redirect to login
header('Location: /SP/admin/login.php');
exit;