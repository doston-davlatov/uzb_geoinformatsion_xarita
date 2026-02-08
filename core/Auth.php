<?php
// core/Auth.php
class Auth {
    
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => SESSION_TIMEOUT,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            session_name('MFY_SECURE_SESSION');
            session_start();
            
            // Session ID yangilash (session fixation himoyasi)
            if (empty($_SESSION['created'])) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            } elseif (time() - $_SESSION['created'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }
    
    public static function login($userId, $username, $role, $mahallaId = null) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['mahalla_id'] = $mahallaId;
        $_SESSION['loggedin'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['session_signature'] = Security::getBrowserSignature();
        
        // Faol sessionlarni saqlash
        self::saveActiveSession($userId);
        
        return true;
    }
    
    public static function logout() {
        if (isset($_SESSION['user_id'])) {
            self::deleteActiveSession($_SESSION['user_id']);
        }
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    public static function isLoggedIn() {
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            return false;
        }
        
        // Session timeout
        if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
            self::logout();
            return false;
        }
        
        // Session fixation himoyasi
        if ($_SESSION['session_signature'] !== Security::getBrowserSignature()) {
            self::logout();
            return false;
        }
        
        // Login vaqtini yangilash
        $_SESSION['login_time'] = time();
        
        return true;
    }
    
    public static function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
    
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function getMahallaId() {
        return $_SESSION['mahalla_id'] ?? null;
    }
    
    public static function hasPermission($requiredRole) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $userRole = self::getUserRole();
        $roleHierarchy = [
            'super_admin' => 4,
            'admin' => 3,
            'operator' => 2,
            'user' => 1
        ];
        
        $userLevel = $roleHierarchy[$userRole] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 0;
        
        return $userLevel >= $requiredLevel;
    }
    
    private static function saveActiveSession($userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO active_sessions 
            (user_id, device_name, ip_address, session_token) 
            VALUES (?, ?, ?, ?)
        ");
        
        $device = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $ip = Security::getClientIP();
        $token = bin2hex(random_bytes(32));
        
        $stmt->execute([$userId, $device, $ip, $token]);
    }
    
    private static function deleteActiveSession($userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            DELETE FROM active_sessions 
            WHERE user_id = ? 
            AND session_token = ?
        ");
        
        $token = $_SESSION['session_token'] ?? '';
        $stmt->execute([$userId, $token]);
    }
}