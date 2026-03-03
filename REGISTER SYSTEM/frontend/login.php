<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auth System</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 420px;
        }
        
        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2575fc;
            margin-bottom: 10px;
        }
        
        .tagline {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }
        
        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #2575fc;
            box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: #2575fc;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: #1a67eb;
        }
        
        .btn-secondary {
            background: #6c757d;
            margin-top: 15px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            text-align: left;
            font-size: 0.9rem;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .link {
            color: #2575fc;
            text-decoration: none;
            font-weight: 500;
        }
        
        .link:hover {
            text-decoration: underline;
        }
        
        .footer {
            margin-top: 25px;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-card">
            <div class="logo">AuthApp</div>
            <p class="tagline">Sign in to your account</p>
            
            <div id="message" class="message"></div>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn" id="loginBtn">Sign In</button>
            </form>
            
            <div class="footer">
                Don't have an account? <a href="register.html" class="link">Create one here</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageDiv = document.getElementById('message');
            const loginBtn = document.getElementById('loginBtn');
            
            // Clear previous messages
            messageDiv.className = 'message';
            messageDiv.textContent = '';
            
            // Validate inputs
            if (!email || !password) {
                showMessage('Please fill in all fields', 'error');
                return;
            }
            
            // Disable button during request
            loginBtn.disabled = true;
            loginBtn.textContent = 'Signing In...';
            
            // Send login request via Axios
            axios.post('../backend/apis/login.php', {
                email: email,
                password: password
            })
            .then(function(response) {
                console.log('Login response:', response.data);
                
                if (response.data.success) {
                    showMessage('Login successful! Redirecting to dashboard...', 'success');
                    
                    // Store user data in localStorage for dashboard
                    if (response.data.user) {
                        localStorage.setItem('userData', JSON.stringify(response.data.user));
                    }
                    
                    // Store session token if provided
                    if (response.data.token) {
                        localStorage.setItem('authToken', response.data.token);
                    }
                    
                    // Redirect to dashboard after successful login
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1500);
                } else {
                    showMessage(response.data.message || 'Login failed', 'error');
                }
            })
            .catch(function(error) {
                console.error('Login error:', error);
                if (error.response) {
                    showMessage(`Error ${error.response.status}: ${error.response.data.message || 'Server error'}`, 'error');
                } else if (error.request) {
                    showMessage('Cannot connect to server. Please check if the server is running.', 'error');
                } else {
                    showMessage('An error occurred. Please try again.', 'error');
                }
            })
            .finally(function() {
                // Re-enable button
                loginBtn.disabled = false;
                loginBtn.textContent = 'Sign In';
            });
        });
        
        function showMessage(text, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = text;
            messageDiv.className = `message ${type}`;
            
            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    messageDiv.className = 'message';
                }, 5000);
            }
        }
        
        // Check if there's a success message from registration redirect
        const urlParams = new URLSearchParams(window.location.search);
        const registered = urlParams.get('registered');
        
        if (registered === 'true') {
            showMessage('Registration successful! Please log in.', 'success');
        }
        
        // Check if user is already logged in (for demo purposes)
        window.addEventListener('load', function() {
            const userData = localStorage.getItem('userData');
            if (userData) {
                console.log('User already logged in:', JSON.parse(userData));
                // Optional: Auto-redirect if already logged in
                // window.location.href = 'dashboard.php';
            }
            
            // Test API connection
            axios.get('../backend/apis/login.php')
                .then(response => {
                    console.log('Login API is accessible');
                })
                .catch(error => {
                    console.warn('Login API may not be accessible:', error.message);
                });
        });
    </script>
</body>
</html>