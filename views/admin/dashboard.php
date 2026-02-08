<!DOCTYPE html>
<html lang="uz" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Boshqaruv Paneli - MFY Tizimi</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
        }
        
        [data-bs-theme="dark"] {
            --primary: #5a6cea;
            --secondary: #4a1bb4;
            --light: #343a40;
            --dark: #f8f9fa;
            --body-bg: #1a1a2e;
            --card-bg: #16213e;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            height: calc(100vh - 180px);
            overflow-y: auto;
        }
        
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }
        
        .nav-link .badge {
            margin-left: auto;
        }
        
        .sidebar.collapsed .nav-text {
            display: none;
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            padding: 20px;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        [data-bs-theme="dark"] .top-bar {
            background: var(--card-bg);
        }
        
        .search-box {
            width: 400px;
            position: relative;
        }
        
        .search-box input {
            border-radius: 50px;
            padding-left: 3rem;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .search-box input {
            background: #2d3748;
            border-color: #4a5568;
            color: white;
        }
        
        .search-box i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        [data-bs-theme="dark"] .stat-card {
            background: var(--card-bg);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }
        
        .stat-icon.users {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-icon.mahallas {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .stat-icon.crimes {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .stat-icon.operators {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        [data-bs-theme="dark"] .stat-number {
            color: var(--light);
        }
        
        .stat-title {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .stat-change {
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .stat-change.positive {
            color: #28a745;
        }
        
        .stat-change.negative {
            color: #dc3545;
        }
        
        /* Charts Container */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        [data-bs-theme="dark"] .chart-container {
            background: var(--card-bg);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark);
        }
        
        [data-bs-theme="dark"] .chart-title {
            color: var(--light);
        }
        
        /* Tables */
        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .data-table {
            background: var(--card-bg);
        }
        
        .data-table .table {
            margin: 0;
        }
        
        .data-table .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .data-table .table th {
            background: #2d3748;
            color: #a0aec0;
        }
        
        .data-table .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .data-table .table td {
            border-color: #4a5568;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
        }
        
        [data-bs-theme="dark"] .user-avatar {
            border-color: #4a5568;
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .status-inactive {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .quick-action-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        [data-bs-theme="dark"] .quick-action-btn {
            background: var(--card-bg);
            border-color: #4a5568;
            color: var(--light);
        }
        
        .quick-action-btn:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        
        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        /* System Health */
        .system-health {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
        }
        
        .health-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .health-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .health-info {
            flex: 1;
        }
        
        .health-label {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }
        
        .health-value {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .health-progress {
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
        }
        
        .health-progress-bar {
            height: 100%;
            background: white;
            border-radius: 3px;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            
            .sidebar:not(.collapsed) {
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed);
            }
            
            .main-content.expanded {
                margin-left: var(--sidebar-width);
            }
            
            .search-box {
                width: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-box {
                width: 100%;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <span class="logo-text">MFY Admin</span>
            </a>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="/admin/dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Boshqaruv Paneli</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/users">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Foydalanuvchilar</span>
                        <span class="badge bg-danger"><?= $stats['total_users'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/operators">
                        <i class="fas fa-user-shield"></i>
                        <span class="nav-text">Operatorlar</span>
                        <span class="badge bg-warning"><?= $stats['total_operators'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/assign-operators">
                        <i class="fas fa-user-check"></i>
                        <span class="nav-text">Operator Tayinlash</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/mahalla">
                        <i class="fas fa-city"></i>
                        <span class="nav-text">MFYlar</span>
                        <span class="badge bg-primary"><?= $stats['total_mahallas'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/crimes">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="nav-text">Jinoyatlar</span>
                        <span class="badge bg-danger"><?= $stats['total_crimes'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/nizok">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Nizok Oilalar</span>
                        <span class="badge bg-secondary"><?= $stats['total_nizok'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/orders">
                        <i class="fas fa-star"></i>
                        <span class="nav-text">Orderlar</span>
                        <span class="badge bg-warning"><?= $stats['total_orders'] ?></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/statistics">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-text">Statistika</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/audit-logs">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">Audit Loglari</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/settings">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Sozlamalar</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/backup-restore">
                        <i class="fas fa-database"></i>
                        <span class="nav-text">Backup & Restore</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4">
                    <div class="nav-link text-muted small">
                        <i class="fas fa-tools"></i>
                        <span class="nav-text">Tizim Alatları</span>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/system-logs">
                        <i class="fas fa-file-alt"></i>
                        <span class="nav-text">Sistem Loglari</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/cache-clear">
                        <i class="fas fa-broom"></i>
                        <span class="nav-text">Cache Tozalash</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/admin/db-optimize">
                        <i class="fas fa-hammer"></i>
                        <span class="nav-text">DB Optimizatsiya</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <div class="text-white fw-bold"><?= $_SESSION['username'] ?? 'Admin' ?></div>
                        <div class="text-white-50 small">Super Admin</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-light" id="toggleSidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary d-lg-none" id="mobileToggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Qidirish...">
                </div>
            </div>
            
            <div class="user-menu">
                <!-- Theme Toggle -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="themeToggle">
                    <label class="form-check-label" for="themeToggle">
                        <i class="fas fa-moon"></i>
                    </label>
                </div>
                
                <!-- Notifications -->
                <div class="dropdown notification-dropdown">
                    <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px;">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0">Bildirishnomalar</h6>
                        </div>
                        <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                            <a href="#" class="dropdown-item d-flex gap-3 py-3 border-bottom">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <div class="small">Yangi jinoyat qo'shildi</div>
                                    <div class="text-muted small">5 daqiqa oldin</div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex gap-3 py-3 border-bottom">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                                <div>
                                    <div class="small">Yangi operator ro'yxatdan o'tdi</div>
                                    <div class="text-muted small">1 soat oldin</div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex gap-3 py-3">
                                <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-database text-white"></i>
                                </div>
                                <div>
                                    <div class="small">Backup muvaffaqiyatli yakunlandi</div>
                                    <div class="text-muted small">Kecha 23:00</div>
                                </div>
                            </a>
                        </div>
                        <div class="p-3 text-center border-top">
                            <a href="#" class="text-decoration-none">Barchasini ko'rish</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <span><?= $_SESSION['username'] ?? 'Admin' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile">
                            <i class="fas fa-user-circle me-2"></i>Profil
                        </a></li>
                        <li><a class="dropdown-item" href="/settings">
                            <i class="fas fa-cog me-2"></i>Shaxsiy Sozlamalar
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/">
                            <i class="fas fa-home me-2"></i>Asosiy Sahifa
                        </a></li>
                        <li><a class="dropdown-item" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Paneli
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Chiqish
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions mb-4">
            <a href="/admin/users/create" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="fw-bold">Foydalanuvchi Qo'shish</div>
                    <small class="text-muted">Yangi foydalanuvchi yaratish</small>
                </div>
            </a>
            
            <a href="/admin/assign-operators" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="fw-bold">Operator Tayinlash</div>
                    <small class="text-muted">MFYlarga operator tayinlash</small>
                </div>
            </a>
            
            <a href="/crimes/create" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="fw-bold">Jinoyat Qo'shish</div>
                    <small class="text-muted">Yangi jinoyat kiritish</small>
                </div>
            </a>
            
            <a href="/admin/backup-restore" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <div class="fw-bold">Backup Yaratish</div>
                    <small class="text-muted">Ma'lumotlar bazasini saqlash</small>
                </div>
            </a>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?= number_format($stats['total_users']) ?></div>
                    <div class="stat-title">Jami Foydalanuvchilar</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>12.5%
                        </div>
                        <div class="text-muted small">O'tgan oyga nisbatan</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon mahallas">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="stat-number"><?= number_format($stats['total_mahallas']) ?></div>
                    <div class="stat-title">Jami MFYlar</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>8.2%
                        </div>
                        <div class="text-muted small"><?= $stats['mahallas_with_operators'] ?> ta operatorli</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon crimes">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-number"><?= number_format($stats['total_crimes']) ?></div>
                    <div class="stat-title">Jami Jinoyatlar</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down me-1"></i>3.4%
                        </div>
                        <div class="text-muted small">Bugun: <?= $stats['crimes_today'] ?> ta</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6">
                <div class="stat-card">
                    <div class="stat-icon operators">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-number"><?= number_format($stats['total_operators']) ?></div>
                    <div class="stat-title">Faol Operatorlar</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>15.7%
                        </div>
                        <div class="text-muted small"><?= $stats['active_users'] ?> ta faol</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">Jinoyatlar Statistikasi (So'nggi 6 oy)</h5>
                        <select class="form-select form-select-sm" style="width: auto;" id="crimeChartFilter">
                            <option value="monthly">Oylik</option>
                            <option value="weekly">Haftalik</option>
                            <option value="daily">Kunlik</option>
                        </select>
                    </div>
                    <div id="crimeChart" style="height: 300px;"></div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">Foydalanuvchilar Taqsimoti</h5>
                    </div>
                    <div id="userDistributionChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities & System Health -->
        <div class="row g-4">
            <!-- Recent Activities -->
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">So'nggi Faoliyatlar</h5>
                        <a href="/admin/audit-logs" class="btn btn-sm btn-outline-primary">Barchasini ko'rish</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foydalanuvchi</th>
                                    <th>Harakat</th>
                                    <th>Vaqt</th>
                                    <th>IP Manzil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activities as $activity): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($activity['user_name'] ?? 'System') ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($activity['user_full_name'] ?? '') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($activity['action']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars(substr($activity['details'] ?? '', 0, 50)) ?>...</small>
                                    </td>
                                    <td>
                                        <?= date('H:i', strtotime($activity['created_at'])) ?>
                                        <div class="text-muted small"><?= date('d.m.Y', strtotime($activity['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($activity['ip_address']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- System Health -->
            <div class="col-lg-4">
                <div class="system-health">
                    <h5 class="mb-4">Tizim Holati</h5>
                    
                    <div class="health-item">
                        <div class="health-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="health-info">
                            <div class="health-label">Ma'lumotlar Bazasi</div>
                            <div class="health-value"><?= $systemHealth['database_size'] ?> MB</div>
                        </div>
                    </div>
                    
                    <div class="health-item">
                        <div class="health-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="health-info">
                            <div class="health-label">Server Yuki</div>
                            <div class="health-value"><?= number_format($systemHealth['server_load'], 2) ?></div>
                            <div class="health-progress">
                                <div class="health-progress-bar" style="width: <?= min(100, $systemHealth['server_load'] * 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="health-item">
                        <div class="health-icon">
                            <i class="fas fa-memory"></i>
                        </div>
                        <div class="health-info">
                            <div class="health-label">Xotira</div>
                            <div class="health-value"><?= round($systemHealth['memory_usage'] / 1024 / 1024, 2) ?> MB</div>
                            <div class="health-progress">
                                <div class="health-progress-bar" style="width: <?= ($systemHealth['memory_usage'] / (intval($systemHealth['memory_limit']) * 1024 * 1024)) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="health-item">
                        <div class="health-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="health-info">
                            <div class="health-label">Ish Vaqti</div>
                            <div class="health-value"><?= $systemHealth['uptime'] ?></div>
                        </div>
                    </div>
                    
                    <div class="health-item">
                        <div class="health-icon">
                            <i class="fas fa-save"></i>
                        </div>
                        <div class="health-info">
                            <div class="health-label">Oxirgi Backup</div>
                            <div class="health-value"><?= $systemHealth['last_backup'] ?></div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="/admin/backup-restore" class="btn btn-light w-100">
                            <i class="fas fa-sync-alt me-2"></i>Backup Yaratish
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Crimes & Users -->
        <div class="row g-4 mt-4">
            <!-- Recent Crimes -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">So'nggi Jinoyatlar</h5>
                        <a href="/crimes" class="btn btn-sm btn-outline-primary">Barchasini ko'rish</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>JK Modda</th>
                                    <th>MFY</th>
                                    <th>Sana</th>
                                    <th>Holat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentCrimes as $crime): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($crime['jk_modda']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($crime['ogrilik_turi'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($crime['mahalla_nomi'] ?? '') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($crime['tuman_nomi'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?= date('H:i', strtotime($crime['created_at'])) ?>
                                        <div class="text-muted small"><?= date('d.m.Y', strtotime($crime['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $severity = $crime['ogrilik_turi'] ?? '';
                                        $badgeClass = 'bg-secondary';
                                        if ($severity === 'o\'ta og\'ir') $badgeClass = 'bg-danger';
                                        elseif ($severity === 'og\'ir') $badgeClass = 'bg-warning';
                                        elseif ($severity === 'uncha og\'ir bo\'lmagan') $badgeClass = 'bg-info';
                                        elseif ($severity === 'ijtimoiy xavfi katta bo\'lmagan') $badgeClass = 'bg-success';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($severity) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">So'nggi Foydalanuvchilar</h5>
                        <a href="/admin/users" class="btn btn-sm btn-outline-primary">Barchasini ko'rish</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foydalanuvchi</th>
                                    <th>Rol</th>
                                    <th>Qo'shilgan</th>
                                    <th>Holat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentUsers as $user): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($user['profile_picture'] !== 'default.png'): ?>
                                            <img src="/uploads/profiles/<?= $user['profile_picture'] ?>" 
                                                 alt="<?= htmlspecialchars($user['username']) ?>" 
                                                 class="user-avatar">
                                            <?php else: ?>
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $roleClass = 'bg-secondary';
                                        if ($user['role'] === 'super_admin') $roleClass = 'bg-danger';
                                        elseif ($user['role'] === 'admin') $roleClass = 'bg-primary';
                                        elseif ($user['role'] === 'operator') $roleClass = 'bg-success';
                                        elseif ($user['role'] === 'user') $roleClass = 'bg-info';
                                        ?>
                                        <span class="badge <?= $roleClass ?>"><?= htmlspecialchars($user['role']) ?></span>
                                        <?php if ($user['mahalla_nomi']): ?>
                                        <div class="text-muted small mt-1"><?= htmlspecialchars($user['mahalla_nomi']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= date('H:i', strtotime($user['created_at'])) ?>
                                        <div class="text-muted small"><?= date('d.m.Y', strtotime($user['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active">Faol</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted">
                        &copy; <?= date('Y') ?> MFY Boshqaruv Tizimi. Barcha huquqlar himoyalangan.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">
                        <span class="me-3">PHP <?= PHP_VERSION ?></span>
                        <span class="me-3">MySQL <?= $systemInfo['database_version'] ?></span>
                        <span>Server: <?= $systemInfo['server_software'] ?></span>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        themeToggle.checked = currentTheme === 'dark';
        
        themeToggle.addEventListener('change', function() {
            const theme = this.checked ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
        });
        
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const mobileToggleSidebar = document.getElementById('mobileToggleSidebar');
        
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-left');
            icon.classList.toggle('fa-chevron-right');
        });
        
        mobileToggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 && !sidebar.contains(event.target) && !mobileToggleSidebar.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
        
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Crime Statistics Chart
            const crimeChartOptions = {
                series: [{
                    name: 'Jinoyatlar soni',
                    data: [30, 40, 35, 50, 49, 60, 70, 91, 125, 110, 95, 120]
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: ['#4361ee'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: ['Yan', 'Fev', 'Mar', 'Apr', 'May', 'Iyun', 'Iyul', 'Avg', 'Sen', 'Okt', 'Noy', 'Dek'],
                    labels: {
                        style: {
                            colors: '#6c757d'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6c757d'
                        }
                    }
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 5
                },
                tooltip: {
                    theme: document.documentElement.getAttribute('data-bs-theme')
                }
            };
            
            const crimeChart = new ApexCharts(document.querySelector("#crimeChart"), crimeChartOptions);
            crimeChart.render();
            
            // User Distribution Chart
            const userDistributionOptions = {
                series: [44, 55, 41, 17],
                labels: ['Super Admin', 'Admin', 'Operator', 'Foydalanuvchi'],
                chart: {
                    type: 'donut',
                    height: 300
                },
                colors: ['#f72585', '#4361ee', '#4cc9f0', '#7209b7'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Jami',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#333'
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            
            const userDistributionChart = new ApexCharts(document.querySelector("#userDistributionChart"), userDistributionOptions);
            userDistributionChart.render();
            
            // Filter change for crime chart
            document.getElementById('crimeChartFilter').addEventListener('change', function() {
                // In real application, this would fetch new data via AJAX
                console.log('Filter changed to:', this.value);
            });
            
            // Initialize DataTables
            $('.table').DataTable({
                pageLength: 5,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Barchasi']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json'
                },
                dom: '<"top"f>rt<"bottom"lp><"clear">',
                responsive: true
            });
            
            // Initialize Select2
            $('.form-select').select2({
                minimumResultsForSearch: 10,
                width: 'auto'
            });
            
            // Search functionality
            $('.search-box input').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    const query = $(this).val().trim();
                    if (query) {
                        window.location.href = `/admin/search?q=${encodeURIComponent(query)}`;
                    }
                }
            });
            
            // Real-time updates (simulated)
            setInterval(() => {
                // Update online users count
                fetch('/api/v1/stats/online-users')
                    .then(response => response.json())
                    .then(data => {
                        // Update badge or other elements
                    });
                
                // Check for new notifications
                fetch('/api/v1/notifications/unread')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            $('.notification-badge').text(data.count).show();
                        } else {
                            $('.notification-badge').hide();
                        }
                    });
            }, 30000); // Every 30 seconds
            
            // Auto-refresh charts every 5 minutes
            setInterval(() => {
                crimeChart.updateSeries([{
                    data: [Math.floor(Math.random() * 150), Math.floor(Math.random() * 150), Math.floor(Math.random() * 150)]
                }]);
            }, 300000);
        });
        
        // Print functionality
        window.printDashboard = function() {
            window.print();
        };
        
        // Export data
        window.exportData = function(format) {
            fetch(`/api/v1/export/dashboard?format=${format}`)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `dashboard-report-${new Date().toISOString().split('T')[0]}.${format}`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                });
        };
        
        // Logout with confirmation
        document.querySelectorAll('a[href="/logout"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Haqiqatan chiqmoqchimisiz?')) {
                    window.location.href = '/logout';
                }
            });
        });
    </script>
</body>
</html>