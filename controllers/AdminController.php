<?php
// controllers/AdminController.php
class AdminController {
    
    private $db;
    private $userModel;
    private $mahallaModel;
    private $crimeModel;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->userModel = new User();
        $this->mahallaModel = new Mahalla();
        $this->crimeModel = new Crime();
    }
    
    public function dashboard() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        // Real-time statistics
        $stats = [
            // User statistics
            'total_users' => $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_admins' => $this->db->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'super_admin')")->fetchColumn(),
            'total_operators' => $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'operator'")->fetchColumn(),
            'active_users' => $this->db->query("SELECT COUNT(DISTINCT user_id) FROM active_sessions WHERE last_activity > DATE_SUB(NOW(), INTERVAL 30 MINUTE)")->fetchColumn(),
            
            // Mahalla statistics
            'total_mahallas' => $this->db->query("SELECT COUNT(*) FROM mahallelar")->fetchColumn(),
            'mahallas_with_operators' => $this->db->query("SELECT COUNT(*) FROM mahallelar WHERE operator_id IS NOT NULL")->fetchColumn(),
            'mahallas_without_operators' => $this->db->query("SELECT COUNT(*) FROM mahallelar WHERE operator_id IS NULL")->fetchColumn(),
            
            // Crime statistics
            'total_crimes' => $this->db->query("SELECT COUNT(*) FROM crimes")->fetchColumn(),
            'crimes_today' => $this->db->query("SELECT COUNT(*) FROM crimes WHERE DATE(created_at) = CURDATE()")->fetchColumn(),
            'crimes_this_month' => $this->db->query("SELECT COUNT(*) FROM crimes WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn(),
            
            // Nizok statistics
            'total_nizok' => $this->db->query("SELECT COUNT(*) FROM nizokash_oilalar")->fetchColumn(),
            'active_nizok' => $this->db->query("SELECT COUNT(*) FROM nizokash_oilalar WHERE status = 'faol'")->fetchColumn(),
            
            // Order statistics
            'total_orders' => $this->db->query("SELECT COUNT(*) FROM order_olganlar")->fetchColumn(),
            
            // File statistics
            'total_uploads' => $this->db->query("SELECT COUNT(*) FROM file_uploads")->fetchColumn(),
            'storage_used' => $this->db->query("SELECT SUM(file_size) FROM file_uploads")->fetchColumn() ?? 0
        ];
        
        // Recent activities
        $activities = $this->db->query("
            SELECT 
                al.*,
                u.username as user_name,
                CONCAT(u.first_name, ' ', u.last_name) as user_full_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC 
            LIMIT 20
        ")->fetchAll();
        
        // System health
        $systemHealth = [
            'database_size' => $this->getDatabaseSize(),
            'server_load' => sys_getloadavg()[0] ?? 0,
            'memory_usage' => memory_get_usage(true),
            'memory_limit' => ini_get('memory_limit'),
            'php_version' => PHP_VERSION,
            'uptime' => $this->getSystemUptime(),
            'last_backup' => $this->getLastBackupDate()
        ];
        
        // Recent crimes
        $recentCrimes = $this->db->query("
            SELECT 
                c.*,
                m.nomi as mahalla_nomi,
                t.nomi as tuman_nomi,
                v.nomi as viloyat_nomi
            FROM crimes c
            LEFT JOIN mahallelar m ON c.mahalla_id = m.id
            LEFT JOIN tumanlar t ON c.tuman_id = t.id
            LEFT JOIN viloyatlar v ON c.viloyat_id = v.id
            ORDER BY c.created_at DESC 
            LIMIT 10
        ")->fetchAll();
        
        // Recent users
        $recentUsers = $this->db->query("
            SELECT 
                u.*,
                m.nomi as mahalla_nomi
            FROM users u
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id
            ORDER BY u.created_at DESC 
            LIMIT 10
        ")->fetchAll();
        
        // Chart data
        $chartData = [
            'crimes_by_month' => $this->getCrimesByMonth(),
            'users_by_role' => $this->getUsersByRole(),
            'crimes_by_severity' => $this->getCrimesBySeverity(),
            'traffic_data' => $this->getTrafficData()
        ];
        
        include __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function users() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $sql = "
            SELECT 
                u.*,
                m.nomi as mahalla_nomi,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                (SELECT COUNT(*) FROM active_sessions WHERE user_id = u.id AND last_activity > DATE_SUB(NOW(), INTERVAL 30 MINUTE)) as is_online
            FROM users u
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (u.username LIKE ? OR u.email LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if ($role) {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        include __DIR__ . '/../views/admin/users.php';
    }
    
    public function createUser() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF verification
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid";
                header('Location: /admin/users/create');
                exit;
            }
            
            $errors = [];
            
            // Validation
            $firstName = Security::sanitize($_POST['first_name'] ?? '');
            $lastName = Security::sanitize($_POST['last_name'] ?? '');
            $email = Security::sanitize($_POST['email'] ?? '');
            $username = Security::sanitize($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = Security::sanitize($_POST['role'] ?? 'user');
            $mahallaId = $_POST['mahalla_id'] ? (int)$_POST['mahalla_id'] : null;
            $phone = Security::sanitize($_POST['phone'] ?? '');
            
            // Basic validation
            if (empty($firstName) || empty($lastName)) {
                $errors[] = "Ism va familiya kiritilishi shart";
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "To'g'ri email manzil kiriting";
            }
            
            if (empty($username) || strlen($username) < 3) {
                $errors[] = "Foydalanuvchi nomi kamida 3 belgidan iborat bo'lishi kerak";
            }
            
            if (strlen($password) < 8) {
                $errors[] = "Parol kamida 8 belgidan iborat bo'lishi kerak";
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = "Parollar mos kelmadi";
            }
            
            // Check uniqueness
            if ($this->userModel->findByUsername($username)) {
                $errors[] = "Bu foydalanuvchi nomi allaqachon mavjud";
            }
            
            if ($this->userModel->findByEmail($email)) {
                $errors[] = "Bu email manzil allaqachon mavjud";
            }
            
            // File upload (profile picture)
            $profilePicture = 'default.png';
            if (!empty($_FILES['profile_picture']['name'])) {
                $validation = Security::validateUploadedFile($_FILES['profile_picture']);
                
                if ($validation['success']) {
                    $uploadDir = PROFILE_PICS_PATH;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $filename = $validation['filename'];
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                        $profilePicture = $filename;
                    }
                } else {
                    $errors[] = $validation['error'];
                }
            }
            
            if (empty($errors)) {
                // Create user
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password,
                    'role' => $role,
                    'mahalla_id' => $mahallaId,
                    'profile_picture' => $profilePicture,
                    'phone' => $phone
                ];
                
                if ($this->userModel->create($userData)) {
                    // Log the action
                    $this->logActivity(
                        Auth::getUserId(),
                        'USER_CREATE',
                        'users',
                        $this->db->lastInsertId(),
                        "Yangi foydalanuvchi yaratildi: $username"
                    );
                    
                    $_SESSION['success'] = "Foydalanuvchi muvaffaqiyatli yaratildi";
                    header('Location: /admin/users');
                    exit;
                } else {
                    $errors[] = "Foydalanuvchi yaratishda xatolik yuz berdi";
                }
            }
            
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /admin/users/create');
            exit;
        }
        
        // Get mahallas for dropdown
        $mahallas = $this->db->query("
            SELECT m.*, v.nomi as viloyat_nomi, t.nomi as tuman_nomi 
            FROM mahallelar m
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            ORDER BY v.nomi, t.nomi, m.nomi
        ")->fetchAll();
        
        include __DIR__ . '/../views/admin/create_user.php';
    }
    
    public function editUser($id) {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            header('Location: /404');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF verification
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid";
                header("Location: /admin/users/$id/edit");
                exit;
            }
            
            $errors = [];
            
            // Validation
            $firstName = Security::sanitize($_POST['first_name'] ?? '');
            $lastName = Security::sanitize($_POST['last_name'] ?? '');
            $email = Security::sanitize($_POST['email'] ?? '');
            $username = Security::sanitize($_POST['username'] ?? '');
            $role = Security::sanitize($_POST['role'] ?? 'user');
            $mahallaId = $_POST['mahalla_id'] ? (int)$_POST['mahalla_id'] : null;
            $phone = Security::sanitize($_POST['phone'] ?? '');
            $status = $_POST['status'] ?? 'active';
            
            // Check if username changed and is unique
            if ($username !== $user['username'] && $this->userModel->findByUsername($username)) {
                $errors[] = "Bu foydalanuvchi nomi allaqachon mavjud";
            }
            
            // Check if email changed and is unique
            if ($email !== $user['email'] && $this->userModel->findByEmail($email)) {
                $errors[] = "Bu email manzil allaqachon mavjud";
            }
            
            // File upload (profile picture)
            $profilePicture = $user['profile_picture'];
            if (!empty($_FILES['profile_picture']['name'])) {
                $validation = Security::validateUploadedFile($_FILES['profile_picture']);
                
                if ($validation['success']) {
                    $uploadDir = PROFILE_PICS_PATH;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $filename = $validation['filename'];
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                        // Delete old profile picture if not default
                        if ($profilePicture !== 'default.png' && file_exists($uploadDir . $profilePicture)) {
                            unlink($uploadDir . $profilePicture);
                        }
                        $profilePicture = $filename;
                    }
                } else {
                    $errors[] = $validation['error'];
                }
            }
            
            if (empty($errors)) {
                // Prepare update data
                $updateData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'username' => $username,
                    'role' => $role,
                    'mahalla_id' => $mahallaId,
                    'profile_picture' => $profilePicture,
                    'phone' => $phone,
                    'status' => $status
                ];
                
                // Password update if provided
                if (!empty($_POST['password'])) {
                    if (strlen($_POST['password']) < 8) {
                        $errors[] = "Parol kamida 8 belgidan iborat bo'lishi kerak";
                    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                        $errors[] = "Parollar mos kelmadi";
                    } else {
                        $updateData['password'] = $_POST['password'];
                    }
                }
                
                if (empty($errors)) {
                    if ($this->userModel->update($id, $updateData)) {
                        // Log the action
                        $this->logActivity(
                            Auth::getUserId(),
                            'USER_UPDATE',
                            'users',
                            $id,
                            "Foydalanuvchi ma'lumotlari yangilandi: $username"
                        );
                        
                        $_SESSION['success'] = "Foydalanuvchi ma'lumotlari muvaffaqiyatli yangilandi";
                        header('Location: /admin/users');
                        exit;
                    } else {
                        $errors[] = "Foydalanuvchi yangilashda xatolik yuz berdi";
                    }
                }
            }
            
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /admin/users/$id/edit");
            exit;
        }
        
        // Get mahallas for dropdown
        $mahallas = $this->db->query("
            SELECT m.*, v.nomi as viloyat_nomi, t.nomi as tuman_nomi 
            FROM mahallelar m
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            ORDER BY v.nomi, t.nomi, m.nomi
        ")->fetchAll();
        
        include __DIR__ . '/../views/admin/edit_user.php';
    }
    
    public function deleteUser($id) {
        if (!Auth::hasPermission('super_admin')) {
            header('Location: /403');
            exit;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            header('Location: /404');
            exit;
        }
        
        // Cannot delete yourself
        if ($id == Auth::getUserId()) {
            $_SESSION['error'] = "O'zingizni o'chira olmaysiz";
            header('Location: /admin/users');
            exit;
        }
        
        // Cannot delete super_admin if you're not super_admin
        if ($user['role'] === 'super_admin' && Auth::getUserRole() !== 'super_admin') {
            $_SESSION['error'] = "Super adminni faqat super admin o'chira oladi";
            header('Location: /admin/users');
            exit;
        }
        
        if ($this->userModel->delete($id)) {
            // Log the action
            $this->logActivity(
                Auth::getUserId(),
                'USER_DELETE',
                'users',
                $id,
                "Foydalanuvchi o'chirildi: {$user['username']}"
            );
            
            $_SESSION['success'] = "Foydalanuvchi muvaffaqiyatli o'chirildi";
        } else {
            $_SESSION['error'] = "Foydalanuvchi o'chirishda xatolik yuz berdi";
        }
        
        header('Location: /admin/users');
        exit;
    }
    
    public function operators() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        $operators = $this->db->query("
            SELECT 
                u.*,
                m.nomi as mahalla_nomi,
                v.nomi as viloyat_nomi,
                t.nomi as tuman_nomi,
                COUNT(c.id) as total_crimes,
                COUNT(DISTINCT n.id) as total_nizok,
                COUNT(DISTINCT o.id) as total_orders
            FROM users u
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            LEFT JOIN crimes c ON u.id = c.created_by
            LEFT JOIN nizokash_oilalar n ON u.id = n.operator_id
            LEFT JOIN order_olganlar o ON u.id = o.operator_id
            WHERE u.role = 'operator'
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ")->fetchAll();
        
        include __DIR__ . '/../views/admin/operators.php';
    }
    
    public function assignOperators() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        // Get operators without mahalla
        $unassignedOperators = $this->db->query("
            SELECT u.* 
            FROM users u 
            WHERE u.role = 'operator' 
            AND u.mahalla_id IS NULL
            ORDER BY u.created_at DESC
        ")->fetchAll();
        
        // Get mahallas without operators
        $unassignedMahallas = $this->db->query("
            SELECT m.*, 
                   v.nomi as viloyat_nomi, 
                   t.nomi as tuman_nomi,
                   COUNT(c.id) as crime_count,
                   COUNT(n.id) as nizok_count
            FROM mahallelar m
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            LEFT JOIN crimes c ON m.id = c.mahalla_id
            LEFT JOIN nizokash_oilalar n ON m.id = n.mahalla_id AND n.status = 'faol'
            WHERE m.operator_id IS NULL
            GROUP BY m.id
            ORDER BY v.nomi, t.nomi, m.nomi
        ")->fetchAll();
        
        // Get current assignments
        $currentAssignments = $this->db->query("
            SELECT 
                m.*,
                u.username as operator_username,
                CONCAT(u.first_name, ' ', u.last_name) as operator_name,
                v.nomi as viloyat_nomi,
                t.nomi as tuman_nomi
            FROM mahallelar m
            JOIN users u ON m.operator_id = u.id
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            ORDER BY v.nomi, t.nomi, m.nomi
        ")->fetchAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF verification
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid";
                header('Location: /admin/assign-operators');
                exit;
            }
            
            $action = $_POST['action'] ?? '';
            
            if ($action === 'assign') {
                $operatorId = (int)($_POST['operator_id'] ?? 0);
                $mahallaId = (int)($_POST['mahalla_id'] ?? 0);
                
                if ($operatorId && $mahallaId) {
                    // Check if operator already assigned to another mahalla
                    $checkStmt = $this->db->prepare("
                        SELECT id FROM mahallelar WHERE operator_id = ?
                    ");
                    $checkStmt->execute([$operatorId]);
                    $existingAssignment = $checkStmt->fetch();
                    
                    if ($existingAssignment) {
                        $_SESSION['error'] = "Bu operator allaqachon boshqa MFYga tayinlangan";
                    } else {
                        // Update mahalla with operator
                        $stmt = $this->db->prepare("
                            UPDATE mahallelar 
                            SET operator_id = ?, updated_at = NOW() 
                            WHERE id = ?
                        ");
                        
                        if ($stmt->execute([$operatorId, $mahallaId])) {
                            // Also update user's mahalla_id
                            $userStmt = $this->db->prepare("
                                UPDATE users 
                                SET mahalla_id = ?, updated_at = NOW() 
                                WHERE id = ?
                            ");
                            $userStmt->execute([$mahallaId, $operatorId]);
                            
                            // Log the action
                            $this->logActivity(
                                Auth::getUserId(),
                                'OPERATOR_ASSIGN',
                                'mahallelar',
                                $mahallaId,
                                "Operator MFYga tayinlandi"
                            );
                            
                            $_SESSION['success'] = "Operator muvaffaqiyatli tayinlandi";
                        } else {
                            $_SESSION['error'] = "Operator tayinlashda xatolik yuz berdi";
                        }
                    }
                }
            } elseif ($action === 'unassign') {
                $mahallaId = (int)($_POST['mahalla_id'] ?? 0);
                
                if ($mahallaId) {
                    // Get operator id before unassigning
                    $getStmt = $this->db->prepare("
                        SELECT operator_id FROM mahallelar WHERE id = ?
                    ");
                    $getStmt->execute([$mahallaId]);
                    $operatorId = $getStmt->fetchColumn();
                    
                    // Remove operator from mahalla
                    $stmt = $this->db->prepare("
                        UPDATE mahallelar 
                        SET operator_id = NULL, updated_at = NOW() 
                        WHERE id = ?
                    ");
                    
                    if ($stmt->execute([$mahallaId])) {
                        // Also remove mahalla_id from user
                        if ($operatorId) {
                            $userStmt = $this->db->prepare("
                                UPDATE users 
                                SET mahalla_id = NULL, updated_at = NOW() 
                                WHERE id = ?
                            ");
                            $userStmt->execute([$operatorId]);
                        }
                        
                        // Log the action
                        $this->logActivity(
                            Auth::getUserId(),
                            'OPERATOR_UNASSIGN',
                            'mahallelar',
                            $mahallaId,
                            "Operator MFYdan olib tashlandi"
                        );
                        
                        $_SESSION['success'] = "Operator muvaffaqiyatli olib tashlandi";
                    } else {
                        $_SESSION['error'] = "Operatorni olib tashlashda xatolik yuz berdi";
                    }
                }
            } elseif ($action === 'reassign') {
                $oldMahallaId = (int)($_POST['old_mahalla_id'] ?? 0);
                $newMahallaId = (int)($_POST['new_mahalla_id'] ?? 0);
                $operatorId = (int)($_POST['operator_id'] ?? 0);
                
                if ($oldMahallaId && $newMahallaId && $operatorId) {
                    // Start transaction
                    $this->db->beginTransaction();
                    
                    try {
                        // Remove operator from old mahalla
                        $stmt1 = $this->db->prepare("
                            UPDATE mahallelar 
                            SET operator_id = NULL, updated_at = NOW() 
                            WHERE id = ?
                        ");
                        $stmt1->execute([$oldMahallaId]);
                        
                        // Assign operator to new mahalla
                        $stmt2 = $this->db->prepare("
                            UPDATE mahallelar 
                            SET operator_id = ?, updated_at = NOW() 
                            WHERE id = ?
                        ");
                        $stmt2->execute([$operatorId, $newMahallaId]);
                        
                        // Update user's mahalla_id
                        $stmt3 = $this->db->prepare("
                            UPDATE users 
                            SET mahalla_id = ?, updated_at = NOW() 
                            WHERE id = ?
                        ");
                        $stmt3->execute([$newMahallaId, $operatorId]);
                        
                        $this->db->commit();
                        
                        // Log the action
                        $this->logActivity(
                            Auth::getUserId(),
                            'OPERATOR_REASSIGN',
                            'mahallelar',
                            $newMahallaId,
                            "Operator boshqa MFYga qayta tayinlandi"
                        );
                        
                        $_SESSION['success'] = "Operator muvaffaqiyatli qayta tayinlandi";
                    } catch (Exception $e) {
                        $this->db->rollBack();
                        $_SESSION['error'] = "Qayta tayinlashda xatolik yuz berdi: " . $e->getMessage();
                    }
                }
            }
            
            header('Location: /admin/assign-operators');
            exit;
        }
        
        include __DIR__ . '/../views/admin/assign_operators.php';
    }
    
    public function statistics() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        $period = $_GET['period'] ?? 'monthly';
        $year = $_GET['year'] ?? date('Y');
        $region = $_GET['region'] ?? '';
        
        // Complex statistics queries
        $stats = [];
        
        // Crimes by year
        $stats['crimes_by_year'] = $this->db->query("
            SELECT 
                YEAR(sodir_vaqti) as year,
                COUNT(*) as total,
                SUM(CASE WHEN ogrilik_turi = 'o\'ta og\'ir' THEN 1 ELSE 0 END) as ota_ogir,
                SUM(CASE WHEN ogrilik_turi = 'og\'ir' THEN 1 ELSE 0 END) as ogir,
                SUM(CASE WHEN ogrilik_turi = 'uncha og\'ir bo\'lmagan' THEN 1 ELSE 0 END) as uncha_ogir,
                SUM(CASE WHEN ogrilik_turi = 'ijtimoiy xavfi katta bo\'lmagan' THEN 1 ELSE 0 END) as ijtimoiy
            FROM crimes 
            WHERE sodir_vaqti IS NOT NULL 
            GROUP BY YEAR(sodir_vaqti) 
            ORDER BY year DESC
            LIMIT 10
        ")->fetchAll();
        
        // Crimes by month for current year
        $stats['crimes_by_month'] = $this->db->query("
            SELECT 
                MONTH(sodir_vaqti) as month,
                COUNT(*) as count,
                MONTHNAME(sodir_vaqti) as month_name
            FROM crimes 
            WHERE YEAR(sodir_vaqti) = YEAR(CURDATE())
            GROUP BY MONTH(sodir_vaqti)
            ORDER BY month
        ")->fetchAll();
        
        // Nizok by region
        $stats['nizok_by_region'] = $this->db->query("
            SELECT 
                v.nomi as viloyat,
                COUNT(*) as total,
                SUM(CASE WHEN n.status = 'faol' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN n.status = 'chiqarilgan' THEN 1 ELSE 0 END) as removed,
                SUM(n.azolar_soni) as total_members
            FROM nizokash_oilalar n
            JOIN viloyatlar v ON n.viloyat_id = v.id
            GROUP BY v.id
            ORDER BY total DESC
        ")->fetchAll();
        
        // Top mahallas by crimes
        $stats['top_mahallas_crimes'] = $this->db->query("
            SELECT 
                m.nomi as mahalla,
                v.nomi as viloyat,
                t.nomi as tuman,
                COUNT(c.id) as crime_count,
                ROUND(COUNT(c.id) * 100.0 / (SELECT COUNT(*) FROM crimes), 2) as percentage
            FROM mahallelar m
            LEFT JOIN crimes c ON m.id = c.mahalla_id
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            GROUP BY m.id
            HAVING crime_count > 0
            ORDER BY crime_count DESC
            LIMIT 15
        ")->fetchAll();
        
        // User activity statistics
        $stats['user_activity'] = $this->db->query("
            SELECT 
                u.username,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                u.role,
                COUNT(DISTINCT DATE(al.created_at)) as active_days,
                COUNT(al.id) as total_actions,
                MAX(al.created_at) as last_activity
            FROM users u
            LEFT JOIN audit_logs al ON u.id = al.user_id
            WHERE al.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY u.id
            ORDER BY total_actions DESC
            LIMIT 20
        ")->fetchAll();
        
        // Crime hotspots
        $stats['crime_hotspots'] = $this->db->query("
            SELECT 
                m.nomi as mahalla,
                v.nomi as viloyat,
                t.nomi as tuman,
                COUNT(c.id) as crime_count,
                GROUP_CONCAT(DISTINCT c.ogrilik_turi ORDER BY c.ogrilik_turi SEPARATOR ', ') as crime_types
            FROM crimes c
            JOIN mahallelar m ON c.mahalla_id = m.id
            JOIN viloyatlar v ON m.viloyat_id = v.id
            JOIN tumanlar t ON m.tuman_id = t.id
            WHERE c.sodir_vaqti > DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY m.id
            HAVING crime_count >= 5
            ORDER BY crime_count DESC
        ")->fetchAll();
        
        // Time-based statistics
        $stats['crimes_by_hour'] = $this->db->query("
            SELECT 
                HOUR(sodir_vaqti) as hour,
                COUNT(*) as count
            FROM crimes 
            WHERE sodir_vaqti IS NOT NULL
            GROUP BY HOUR(sodir_vaqti)
            ORDER BY hour
        ")->fetchAll();
        
        $stats['crimes_by_day'] = $this->db->query("
            SELECT 
                DAYNAME(sodir_vaqti) as day,
                COUNT(*) as count,
                DAYOFWEEK(sodir_vaqti) as day_num
            FROM crimes 
            WHERE sodir_vaqti IS NOT NULL
            GROUP BY DAYOFWEEK(sodir_vaqti), DAYNAME(sodir_vaqti)
            ORDER BY day_num
        ")->fetchAll();
        
        include __DIR__ . '/../views/admin/statistics.php';
    }
    
    public function settings() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF verification
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid";
                header('Location: /admin/settings');
                exit;
            }
            
            $action = $_POST['action'] ?? '';
            
            if ($action === 'general') {
                // Update general settings
                $settings = [
                    'site_name' => Security::sanitize($_POST['site_name'] ?? ''),
                    'site_description' => Security::sanitize($_POST['site_description'] ?? ''),
                    'site_email' => Security::sanitize($_POST['site_email'] ?? ''),
                    'site_phone' => Security::sanitize($_POST['site_phone'] ?? ''),
                    'site_address' => Security::sanitize($_POST['site_address'] ?? ''),
                    'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0,
                    'registration_enabled' => isset($_POST['registration_enabled']) ? 1 : 0,
                    'auto_backup' => isset($_POST['auto_backup']) ? 1 : 0,
                    'backup_frequency' => Security::sanitize($_POST['backup_frequency'] ?? 'daily')
                ];
                
                $this->updateSettings($settings);
                $_SESSION['success'] = "Umumiy sozlamalar yangilandi";
                
            } elseif ($action === 'security') {
                // Update security settings
                $settings = [
                    'max_login_attempts' => (int)($_POST['max_login_attempts'] ?? 5),
                    'session_timeout' => (int)($_POST['session_timeout'] ?? 60),
                    'password_min_length' => (int)($_POST['password_min_length'] ?? 8),
                    'require_2fa' => isset($_POST['require_2fa']) ? 1 : 0,
                    'force_ssl' => isset($_POST['force_ssl']) ? 1 : 0,
                    'ip_whitelist' => Security::sanitize($_POST['ip_whitelist'] ?? ''),
                    'brute_force_protection' => isset($_POST['brute_force_protection']) ? 1 : 0
                ];
                
                $this->updateSettings($settings);
                $_SESSION['success'] = "Xavfsizlik sozlamalari yangilandi";
                
            } elseif ($action === 'email') {
                // Update email settings
                $settings = [
                    'smtp_host' => Security::sanitize($_POST['smtp_host'] ?? ''),
                    'smtp_port' => (int)($_POST['smtp_port'] ?? 587),
                    'smtp_username' => Security::sanitize($_POST['smtp_username'] ?? ''),
                    'smtp_password' => $_POST['smtp_password'] ? Security::sanitize($_POST['smtp_password']) : null,
                    'smtp_encryption' => Security::sanitize($_POST['smtp_encryption'] ?? 'tls'),
                    'email_from' => Security::sanitize($_POST['email_from'] ?? ''),
                    'email_from_name' => Security::sanitize($_POST['email_from_name'] ?? '')
                ];
                
                // Don't update password if empty
                if ($settings['smtp_password'] === null) {
                    unset($settings['smtp_password']);
                }
                
                $this->updateSettings($settings);
                $_SESSION['success'] = "Email sozlamalari yangilandi";
                
            } elseif ($action === 'backup') {
                // Create manual backup
                $backupResult = $this->createBackup();
                
                if ($backupResult['success']) {
                    $_SESSION['success'] = "Backup muvaffaqiyatli yaratildi: " . $backupResult['filename'];
                } else {
                    $_SESSION['error'] = "Backup yaratishda xatolik: " . $backupResult['error'];
                }
                
            } elseif ($action === 'clear_cache') {
                // Clear cache
                $this->clearCache();
                $_SESSION['success'] = "Cache muvaffaqiyatli tozalandi";
                
            } elseif ($action === 'optimize_db') {
                // Optimize database
                $this->optimizeDatabase();
                $_SESSION['success'] = "Ma'lumotlar bazasi optimallashtirildi";
            }
            
            header('Location: /admin/settings');
            exit;
        }
        
        // Get current settings
        $settings = $this->getSettings();
        $backupFiles = $this->getBackupFiles();
        $systemInfo = $this->getSystemInfo();
        
        include __DIR__ . '/../views/admin/settings.php';
    }
    
    public function auditLogs() {
        if (!Auth::hasPermission('admin')) {
            header('Location: /403');
            exit;
        }
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $search = $_GET['search'] ?? '';
        $action = $_GET['action'] ?? '';
        $user_id = $_GET['user_id'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        
        $sql = "
            SELECT 
                al.*,
                u.username as user_name,
                CONCAT(u.first_name, ' ', u.last_name) as user_full_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (al.action LIKE ? OR al.details LIKE ? OR al.ip_address LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if ($action) {
            $sql .= " AND al.action = ?";
            $params[] = $action;
        }
        
        if ($user_id) {
            $sql .= " AND al.user_id = ?";
            $params[] = $user_id;
        }
        
        if ($date_from) {
            $sql .= " AND DATE(al.created_at) >= ?";
            $params[] = $date_from;
        }
        
        if ($date_to) {
            $sql .= " AND DATE(al.created_at) <= ?";
            $params[] = $date_to;
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) FROM ($sql) as count_query";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Get paginated results
        $sql .= " ORDER BY al.created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $logs = $stmt->fetchAll();
        
        // Get distinct actions for filter
        $actions = $this->db->query("
            SELECT DISTINCT action FROM audit_logs ORDER BY action
        ")->fetchAll(PDO::FETCH_COLUMN);
        
        // Get users for filter
        $users = $this->db->query("
            SELECT DISTINCT al.user_id, u.username 
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            WHERE al.user_id IS NOT NULL
            ORDER BY u.username
        ")->fetchAll();
        
        $totalPages = ceil($total / $limit);
        
        include __DIR__ . '/../views/admin/audit_logs.php';
    }
    
    public function systemLogs() {
        if (!Auth::hasPermission('super_admin')) {
            header('Location: /403');
            exit;
        }
        
        $logFile = __DIR__ . '/../logs/system.log';
        $logs = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $logs = array_slice(array_reverse($lines), 0, 100); // Last 100 lines
        }
        
        include __DIR__ . '/../views/admin/system_logs.php';
    }
    
    public function backupRestore() {
        if (!Auth::hasPermission('super_admin')) {
            header('Location: /403');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF verification
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "CSRF token not valid";
                header('Location: /admin/backup-restore');
                exit;
            }
            
            $action = $_POST['action'] ?? '';
            
            if ($action === 'create_backup') {
                $backupResult = $this->createBackup();
                
                if ($backupResult['success']) {
                    $_SESSION['success'] = "Backup muvaffaqiyatli yaratildi: " . $backupResult['filename'];
                } else {
                    $_SESSION['error'] = "Backup yaratishda xatolik: " . $backupResult['error'];
                }
                
            } elseif ($action === 'restore_backup') {
                $backupFile = $_POST['backup_file'] ?? '';
                
                if ($backupFile && file_exists($backupFile)) {
                    $restoreResult = $this->restoreBackup($backupFile);
                    
                    if ($restoreResult['success']) {
                        $_SESSION['success'] = "Backup muvaffaqiyatli tiklandi";
                    } else {
                        $_SESSION['error'] = "Backup tiklashda xatolik: " . $restoreResult['error'];
                    }
                } else {
                    $_SESSION['error'] = "Backup fayli topilmadi";
                }
                
            } elseif ($action === 'delete_backup') {
                $backupFile = $_POST['backup_file'] ?? '';
                
                if ($backupFile && file_exists($backupFile)) {
                    if (unlink($backupFile)) {
                        $_SESSION['success'] = "Backup fayli muvaffaqiyatli o'chirildi";
                    } else {
                        $_SESSION['error'] = "Backup faylini o'chirishda xatolik";
                    }
                } else {
                    $_SESSION['error'] = "Backup fayli topilmadi";
                }
            }
            
            header('Location: /admin/backup-restore');
            exit;
        }
        
        $backupFiles = $this->getBackupFiles();
        $autoBackupSettings = $this->getAutoBackupSettings();
        
        include __DIR__ . '/../views/admin/backup_restore.php';
    }
    
    private function getDatabaseSize() {
        $stmt = $this->db->query("
            SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
        ");
        
        return $stmt->fetchColumn() ?? '0.00';
    }
    
    private function getSystemUptime() {
        if (function_exists('shell_exec')) {
            $uptime = shell_exec('uptime -p');
            return $uptime ? trim($uptime) : 'Noma\'lum';
        }
        return 'Noma\'lum';
    }
    
    private function getLastBackupDate() {
        $backupDir = __DIR__ . '/../backups/';
        if (!is_dir($backupDir)) {
            return 'Hech qachon';
        }
        
        $files = glob($backupDir . '*.sql');
        if (empty($files)) {
            return 'Hech qachon';
        }
        
        $latestFile = max($files);
        return date('d.m.Y H:i', filemtime($latestFile));
    }
    
    private function getCrimesByMonth() {
        $stmt = $this->db->query("
            SELECT 
                DATE_FORMAT(sodir_vaqti, '%Y-%m') as month,
                COUNT(*) as count
            FROM crimes
            WHERE sodir_vaqti >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(sodir_vaqti, '%Y-%m')
            ORDER BY month
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getUsersByRole() {
        $stmt = $this->db->query("
            SELECT 
                role,
                COUNT(*) as count
            FROM users
            GROUP BY role
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getCrimesBySeverity() {
        $stmt = $this->db->query("
            SELECT 
                ogrilik_turi,
                COUNT(*) as count
            FROM crimes
            WHERE ogrilik_turi IS NOT NULL
            GROUP BY ogrilik_turi
        ");
        
        return $stmt->fetchAll();
    }
    
    private function getTrafficData() {
        $stmt = $this->db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as visits,
                COUNT(DISTINCT ip_address) as unique_visitors
            FROM audit_logs
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");
        
        return $stmt->fetchAll();
    }
    
    private function logActivity($userId, $action, $table, $recordId, $details = '') {
        $stmt = $this->db->prepare("
            INSERT INTO audit_logs 
            (user_id, action, table_name, record_id, details, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $action,
            $table,
            $recordId,
            $details,
            Security::getClientIP(),
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }
    
    private function getSettings() {
        $stmt = $this->db->query("SELECT name, value FROM settings");
        $rows = $stmt->fetchAll();
        
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['name']] = $row['value'];
        }
        
        return $settings;
    }
    
    private function updateSettings($settings) {
        foreach ($settings as $name => $value) {
            $stmt = $this->db->prepare("
                INSERT INTO settings (name, value, updated_at) 
                VALUES (?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE 
                value = ?, updated_at = NOW()
            ");
            
            $stmt->execute([$name, $value, $value]);
        }
        
        // Log the action
        $this->logActivity(
            Auth::getUserId(),
            'SETTINGS_UPDATE',
            'settings',
            0,
            "Sozlamalar yangilandi"
        );
        
        return true;
    }
    
    private function createBackup() {
        $backupDir = __DIR__ . '/../backups/';
        
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . $filename;
        
        try {
            $config = require __DIR__ . '/../config/database.php';
            
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($config['host']),
                escapeshellarg($config['database']),
                escapeshellarg($filepath)
            );
            
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                return ['success' => false, 'error' => 'Backup command failed'];
            }
            
            // Compress the backup
            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive();
                $zipFile = str_replace('.sql', '.zip', $filepath);
                
                if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile($filepath, basename($filepath));
                    $zip->close();
                    
                    // Delete the original SQL file
                    unlink($filepath);
                    $filename = str_replace('.sql', '.zip', $filename);
                }
            }
            
            // Log the action
            $this->logActivity(
                Auth::getUserId(),
                'BACKUP_CREATE',
                'system',
                0,
                "Backup yaratildi: $filename"
            );
            
            return ['success' => true, 'filename' => $filename];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function restoreBackup($backupFile) {
        try {
            $config = require __DIR__ . '/../config/database.php';
            
            // Check if file is ZIP
            if (pathinfo($backupFile, PATHINFO_EXTENSION) === 'zip') {
                $zip = new ZipArchive();
                if ($zip->open($backupFile) === TRUE) {
                    $sqlFile = $backupFile . '.temp.sql';
                    $zip->extractTo(dirname($backupFile));
                    $zip->close();
                    $backupFile = $sqlFile;
                }
            }
            
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($config['host']),
                escapeshellarg($config['database']),
                escapeshellarg($backupFile)
            );
            
            exec($command, $output, $returnVar);
            
            // Clean up temp file if created
            if (isset($sqlFile) && file_exists($sqlFile)) {
                unlink($sqlFile);
            }
            
            if ($returnVar !== 0) {
                return ['success' => false, 'error' => 'Restore command failed'];
            }
            
            // Log the action
            $this->logActivity(
                Auth::getUserId(),
                'BACKUP_RESTORE',
                'system',
                0,
                "Backup tiklandi: " . basename($backupFile)
            );
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function getBackupFiles() {
        $backupDir = __DIR__ . '/../backups/';
        
        if (!is_dir($backupDir)) {
            return [];
        }
        
        $files = [];
        foreach (glob($backupDir . '*.{sql,zip}', GLOB_BRACE) as $file) {
            $files[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'modified' => filemtime($file)
            ];
        }
        
        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
        
        return $files;
    }
    
    private function clearCache() {
        // Clear session files
        session_destroy();
        
        // Clear file cache
        $cacheDir = __DIR__ . '/../cache/';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        // Clear opcache if enabled
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        // Log the action
        $this->logActivity(
            Auth::getUserId(),
            'CACHE_CLEAR',
            'system',
            0,
            "Cache tozalandi"
        );
    }
    
    private function optimizeDatabase() {
        $tables = $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->db->query("OPTIMIZE TABLE `$table`");
        }
        
        // Log the action
        $this->logActivity(
            Auth::getUserId(),
            'DB_OPTIMIZE',
            'system',
            0,
            "Ma'lumotlar bazasi optimallashtirildi"
        );
    }
    
    private function getSystemInfo() {
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Noma\'lum',
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'database_version' => $this->db->query('SELECT VERSION()')->fetchColumn(),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'timezone' => date_default_timezone_get(),
            'disk_free_space' => round(disk_free_space('/') / 1024 / 1024 / 1024, 2) . ' GB',
            'disk_total_space' => round(disk_total_space('/') / 1024 / 1024 / 1024, 2) . ' GB'
        ];
    }
    
    private function getAutoBackupSettings() {
        return [
            'enabled' => true,
            'frequency' => 'daily', // daily, weekly, monthly
            'time' => '02:00',
            'keep_days' => 30,
            'last_run' => $this->getLastBackupDate()
        ];
    }
}