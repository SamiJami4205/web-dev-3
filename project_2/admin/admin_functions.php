<?php
// Check if user is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

// Admin specific functions
function get_user_stats($pdo) {
    $stats = array();
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $stats['total_users'] = $stmt->fetch()['total'];
    
    // Users by gender
    $stmt = $pdo->query("SELECT gender, COUNT(*) as count FROM users GROUP BY gender");
    $stats['users_by_gender'] = $stmt->fetchAll();
    
    // New users this month
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE MONTH(registration_date) = MONTH(CURRENT_DATE())");
    $stats['new_users_this_month'] = $stmt->fetch()['count'];
    
    return $stats;
}

// Function to get users with pagination and search
function get_users_paginated($pdo, $page = 1, $per_page = 10, $search = '') {
    $offset = ($page - 1) * $per_page;
    
    $where = '';
    $params = [];
    
    if (!empty($search)) {
        $where = "WHERE (username LIKE :search OR email LIKE :search OR first_name LIKE :search OR last_name LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    // Get total count
    $count_sql = "SELECT COUNT(*) FROM users $where";
    $stmt = $pdo->prepare($count_sql);
    if (!empty($params)) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    $total_records = $stmt->fetchColumn();
    
    // Get users
    $sql = "SELECT * FROM users $where ORDER BY registration_date DESC LIMIT :offset, :per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    return [
        'users' => $users,
        'total_records' => $total_records,
        'total_pages' => ceil($total_records / $per_page),
        'current_page' => $page,
        'per_page' => $per_page
    ];
}
?>