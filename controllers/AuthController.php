<?php
// controllers/AuthController.php
class AuthController {
    
    public function login() {
        // CSRF token yaratish
        $csrfToken = Security::generateCSRFToken();
        
        // Login form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../views/auth/login.php';
            return;
        }
        
        // Login amaliyoti
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF tekshirish
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid or expired";
                header('Location: /login');
                exit;
            }
            
            $username = Security::sanitize($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);
            
            // Failed attempts tekshirish
            $userModel = new User();
            $ip = Security::getClientIP();
            
            if ($userModel->checkFailedAttempts($ip) >= MAX_LOGIN_ATTEMPTS) {
                $_SESSION['error'] = "Too many failed attempts. Please try again later.";
                header('Location: /login');
                exit;
            }
            
            // Foydalanuvchi topish
            $user = $userModel->findByUsername($username);
            
            if (!$user || !Security::verifyPassword($password, $user['password'])) {
                $userModel->recordFailedAttempt($ip);
                $_SESSION['error'] = "Invalid username or password";
                header('Location: /login');
                exit;
            }
            
            // Failed attempts reset
            $userModel->resetFailedAttempts($ip);
            
            // Login qilish
            Auth::login($user['id'], $user['username'], $user['role'], $user['mahalla_id']);
            
            // Audit log
            $this->logLogin($user['id']);
            
            // Redirect based on role
            switch ($user['role']) {
                case 'super_admin':
                case 'admin':
                    header('Location: /admin/dashboard');
                    break;
                case 'operator':
                    header('Location: /operator/dashboard');
                    break;
                default:
                    header('Location: /dashboard');
            }
            exit;
        }
    }
    
    public function logout() {
        Auth::logout();
        header('Location: /login');
        exit;
    }
    
    public function register() {
        // Only admin can register new users
        if (!Auth::hasPermission('admin')) {
            header('Location: /login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $csrfToken = Security::generateCSRFToken();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF tekshirish
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid or expired";
                header('Location: /register');
                exit;
            }
            
            // Ma'lumotlarni validatsiya qilish
            $errors = [];
            
            $firstName = Security::sanitize($_POST['first_name'] ?? '');
            $lastName = Security::sanitize($_POST['last_name'] ?? '');
            $email = Security::sanitize($_POST['email'] ?? '');
            $username = Security::sanitize($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = Security::sanitize($_POST['role'] ?? 'user');
            $mahallaId = $_POST['mahalla_id'] ?? null;
            
            if (empty($firstName)) $errors[] = "First name is required";
            if (empty($lastName)) $errors[] = "Last name is required";
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Valid email is required";
            }
            if (empty($username) || strlen($username) < 3) {
                $errors[] = "Username must be at least 3 characters";
            }
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                $errors[] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters";
            }
            if ($password !== $confirmPassword) {
                $errors[] = "Passwords do not match";
            }
            
            // Fayl upload (rasm)
            $profilePicture = 'default.png';
            if (!empty($_FILES['profile_picture']['name'])) {
                $validation = Security::validateUploadedFile($_FILES['profile_picture']);
                
                if ($validation['success']) {
                    $uploadPath = PROFILE_PICS_PATH . $validation['filename'];
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                        $profilePicture = $validation['filename'];
                    }
                } else {
                    $errors[] = $validation['error'];
                }
            }
            
            if (empty($errors)) {
                $userModel = new User();
                
                // Username va email takrorlanishi tekshirish
                if ($userModel->findByUsername($username)) {
                    $errors[] = "Username already exists";
                }
                
                if ($userModel->findByEmail($email)) {
                    $errors[] = "Email already exists";
                }
                
                if (empty($errors)) {
                    $userData = [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'username' => $username,
                        'password' => $password,
                        'role' => $role,
                        'mahalla_id' => $mahallaId,
                        'profile_picture' => $profilePicture
                    ];
                    
                    if ($userModel->create($userData)) {
                        $_SESSION['success'] = "User created successfully";
                        header('Location: /admin/users');
                        exit;
                    } else {
                        $errors[] = "Failed to create user";
                    }
                }
            }
            
            // Xatoliklar bilan qayta ko'rsatish
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /register');
            exit;
        }
    }
    
    private function logLogin($userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO login_logs 
            (user_id, ip_address, user_agent, login_time) 
            VALUES (?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $userId,
            Security::getClientIP(),
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }
}