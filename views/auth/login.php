<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - MFY Boshqaruv Tizimi</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.3);
        }
        
        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 32px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        
        .input-group-text {
            background: rgba(102, 126, 234, 0.1);
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #667eea;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-left: 4px solid #28a745;
        }
        
        .copyright {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
            justify-content: center;
        }
        
        .security-badge i {
            color: #28a745;
        }
        
        @media (max-width: 768px) {
            .glass-card {
                padding: 30px 20px;
            }
            
            .language-switcher {
                position: static;
                text-align: center;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Language Switcher -->
    <div class="language-switcher">
        <select class="form-select form-select-sm" style="width: auto;" id="languageSelect">
            <option value="uz" selected>O'zbekcha</option>
            <option value="ru">Русский</option>
            <option value="en">English</option>
        </select>
    </div>
    
    <div class="login-container">
        <div class="glass-card">
            <!-- Logo -->
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #333;">MFY Boshqaruv Tizimi</h2>
                <p class="text-muted mb-0">Xavfsiz kirish paneli</p>
            </div>
            
            <!-- Error/Success Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="/login" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Foydalanuvchi nomi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               required
                               placeholder="Foydalanuvchi nomingiz"
                               autocomplete="username">
                    </div>
                </div>
                
                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Parol</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Parolingiz"
                               autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-text mt-2">
                        <a href="/forgot-password" class="text-decoration-none">
                            <i class="fas fa-key me-1"></i>Parolni unutdingizmi?
                        </a>
                    </div>
                </div>
                
                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Eslab qolish
                        </label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Kirish
                </button>
                
                <!-- Divider -->
                <div class="position-relative my-4">
                    <hr>
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                        yoki
                    </span>
                </div>
                
                <!-- Alternative Login -->
                <div class="text-center">
                    <p class="mb-2">Boshqa hisob bilan kirish</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fab fa-google"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fab fa-microsoft"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fab fa-github"></i>
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Security Badge -->
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>256-bit SSL shifrlash | Xavfsiz sessiya | 2FA qo'llab-quvvatlanadi</span>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="copyright">
            <p>&copy; 2026 MFY Boshqaruv Tizimi. Barcha huquqlar himoyalangan.</p>
            <p class="small">
                <a href="/privacy" class="text-white-50 text-decoration-none me-3">Maxfiylik siyosati</a>
                <a href="/terms" class="text-white-50 text-decoration-none">Foydalanish shartlari</a>
            </p>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Language switching
        document.getElementById('languageSelect').addEventListener('change', function() {
            const lang = this.value;
            const translations = {
                'uz': {
                    title: 'MFY Boshqaruv Tizimi',
                    subtitle: 'Xavfsiz kirish paneli',
                    username: 'Foydalanuvchi nomi',
                    password: 'Parol',
                    remember: 'Eslab qolish',
                    login: 'Kirish',
                    forgot: 'Parolni unutdingizmi?',
                    or: 'yoki',
                    alt: 'Boshqa hisob bilan kirish'
                },
                'ru': {
                    title: 'Система Управления МФЙ',
                    subtitle: 'Безопасная панель входа',
                    username: 'Имя пользователя',
                    password: 'Пароль',
                    remember: 'Запомнить меня',
                    login: 'Войти',
                    forgot: 'Забыли пароль?',
                    or: 'или',
                    alt: 'Войти через другую учетную запись'
                },
                'en': {
                    title: 'MFY Management System',
                    subtitle: 'Secure login panel',
                    username: 'Username',
                    password: 'Password',
                    remember: 'Remember me',
                    login: 'Login',
                    forgot: 'Forgot password?',
                    or: 'or',
                    alt: 'Login with another account'
                }
            };
            
            const t = translations[lang];
            
            // Update all text elements
            document.querySelector('h2').textContent = t.title;
            document.querySelector('.logo-container p').textContent = t.subtitle;
            document.querySelector('label[for="username"]').textContent = t.username;
            document.querySelector('label[for="password"]').textContent = t.password;
            document.querySelector('label[for="remember"]').textContent = t.remember;
            document.querySelector('.btn-primary').innerHTML = `<i class="fas fa-sign-in-alt me-2"></i>${t.login}`;
            document.querySelector('a[href="/forgot-password"]').innerHTML = `<i class="fas fa-key me-1"></i>${t.forgot}`;
            document.querySelector('.position-absolute span').textContent = t.or;
            document.querySelector('.text-center p').textContent = t.alt;
            
            // Update placeholders
            document.getElementById('username').placeholder = t.username;
            document.getElementById('password').placeholder = t.password;
        });
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Kirish...';
            
            return true;
        });
        
        // Auto focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>