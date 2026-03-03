<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auth System</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --sidebar-width: 250px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        .logo {
            padding: 25px 20px;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo i {
            margin-right: 12px;
            font-size: 2rem;
        }
        
        .nav-menu {
            flex: 1;
            padding: 20px 0;
        }
        
        .nav-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--success-color);
        }
        
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: white;
        }
        
        .nav-item i {
            width: 25px;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .user-profile {
            padding: 20px;
            display: flex;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        
        .user-info h4 {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        
        .user-info p {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px 0;
            border-bottom: 1px solid #eaeaea;
        }
        
        .header h1 {
            color: var(--dark-color);
            font-size: 1.8rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #e11570;
        }
        
        .logout-btn i {
            margin-right: 8px;
        }
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 1rem;
            color: #666;
            font-weight: 500;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .card-icon.users {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-icon.activity {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .card-icon.visits {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .card-icon.growth {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .card-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .card-change {
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .card-change.positive {
            color: #10b981;
        }
        
        .card-change.negative {
            color: #ef4444;
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .chart-container h3 {
            margin-bottom: 20px;
            color: var(--dark-color);
            font-size: 1.3rem;
        }
        
        /* Recent Activity */
        .recent-activity {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }
        
        .recent-activity h3 {
            margin-bottom: 20px;
            color: var(--dark-color);
            font-size: 1.3rem;
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1rem;
        }
        
        .activity-icon.login {
            background-color: var(--primary-color);
        }
        
        .activity-icon.register {
            background-color: var(--success-color);
        }
        
        .activity-icon.update {
            background-color: var(--warning-color);
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-details h4 {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        
        .activity-details p {
            font-size: 0.9rem;
            color: #666;
        }
        
        .activity-time {
            font-size: 0.85rem;
            color: #888;
        }
        
        /* Responsive Design */
        @media (max-width: 1100px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo span, 
            .sidebar .nav-item span,
            .sidebar .user-info {
                display: none;
            }
            
            .sidebar .logo {
                justify-content: center;
            }
            
            .sidebar .logo i {
                margin-right: 0;
            }
            
            .sidebar .nav-item {
                justify-content: center;
                padding: 20px 0;
            }
            
            .sidebar .nav-item i {
                margin-right: 0;
            }
            
            .sidebar .user-profile {
                justify-content: center;
                padding: 20px 10px;
            }
            
            .sidebar .user-avatar {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-header h3 {
            color: var(--dark-color);
            font-size: 1.5rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-secondary {
            background-color: #eaeaea;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #ddd;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            display: flex;
            align-items: center;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast.success {
            background-color: #10b981;
        }
        
        .toast.error {
            background-color: #ef4444;
        }
        
        .toast i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
            <span>AuthDash</span>
        </div>
        
        <div class="nav-menu">
            <div class="nav-item active" data-page="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item" data-page="users">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </div>
            <div class="nav-item" data-page="activity">
                <i class="fas fa-history"></i>
                <span>Activity Log</span>
            </div>
            <div class="nav-item" data-page="settings">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
            <div class="nav-item" data-page="profile">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </div>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar" id="userAvatar">JD</div>
            <div class="user-info">
                <h4 id="userName">John Doe</h4>
                <p id="userEmail">john@example.com</p>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1 id="pageTitle">Dashboard Overview</h1>
            <div class="header-actions">
                <button class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
        
        <!-- Dashboard Page -->
        <div id="dashboardPage" class="page-content">
            <!-- Stats Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Users</h3>
                        <div class="card-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value" id="totalUsers">0</div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Active Sessions</h3>
                        <div class="card-icon activity">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="card-value" id="activeSessions">0</div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> 5% from yesterday
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Today's Visits</h3>
                        <div class="card-icon visits">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-value" id="todaysVisits">0</div>
                    <div class="card-change negative">
                        <i class="fas fa-arrow-down"></i> 3% from yesterday
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Growth Rate</h3>
                        <div class="card-icon growth">
                            <i class="fas fa-seedling"></i>
                        </div>
                    </div>
                    <div class="card-value" id="growthRate">0%</div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> 8% from last week
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-container">
                    <h3>User Signups (Last 7 Days)</h3>
                    <canvas id="signupsChart"></canvas>
                </div>
                
                <div class="chart-container">
                    <h3>Login Sources</h3>
                    <canvas id="sourcesChart"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="recent-activity">
                <h3>Recent Activity</h3>
                <ul class="activity-list" id="activityList">
                    <!-- Activity items will be added here by JavaScript -->
                </ul>
            </div>
        </div>
        
        <!-- Users Page (hidden by default) -->
        <div id="usersPage" class="page-content" style="display: none;">
            <h2 style="margin-bottom: 20px;">User Management</h2>
            <div style="background: white; border-radius: 12px; padding: 25px;">
                <p>User management features will be implemented here.</p>
                <p>You can list, edit, and delete users from this section.</p>
            </div>
        </div>
        
        <!-- Activity Log Page (hidden by default) -->
        <div id="activityPage" class="page-content" style="display: none;">
            <h2 style="margin-bottom: 20px;">Activity Log</h2>
            <div style="background: white; border-radius: 12px; padding: 25px;">
                <p>Detailed activity log will be displayed here.</p>
                <p>This includes login attempts, profile updates, and other user actions.</p>
            </div>
        </div>
        
        <!-- Settings Page (hidden by default) -->
        <div id="settingsPage" class="page-content" style="display: none;">
            <h2 style="margin-bottom: 20px;">System Settings</h2>
            <div style="background: white; border-radius: 12px; padding: 25px;">
                <p>System configuration settings will be available here.</p>
                <p>This includes security settings, email configuration, and other system options.</p>
            </div>
        </div>
        
        <!-- Profile Page (hidden by default) -->
        <div id="profilePage" class="page-content" style="display: none;">
            <h2 style="margin-bottom: 20px;">My Profile</h2>
            <div style="background: white; border-radius: 12px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 30px;">
                    <div class="user-avatar" id="profileAvatar" style="width: 80px; height: 80px; font-size: 2rem;">JD</div>
                    <div style="margin-left: 20px;">
                        <h3 id="profileName">John Doe</h3>
                        <p id="profileEmail">john@example.com</p>
                        <button id="editProfileBtn" style="margin-top: 10px; background: var(--primary-color); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer;">
                            Edit Profile
                        </button>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h4 style="margin-bottom: 15px;">Account Information</h4>
                        <p><strong>Member Since:</strong> <span id="memberSince">January 2023</span></p>
                        <p><strong>Last Login:</strong> <span id="lastLogin">Today, 10:30 AM</span></p>
                        <p><strong>Account Status:</strong> <span style="color: #10b981; font-weight: 600;">Active</span></p>
                    </div>
                    
                    <div>
                        <h4 style="margin-bottom: 15px;">Security</h4>
                        <button id="changePasswordBtn" style="display: block; width: 100%; margin-bottom: 10px; padding: 10px; background: #eaeaea; border: none; border-radius: 6px; cursor: pointer;">
                            Change Password
                        </button>
                        <button id="twoFactorBtn" style="display: block; width: 100%; padding: 10px; background: #eaeaea; border: none; border-radius: 6px; cursor: pointer;">
                            Two-Factor Authentication
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profile</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form id="editProfileForm">
                <div class="form-group">
                    <label for="editName">Full Name</label>
                    <input type="text" id="editName" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email Address</label>
                    <input type="email" id="editEmail" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Password</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form id="changePasswordForm">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmNewPassword">Confirm New Password</label>
                    <input type="password" id="confirmNewPassword" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Operation completed successfully!</span>
    </div>
    
    <script>
        // Check if user is logged in (in a real app, check session/token)
        document.addEventListener('DOMContentLoaded', function() {
            // Try to get user data from localStorage (simulated session)
            const userData = JSON.parse(localStorage.getItem('userData'));
            
            if (!userData) {
                // Redirect to login if no user data
                window.location.href = 'index.html';
                return;
            }
            
            // Set user information
            document.getElementById('userName').textContent = userData.name || 'John Doe';
            document.getElementById('userEmail').textContent = userData.email || 'john@example.com';
            document.getElementById('userAvatar').textContent = getInitials(userData.name || 'John Doe');
            
            // Set profile information
            document.getElementById('profileName').textContent = userData.name || 'John Doe';
            document.getElementById('profileEmail').textContent = userData.email || 'john@example.com';
            document.getElementById('profileAvatar').textContent = getInitials(userData.name || 'John Doe');
            document.getElementById('editName').value = userData.name || 'John Doe';
            document.getElementById('editEmail').value = userData.email || 'john@example.com';
            
            // Initialize dashboard
            initializeDashboard();
            initializeCharts();
            loadRecentActivity();
            
            // Set current date for member since
            const currentDate = new Date();
            const options = { year: 'numeric', month: 'long' };
            document.getElementById('memberSince').textContent = currentDate.toLocaleDateString('en-US', options);
            
            // Set last login time
            const now = new Date();
            const lastLoginStr = now.toLocaleDateString('en-US', { weekday: 'long', hour: '2-digit', minute: '2-digit' });
            document.getElementById('lastLogin').textContent = lastLoginStr;
        });
        
        // Helper function to get initials from name
        function getInitials(name) {
            return name.split(' ').map(part => part[0]).join('').toUpperCase().substring(0, 2);
        }
        
        // Initialize dashboard stats
        function initializeDashboard() {
            // Simulated data - in a real app, fetch from API
            document.getElementById('totalUsers').textContent = Math.floor(Math.random() * 500) + 1000;
            document.getElementById('activeSessions').textContent = Math.floor(Math.random() * 50) + 20;
            document.getElementById('todaysVisits').textContent = Math.floor(Math.random() * 200) + 100;
            document.getElementById('growthRate').textContent = (Math.random() * 15 + 5).toFixed(1) + '%';
        }
        
        // Initialize charts
        function initializeCharts() {
            // Signups Chart (Line)
            const signupsCtx = document.getElementById('signupsChart').getContext('2d');
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const signupsData = days.map(() => Math.floor(Math.random() * 30) + 10);
            
            new Chart(signupsCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'New Users',
                        data: signupsData,
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Sources Chart (Doughnut)
            const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
            
            new Chart(sourcesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Direct', 'Social', 'Email', 'Referral'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: [
                            '#4361ee',
                            '#3a0ca3',
                            '#4cc9f0',
                            '#f72585'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Load recent activity
        function loadRecentActivity() {
            const activityList = document.getElementById('activityList');
            const activities = [
                { type: 'login', user: 'John Doe', time: '10:30 AM', details: 'Logged in from Chrome on Windows' },
                { type: 'register', user: 'Jane Smith', time: '9:15 AM', details: 'New user registration' },
                { type: 'update', user: 'Robert Johnson', time: 'Yesterday, 3:45 PM', details: 'Updated profile information' },
                { type: 'login', user: 'Sarah Williams', time: 'Yesterday, 1:20 PM', details: 'Logged in from Safari on Mac' },
                { type: 'update', user: 'Michael Brown', time: 'Oct 12, 11:30 AM', details: 'Changed password' }
            ];
            
            activityList.innerHTML = '';
            
            activities.forEach(activity => {
                const activityItem = document.createElement('li');
                activityItem.className = 'activity-item';
                
                let iconClass = '';
                let iconText = '';
                
                switch(activity.type) {
                    case 'login':
                        iconClass = 'login';
                        iconText = '<i class="fas fa-sign-in-alt"></i>';
                        break;
                    case 'register':
                        iconClass = 'register';
                        iconText = '<i class="fas fa-user-plus"></i>';
                        break;
                    case 'update':
                        iconClass = 'update';
                        iconText = '<i class="fas fa-edit"></i>';
                        break;
                }
                
                activityItem.innerHTML = `
                    <div class="activity-icon ${iconClass}">${iconText}</div>
                    <div class="activity-details">
                        <h4>${activity.user}</h4>
                        <p>${activity.details}</p>
                    </div>
                    <div class="activity-time">${activity.time}</div>
                `;
                
                activityList.appendChild(activityItem);
            });
        }
        
        // Navigation between pages
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                // Update active nav item
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // Get page to show
                const pageId = this.getAttribute('data-page') + 'Page';
                
                // Hide all pages
                document.querySelectorAll('.page-content').forEach(page => {
                    page.style.display = 'none';
                });
                
                // Show selected page
                document.getElementById(pageId).style.display = 'block';
                
                // Update page title
                const pageTitle = this.querySelector('span').textContent;
                document.getElementById('pageTitle').textContent = pageTitle;
            });
        });
        
        // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            // Clear user data from localStorage
            localStorage.removeItem('userData');
            
            // In a real app, you would also call a logout API endpoint
            
            // Redirect to login page
            window.location.href = 'index.html';
        });
        
        // Modal functionality
        const modals = document.querySelectorAll('.modal');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        
        // Open edit profile modal
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            document.getElementById('editProfileModal').style.display = 'flex';
        });
        
        // Open change password modal
        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            document.getElementById('changePasswordModal').style.display = 'flex';
        });
        
        // Close modals
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                modals.forEach(modal => modal.style.display = 'none');
            });
        });
        
        // Close modal when clicking outside
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });
        
        // Edit profile form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newName = document.getElementById('editName').value;
            const newEmail = document.getElementById('editEmail').value;
            
            // Update user data in localStorage (simulated)
            const userData = JSON.parse(localStorage.getItem('userData')) || {};
            userData.name = newName;
            userData.email = newEmail;
            localStorage.setItem('userData', JSON.stringify(userData));
            
            // Update UI
            document.getElementById('userName').textContent = newName;
            document.getElementById('userEmail').textContent = newEmail;
            document.getElementById('userAvatar').textContent = getInitials(newName);
            document.getElementById('profileName').textContent = newName;
            document.getElementById('profileEmail').textContent = newEmail;
            document.getElementById('profileAvatar').textContent = getInitials(newName);
            
            // Close modal
            document.getElementById('editProfileModal').style.display = 'none';
            
            // Show success message
            showToast('Profile updated successfully!', 'success');
        });
        
        // Change password form submission
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;
            
            // Basic validation
            if (newPassword !== confirmNewPassword) {
                showToast('New passwords do not match!', 'error');
                return;
            }
            
            if (newPassword.length < 8) {
                showToast('Password must be at least 8 characters long!', 'error');
                return;
            }
            
            // In a real app, you would send this to your API
            // For now, just simulate success
            setTimeout(() => {
                // Clear form
                document.getElementById('changePasswordForm').reset();
                
                // Close modal
                document.getElementById('changePasswordModal').style.display = 'none';
                
                // Show success message
                showToast('Password changed successfully!', 'success');
            }, 1000);
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.className = `toast ${type} show`;
            
            // Change icon based on type
            const icon = toast.querySelector('i');
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else {
                icon.className = 'fas fa-exclamation-circle';
            }
            
            // Hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Two-factor authentication button
        document.getElementById('twoFactorBtn').addEventListener('click', function() {
            showToast('Two-factor authentication feature will be available soon!', 'success');
        });
        
        // Simulate API calls for dashboard data
        /*
        function fetchDashboardData() {
             In a real app, you would fetch this from your API
         Example:
            axios.get('api/dashboard.php')
               .then(response => {
                 Update dashboard with real data
             })
         .catch(error => {
                console.error('Error fetching dashboard data:', error);
             });
            
            // For now, we're using simulated data
            setTimeout(() => {
                initializeDashboard();
                loadRecentActivity();
            }, 100);
        }
        */
        // Refresh dashboard data every 30 seconds (optional)
        setInterval(fetchDashboardData, 30000);
    </script>
</body>
</html>