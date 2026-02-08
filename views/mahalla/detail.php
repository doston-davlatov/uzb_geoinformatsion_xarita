<?php
// views/mahalla/detail.php
if (!isset($mahalla) || !isset($stats)) {
    header("Location: /mahalla");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uz" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mahalla['nomi']) ?> - MFY Holati</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
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
        }
        
        [data-bs-theme="dark"] {
            --primary: #5a6cea;
            --secondary: #4a1bb4;
            --light: #343a40;
            --dark: #f8f9fa;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
        }
        
        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        [data-bs-theme="dark"] .glass-card {
            background: rgba(30, 30, 46, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(31, 38, 135, 0.25);
        }
        
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .stat-card:hover {
            transform: scale(1.05);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }
        
        [data-bs-theme="dark"] .stat-number {
            background: linear-gradient(90deg, #5a6cea, #4a1bb4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        #map {
            height: 600px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            padding: 1rem 1.5rem;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: transparent;
        }
        
        .custom-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .layer-control {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 1rem;
            width: 280px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        
        [data-bs-theme="dark"] .layer-control {
            background: rgba(30, 30, 46, 0.95);
        }
        
        .layer-item {
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .layer-item:hover {
            background: rgba(67, 97, 238, 0.1);
        }
        
        .layer-item.active {
            background: rgba(67, 97, 238, 0.2);
            border-left: 4px solid var(--primary);
        }
        
        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1030;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }
        
        .floating-action-btn:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 15px 40px rgba(67, 97, 238, 0.4);
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary), transparent);
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -33px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid white;
        }
        
        .dark-mode-toggle {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1030;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light);
            color: var(--dark);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        [data-bs-theme="dark"] .dark-mode-toggle {
            background: var(--dark);
            color: var(--light);
        }
        
        .dark-mode-toggle:hover {
            transform: rotate(30deg);
        }
        
        @media (max-width: 768px) {
            #map {
                height: 400px;
            }
            
            .layer-control {
                width: calc(100% - 40px);
                max-width: 280px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    
    <!-- Theme Toggle -->
    <div class="dark-mode-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </div>
    
    <!-- Floating Action Button -->
    <div class="floating-action-btn bg-primary text-white" id="actionMenuBtn">
        <i class="fas fa-plus"></i>
    </div>
    
    <!-- Action Menu (Hidden by default) -->
    <div class="position-fixed bottom-100 end-30 mb-3" id="actionMenu" style="display: none; z-index: 1031;">
        <div class="glass-card p-3" style="width: 250px;">
            <h6 class="fw-bold mb-3 text-primary">Tezkor Amallar</h6>
            <div class="d-grid gap-2">
                <a href="/crimes/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Jinoyat qo'shish
                </a>
                <a href="/nizok/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-users me-2"></i>Nizok oila
                </a>
                <a href="/order/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-warning">
                    <i class="fas fa-star me-2"></i>Order olgan
                </a>
                <a href="/muammoli/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-info">
                    <i class="fas fa-exclamation-circle me-2"></i>Muammoli joy
                </a>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 shadow-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
                <i class="fas fa-map-marked-alt"></i>
                <span>MFY Tizimi</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Boshqaruv
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/mahalla">
                            <i class="fas fa-city me-1"></i>MFYlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/statistics">
                            <i class="fas fa-chart-line me-1"></i>Statistika
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-database me-1"></i>Ma'lumotlar
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/crimes">Jinoyatlar</a></li>
                            <li><a class="dropdown-item" href="/nizok">Nizok oilalar</a></li>
                            <li><a class="dropdown-item" href="/orders">Order olganlar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/muammoli">Muammoli joylar</a></li>
                            <li><a class="dropdown-item" href="/ovloq">Ovloq joylar</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="navbar-nav align-items-center">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <span><?= $_SESSION['username'] ?? 'Foydalanuvchi' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">
                                <i class="fas fa-user-circle me-2"></i>Profil
                            </a></li>
                            <li><a class="dropdown-item" href="/settings">
                                <i class="fas fa-cog me-2"></i>Sozlamalar
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Chiqish
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Container -->
    <div class="container-fluid py-4 px-3 px-lg-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item"><a href="/mahalla" class="text-decoration-none">MFYlar</a></li>
                                    <li class="breadcrumb-item"><a href="/mahalla?viloyat=<?= $mahalla['viloyat_id'] ?>" class="text-decoration-none"><?= $mahalla['viloyat_nomi'] ?></a></li>
                                    <li class="breadcrumb-item"><a href="/mahalla?tuman=<?= $mahalla['tuman_id'] ?>" class="text-decoration-none"><?= $mahalla['tuman_nomi'] ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $mahalla['nomi'] ?></li>
                                </ol>
                            </nav>
                            
                            <h1 class="fw-bold mb-2"><?= htmlspecialchars($mahalla['nomi']) ?></h1>
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <span class="badge bg-primary">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= $mahalla['viloyat_nomi'] ?>, <?= $mahalla['tuman_nomi'] ?>
                                </span>
                                <?php if ($mahalla['operator_username']): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-user-shield me-1"></i>
                                        Inspektor: <?= $mahalla['operator_username'] ?>
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-info">
                                    <i class="fas fa-calendar me-1"></i>
                                    Qo'shilgan: <?= date('d.m.Y', strtotime($mahalla['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="exportDataBtn">
                                    <i class="fas fa-download me-2"></i>Export
                                </button>
                                <?php if (Auth::hasPermission('admin')): ?>
                                    <a href="/mahalla/<?= $mahalla['id'] ?>/edit" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Tahrirlash
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['jinoyatlar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-exclamation-triangle me-1 text-danger"></i>
                        Jinoyatlar
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-danger" style="width: <?= min(100, $stats['jinoyatlar']) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['nizok_oilalar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-users me-1 text-secondary"></i>
                        Nizok oilalar
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-secondary" style="width: <?= min(100, $stats['nizok_oilalar'] * 10) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['orderlar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-star me-1 text-warning"></i>
                        Orderlar
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: <?= min(100, $stats['orderlar'] * 20) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['ochoklar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-fire me-1 text-danger"></i>
                        Jinoyat o'choqlari
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-danger" style="width: <?= min(100, $stats['ochoklar'] * 20) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['muammoli_joylar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-exclamation-circle me-1 text-info"></i>
                        Muammoli joylar
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: <?= min(100, $stats['muammoli_joylar'] * 20) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card glass-card">
                    <div class="stat-number"><?= number_format($stats['ovloq_joylar']) ?></div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-map-marker-alt me-1" style="color: #ff8c00;"></i>
                        Ovloq joylar
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar" style="width: <?= min(100, $stats['ovloq_joylar'] * 20) ?>%; background-color: #ff8c00;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column - Map -->
            <div class="col-lg-8">
                <div class="glass-card p-3">
                    <div id="map"></div>
                    
                    <!-- Layer Control -->
                    <div class="layer-control">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-layer-group me-2 text-primary"></i>
                            Xarita Qatlamlari
                        </h6>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mahallaBoundary" checked>
                                <label class="form-check-label" for="mahallaBoundary">
                                    MFY Chegarasi
                                </label>
                            </div>
                        </div>
                        
                        <div id="layerList">
                            <!-- Layers will be added dynamically -->
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-grid">
                            <button class="btn btn-sm btn-outline-primary" onclick="fitToBounds()">
                                <i class="fas fa-expand-alt me-2"></i>MFYga Zoom
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="glass-card p-3 h-100">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>
                                Jinoyatlar (Yillik)
                            </h6>
                            <canvas id="crimesByYearChart" height="200"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card p-3 h-100">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>
                                Jinoyat Turlari
                            </h6>
                            <canvas id="crimeTypesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Info & Actions -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="glass-card p-3 mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Tezkor Amallar
                    </h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="/crimes/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-danger w-100 mb-2">
                                <i class="fas fa-plus me-1"></i>Jinoyat
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/nizok/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-secondary w-100 mb-2">
                                <i class="fas fa-plus me-1"></i>Nizok Oila
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/order/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-plus me-1"></i>Order
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/muammoli/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-info w-100 mb-2">
                                <i class="fas fa-plus me-1"></i>Muammoli Joy
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/ochok/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-outline-danger w-100">
                                <i class="fas fa-plus me-1"></i>O'choq
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/ovloq/create?mahalla_id=<?= $mahalla['id'] ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-plus me-1"></i>Ovloq Joy
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="glass-card p-3 mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-history me-2 text-primary"></i>
                        So'nggi Faoliyat
                    </h6>
                    <div class="timeline">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-dark"><?= htmlspecialchars($activity['title']) ?></strong>
                                    <small class="text-muted"><?= $activity['time'] ?></small>
                                </div>
                                <p class="mb-0 small"><?= htmlspecialchars($activity['description']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Statistics Summary -->
                <div class="glass-card p-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Statistik Ko'rsatkichlar
                    </h6>
                    <div id="statsChart"></div>
                </div>
            </div>
        </div>
        
        <!-- Data Tables -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glass-card p-3">
                    <ul class="nav nav-tabs" id="dataTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#crimesTab">
                                <i class="fas fa-exclamation-triangle me-2"></i>Jinoyatlar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nizokTab">
                                <i class="fas fa-users me-2"></i>Nizok Oilalar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ordersTab">
                                <i class="fas fa-star me-2"></i>Orderlar
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-3" id="dataTabsContent">
                        <!-- Crimes Tab -->
                        <div class="tab-pane fade show active" id="crimesTab">
                            <table class="table table-hover" id="crimesTable">
                                <thead>
                                    <tr>
                                        <th>JK Modda</th>
                                        <th>Turi</th>
                                        <th>Sana</th>
                                        <th>Holati</th>
                                        <th>Harakatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Nizok Tab -->
                        <div class="tab-pane fade" id="nizokTab">
                            <table class="table table-hover" id="nizokTable">
                                <thead>
                                    <tr>
                                        <th>F.I.Sh.</th>
                                        <th>A'zolar</th>
                                        <th>Status</th>
                                        <th>Qo'shilgan</th>
                                        <th>Harakatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="ordersTab">
                            <table class="table table-hover" id="ordersTable">
                                <thead>
                                    <tr>
                                        <th>F.I.Sh.</th>
                                        <th>Order</th>
                                        <th>Berilgan sana</th>
                                        <th>Harakatlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ma'lumotlarni Yuklab Olish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" id="exportFormat">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                            <option value="json">JSON (.json)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ma'lumotlar</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportCrimes" checked>
                            <label class="form-check-label" for="exportCrimes">Jinoyatlar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportNizok" checked>
                            <label class="form-check-label" for="exportNizok">Nizok oilalar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportOrders" checked>
                            <label class="form-check-label" for="exportOrders">Orderlar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportStats" checked>
                            <label class="form-check-label" for="exportStats">Statistik ma'lumotlar</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vaqt oralig'i</label>
                        <select class="form-select" id="exportDateRange">
                            <option value="all">Hammasi</option>
                            <option value="year">So'nggi yil</option>
                            <option value="month">So'nggi oy</option>
                            <option value="week">So'nggi hafta</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="button" class="btn btn-primary" onclick="startExport()">
                        <i class="fas fa-download me-2"></i>Yuklab olish
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Global variables
        let map, mahallaPolygon, markersLayer, crimeMarkers = [];
        const mahallaId = <?= $mahalla['id'] ?>;
        const mahallaCenter = [
            <?= $mahalla['markaz_lat'] ?? 41.3111 ?>,
            <?= $mahalla['markaz_lng'] ?? 69.2797 ?>
        ];
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            initCharts();
            initDataTables();
            initEventListeners();
            loadMapData();
        });
        
        // Initialize map
        function initMap() {
            map = L.map('map').setView(mahallaCenter, 14);
            
            // Tile layers
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            const yandexLayer = L.tileLayer('https://core-renderer-tiles.maps.yandex.net/tiles?l=map&x={x}&y={y}&z={z}&lang=ru_RU', {
                attribution: '© Yandex'
            });
            
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri'
            });
            
            // Layer control
            const baseLayers = {
                "OpenStreetMap": osmLayer,
                "Yandex Maps": yandexLayer,
                "Satellite": satelliteLayer
            };
            
            L.control.layers(baseLayers).addTo(map);
            
            // Initialize layers
            markersLayer = L.layerGroup().addTo(map);
            
            // Add mahalla boundary if exists
            const polygonData = <?= json_encode($mahalla['polygon']) ?>;
            if (polygonData) {
                try {
                    const geo = JSON.parse(polygonData);
                    mahallaPolygon = L.geoJSON(geo, {
                        color: '#4361ee',
                        weight: 3,
                        fillColor: '#4361ee',
                        fillOpacity: 0.1,
                        interactive: false
                    }).addTo(map);
                    
                    // Fit to bounds
                    map.fitBounds(mahallaPolygon.getBounds());
                } catch (e) {
                    console.error('Polygon parse error:', e);
                }
            }
            
            // Add scale control
            L.control.scale({imperial: false}).addTo(map);
            
            // Add fullscreen control
            map.addControl(new L.Control.Fullscreen());
        }
        
        // Load map data via AJAX
        function loadMapData() {
            // Load crimes
            fetch(`/api/v1/mahalla/${mahallaId}/crimes`)
                .then(response => response.json())
                .then(data => {
                    addCrimeMarkers(data);
                    updateLayerList('crimes', data.length);
                });
            
            // Load nizok families
            fetch(`/api/v1/mahalla/${mahallaId}/nizok`)
                .then(response => response.json())
                .then(data => {
                    addNizokMarkers(data);
                    updateLayerList('nizok', data.length);
                });
            
            // Load orders
            fetch(`/api/v1/mahalla/${mahallaId}/orders`)
                .then(response => response.json())
                .then(data => {
                    addOrderMarkers(data);
                    updateLayerList('orders', data.length);
                });
            
            // Load crime hotspots
            fetch(`/api/v1/mahalla/${mahallaId}/hotspots`)
                .then(response => response.json())
                .then(data => {
                    addHotspotPolygons(data);
                    updateLayerList('hotspots', data.length);
                });
            
            // Load problematic places
            fetch(`/api/v1/mahalla/${mahallaId}/problematic`)
                .then(response => response.json())
                .then(data => {
                    addProblematicMarkers(data);
                    updateLayerList('problematic', data.length);
                });
        }
        
        // Add crime markers to map
        function addCrimeMarkers(crimes) {
            crimes.forEach(crime => {
                if (crime.lat && crime.lng) {
                    const marker = L.marker([crime.lat, crime.lng], {
                        icon: getCrimeIcon(crime.ogrilik_turi)
                    });
                    
                    const popupContent = `
                        <div class="popup-content">
                            <h6 class="fw-bold text-danger">${crime.jk_modda}</h6>
                            <p class="mb-1"><small><strong>Turi:</strong> ${crime.ogrilik_turi}</small></p>
                            <p class="mb-1"><small><strong>Sana:</strong> ${crime.sodir_vaqti}</small></p>
                            ${crime.jinoyat_matni ? `<p class="mt-2">${crime.jinoyat_matni.substring(0, 100)}...</p>` : ''}
                            <div class="mt-2">
                                <a href="/crimes/${crime.id}" class="btn btn-sm btn-danger">Batafsil</a>
                            </div>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    marker.addTo(markersLayer);
                    crimeMarkers.push(marker);
                }
            });
        }
        
        // Get crime icon based on severity
        function getCrimeIcon(severity) {
            let color = 'blue';
            
            switch(severity) {
                case 'o\'ta og\'ir':
                    color = 'black';
                    break;
                case 'og\'ir':
                    color = 'red';
                    break;
                case 'uncha og\'ir bo\'lmagan':
                    color = 'green';
                    break;
                case 'ijtimoiy xavfi katta bo\'lmagan':
                    color = 'yellow';
                    break;
            }
            
            return L.icon({
                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${color}.png`,
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png'
            });
        }
        
        // Add nizok family markers
        function addNizokMarkers(nizoklar) {
            // Similar implementation for nizok markers
        }
        
        // Add order markers
        function addOrderMarkers(orders) {
            // Similar implementation for order markers
        }
        
        // Add hotspot polygons
        function addHotspotPolygons(hotspots) {
            // Similar implementation for hotspot polygons
        }
        
        // Update layer list in control panel
        function updateLayerList(type, count) {
            const layerList = document.getElementById('layerList');
            const layerItem = document.createElement('div');
            layerItem.className = 'layer-item active';
            layerItem.dataset.layer = type;
            
            let icon = '', label = '';
            
            switch(type) {
                case 'crimes':
                    icon = '<i class="fas fa-exclamation-triangle text-danger"></i>';
                    label = 'Jinoyatlar';
                    break;
                case 'nizok':
                    icon = '<i class="fas fa-users text-secondary"></i>';
                    label = 'Nizok Oilalar';
                    break;
                case 'orders':
                    icon = '<i class="fas fa-star text-warning"></i>';
                    label = 'Order Olganlar';
                    break;
                case 'hotspots':
                    icon = '<i class="fas fa-fire text-danger"></i>';
                    label = 'Jinoyat O\'choqlari';
                    break;
                case 'problematic':
                    icon = '<i class="fas fa-exclamation-circle text-info"></i>';
                    label = 'Muammoli Joylar';
                    break;
            }
            
            layerItem.innerHTML = `
                ${icon}
                <span>${label}</span>
                <span class="badge bg-primary ms-auto">${count}</span>
            `;
            
            layerItem.addEventListener('click', function() {
                this.classList.toggle('active');
                const layerType = this.dataset.layer;
                toggleLayer(layerType);
            });
            
            layerList.appendChild(layerItem);
        }
        
        // Toggle layer visibility
        function toggleLayer(layerType) {
            // Implementation for toggling layers
        }
        
        // Fit map to mahalla bounds
        function fitToBounds() {
            if (mahallaPolygon) {
                map.fitBounds(mahallaPolygon.getBounds());
            } else {
                map.setView(mahallaCenter, 14);
            }
        }
        
        // Initialize charts
        function initCharts() {
            // Crimes by year chart
            const ctx1 = document.getElementById('crimesByYearChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
                    datasets: [{
                        label: 'Jinoyatlar soni',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
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
            
            // Crime types chart
            const ctx2 = document.getElementById('crimeTypesChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['O\'g\'rilik', 'Talan', 'Zo\'ravonlik', 'Boshqa'],
                    datasets: [{
                        data: [40, 25, 20, 15],
                        backgroundColor: [
                            '#f72585',
                            '#4361ee',
                            '#4cc9f0',
                            '#7209b7'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // ApexChart for statistics
            const options = {
                series: [{
                    name: 'Jinoyatlar',
                    data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                }],
                chart: {
                    height: 200,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#4361ee'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };
            
            const chart = new ApexCharts(document.querySelector("#statsChart"), options);
            chart.render();
        }
        
        // Initialize DataTables
        function initDataTables() {
            $('#crimesTable').DataTable({
                ajax: `/api/v1/mahalla/${mahallaId}/crimes-table`,
                columns: [
                    { data: 'jk_modda' },
                    { data: 'ogrilik_turi' },
                    { data: 'sodir_vaqti' },
                    { data: 'status' },
                    { data: 'actions' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json'
                }
            });
            
            // Similar for other tables
        }
        
        // Initialize event listeners
        function initEventListeners() {
            // Theme toggle
            document.getElementById('themeToggle').addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                
                const icon = this.querySelector('i');
                icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                
                // Save preference to localStorage
                localStorage.setItem('theme', newTheme);
            });
            
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            document.querySelector('#themeToggle i').className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            
            // Floating action button
            document.getElementById('actionMenuBtn').addEventListener('click', function() {
                const menu = document.getElementById('actionMenu');
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });
            
            // Export button
            document.getElementById('exportDataBtn').addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('exportModal'));
                modal.show();
            });
            
            // Mahalla boundary toggle
            document.getElementById('mahallaBoundary').addEventListener('change', function() {
                if (mahallaPolygon) {
                    if (this.checked) {
                        map.addLayer(mahallaPolygon);
                    } else {
                        map.removeLayer(mahallaPolygon);
                    }
                }
            });
            
            // Close action menu when clicking outside
            document.addEventListener('click', function(event) {
                const actionBtn = document.getElementById('actionMenuBtn');
                const actionMenu = document.getElementById('actionMenu');
                
                if (!actionBtn.contains(event.target) && !actionMenu.contains(event.target)) {
                    actionMenu.style.display = 'none';
                }
            });
        }
        
        // Start export process
        function startExport() {
            const format = document.getElementById('exportFormat').value;
            const dateRange = document.getElementById('exportDateRange').value;
            
            const data = {
                mahalla_id: mahallaId,
                format: format,
                date_range: dateRange,
                include_crimes: document.getElementById('exportCrimes').checked,
                include_nizok: document.getElementById('exportNizok').checked,
                include_orders: document.getElementById('exportOrders').checked,
                include_stats: document.getElementById('exportStats').checked
            };
            
            // Show loading
            const exportBtn = document.querySelector('#exportModal .btn-primary');
            exportBtn.disabled = true;
            exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Yuklanmoqda...';
            
            // Send export request
            fetch('/api/v1/export', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.blob())
            .then(blob => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `MFY_${mahallaId}_export.${format === 'excel' ? 'xlsx' : format === 'csv' ? 'csv' : format === 'pdf' ? 'pdf' : 'json'}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Reset button
                exportBtn.disabled = false;
                exportBtn.innerHTML = '<i class="fas fa-download me-2"></i>Yuklab olish';
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
            })
            .catch(error => {
                console.error('Export error:', error);
                alert('Export failed: ' + error.message);
                
                // Reset button
                exportBtn.disabled = false;
                exportBtn.innerHTML = '<i class="fas fa-download me-2"></i>Yuklab olish';
            });
        }
        
        // Print function
        window.printReport = function() {
            window.print();
        };
        
        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    map.setView([lat, lng], 16);
                    
                    // Add marker for current location
                    L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41]
                        })
                    })
                    .addTo(map)
                    .bindPopup('Sizning joylashuvingiz')
                    .openPopup();
                });
            } else {
                alert('Geolocation is not supported by your browser');
            }
        }
    </script>
</body>
</html>