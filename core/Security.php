<?php
// core/Security.php
class Security {
    
    // CSRF token yaratish
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token']) || time() > $_SESSION['csrf_token_expire']) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_expire'] = time() + CSRF_TOKEN_LIFETIME;
        }
        return $_SESSION['csrf_token'];
    }
    
    // CSRF token tekshirish
    public static function verifyCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        if (time() > $_SESSION['csrf_token_expire']) {
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_expire']);
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // XSS himoyasi
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $input;
    }
    
    // Parol hash
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 2048,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
    
    // Parol tekshirish
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // IP manzil olish
    public static function getClientIP() {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
    
    // Brauzer imzosi
    public static function getBrowserSignature() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        
        return hash('sha256', $userAgent . $accept . $language . self::getClientIP());
    }
    
    // Fayl upload xavfsizligi
    public static function validateUploadedFile($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'File upload error'];
        }
        
        // Fayl hajmi (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return ['success' => false, 'error' => 'File too large (max 5MB)'];
        }
        
        // Fayl tipi
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type'];
        }
        
        // Fayl nomini xavfsizlashtirish
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $filename = time() . '_' . uniqid() . '_' . $filename;
        
        return ['success' => true, 'filename' => $filename, 'mime' => $mime];
    }
}