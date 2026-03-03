<?php
include_once '../config/db.php';

setJsonHeaders();

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$action = $_GET['action'] ?? 'stats';

switch ($action) {
    case 'stats':
        getDashboardStats();
        break;
    case 'activity':
        getRecentActivity();
        break;
    case 'update-profile':
        updateUserProfile();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getDashboardStats() {
    $conn = getDBConnection();
    $userId = $_SESSION['user_id'];
    
    // Get total users
    $totalUsers = 0;
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($result) {
        $row = $result->fetch_assoc();
        $totalUsers = $row['count'];
    }
    
    // Get today's logins
    $todayLogins = 0;
    $today = date('Y-m-d');
    $result = $conn->query("SELECT COUNT(*) as count FROM login_log WHERE DATE(login_time) = '$today'");
    if ($result) {
        $row = $result->fetch_assoc();
        $todayLogins = $row['count'];
    }
    
    // Get active sessions (last 30 minutes)
    $activeSessions = 0;
    $thirtyMinutesAgo = date('Y-m-d H:i:s', strtotime('-30 minutes'));
    $result = $conn->query("SELECT COUNT(DISTINCT user_id) as count FROM login_log WHERE login_time > '$thirtyMinutesAgo'");
    if ($result) {
        $row = $result->fetch_assoc();
        $activeSessions = $row['count'];
    }
    
    // Get weekly signups
    $weeklySignups = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $dayName = date('D', strtotime($date));
        
        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = '$date'");
        $count = 0;
        if ($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
        }
        
        $weeklySignups[] = [
            'day' => $dayName,
            'count' => $count
        ];
    }
    
    $conn->close();
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalUsers' => $totalUsers,
            'todayLogins' => $todayLogins,
            'activeSessions' => $activeSessions,
            'weeklySignups' => $weeklySignups
        ]
    ]);
}

function getRecentActivity() {
    $conn = getDBConnection();
    
    $activities = [];
    $result = $conn->query("
        SELECT u.name, l.login_time, l.ip_address, l.user_agent 
        FROM login_log l 
        JOIN users u ON l.user_id = u.id 
        ORDER BY l.login_time DESC 
        LIMIT 10
    ");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $activities[] = [
                'user' => $row['name'],
                'time' => date('h:i A', strtotime($row['login_time'])),
                'date' => date('M d', strtotime($row['login_time'])),
                'details' => "Logged in from " . ($row['ip_address'] ?? 'unknown IP')
            ];
        }
    }
    
    $conn->close();
    
    echo json_encode([
        'success' => true,
        'activities' => $activities
    ]);
}

function updateUserProfile() {
    $conn = getDBConnection();
    $userId = $_SESSION['user_id'];
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['name']) || !isset($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $name = trim($data['name']);
    $email = trim($data['email']);
    
    // Check if email is already taken by another user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $userId);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already in use']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
    
    // Update user
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $userId);
    
    if ($stmt->execute()) {
        // Update session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }
    
    $stmt->close();
    $conn->close();
}
?>