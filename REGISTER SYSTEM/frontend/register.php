<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Auth System</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* Your CSS remains the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 480px;
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
            color: #f5576c;
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
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: #f5576c;
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
            background: #e74c5e;
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
            color: #f5576c;
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
        
        .password-hint {
            font-size: 0.8rem;
            color: #777;
            margin-top: 5px;
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
            <p class="tagline">Create your account</p>
            
            <div id="message" class="message"></div>
            
            <form id="registerForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                    <div class="password-hint">Must be at least 8 characters with letters and numbers</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <button type="submit" class="btn" id="registerBtn">Create Account</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='login.html'">Back to Login</button>
            </form>
            
            <div class="footer">
                Already have an account? <a href="login.html" class="link">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const messageDiv = document.getElementById('message');
            const registerBtn = document.getElementById('registerBtn');
            
            // Clear previous messages
            messageDiv.className = 'message';
            messageDiv.textContent = '';
            
            // Validate inputs
            if (!name || !email || !password || !confirmPassword) {
                showMessage('Please fill in all fields', 'error');
                return;
            }
            
            if (password !== confirmPassword) {
                showMessage('Passwords do not match', 'error');
                return;
            }
            
            if (password.length < 8) {
                showMessage('Password must be at least 8 characters long', 'error');
                return;
            }
            
            // Simple password strength check
            if (!/(?=.*[a-zA-Z])(?=.*\d)/.test(password)) {
                showMessage('Password must contain both letters and numbers', 'error');
                return;
            }
            
            // Disable button during request
            registerBtn.disabled = true;
            registerBtn.textContent = 'Creating Account...';
            
            // CORRECT PATH: Since frontend is in /frontend/ and backend in /backend/
            axios.post('../backend/apis/register.php', {
                name: name,
                email: email,
                password: password
            })
            .then(function(response) {
                console.log('API Response:', response.data);
                
                if (response.data.success) {
                    showMessage('Registration successful! Redirecting to login...', 'success');
                    
                    // Redirect to login page after successful registration
                    setTimeout(() => {
                        window.location.href = 'login.php?registered=true';
                    }, 2000);
                } else {
                    showMessage(response.data.message || 'Registration failed', 'error');
                }
            })
            .catch(function(error) {
                console.error('Registration error:', error);
                
                // Better error messages
                if (error.response) {
                    // Server responded with error
                    showMessage(`Error ${error.response.status}: ${error.response.data.message || 'Server error'}`, 'error');
                } else if (error.request) {
                    // Request made but no response
                    showMessage('Cannot connect to server. Please check if the server is running.', 'error');
                } else {
                    // Something else
                    showMessage('An error occurred. Please try again.', 'error');
                }
            })
            .finally(function() {
                // Re-enable button
                registerBtn.disabled = false;
                registerBtn.textContent = 'Create Account';
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
        
        // Test if API is accessible on page load
        window.addEventListener('load', function() {
            console.log('Testing API connection...');
            
            // Test the API endpoint
            axios.get('../backend/apis/register.php')
                .then(response => {
                    console.log('API is accessible!', response.data);
                })
                .catch(error => {
                    console.warn('API connection test failed:', error.message);
                    
                    // Try alternative paths if ../backend/ doesn't work
                    const testPaths = [
                        'http://localhost/REGISTER%20SYSTEM/backend/apis/register.php',
                        'http://localhost/REGISTER SYSTEM/backend/apis/register.php',
                        '/REGISTER SYSTEM/backend/apis/register.php',
                    
                        '../backend/apis/register.php'

                    ];
                    
                    testPaths.forEach(path => {
                        console.log('Trying path:', path);
                    });
                });
        });
    </script>
</body>
</html>