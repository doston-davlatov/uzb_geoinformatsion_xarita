<!DOCTYPE html>
<html lang="uz" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Tahlillar - MFY Tizimi</title>
    
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        
        .header-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .header-card {
            background: #16213e;
        }
        
        .period-selector {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .period-selector {
            background: #1e3058;
        }
        
        .period-btn {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            color: #6c757d;
        }
        
        [data-bs-theme="dark"] .period-btn {
            background: #2d3748;
            border-color: #4a5568;
            color: #a0aec0;
        }
        
        .period-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }
        
        .period-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .chart-container {
            background: #1e3058;
        }
        
        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }
        
        [data-bs-theme="dark"] .chart-title {
            color: var(--light);
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        [data-bs-theme="dark"] .stat-card {
            background: #1e3058;
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
            margin: 0 auto 1rem;
            color: white;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        [data-bs-theme="dark"] .stat-number {
            color: var(--light);
        }
        
        .stat-label {
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
        
        .heatmap-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .heatmap-container {
            background: #1e3058;
        }
        
        .heatmap-day {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            margin: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        
        .heatmap-legend {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
        }
        
        .heatmap-legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }
        
        .heatmap-legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        
        .comparison-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
        }
        
        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .comparison-item:last-child {
            border-bottom: none;
        }
        
        .comparison-label {
            font-weight: 500;
        }
        
        .comparison-value {
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .comparison-change {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .data-table {
            background: #1e3058;
        }
        
        .export-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        [data-bs-theme="dark"] .export-btn {
            background: #2d3748;
            border-color: #4a5568;
            color: #a0aec0;
        }
        
        .export-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .period-selector {
                overflow-x: auto;
            }
            
            .period-btns {
                display: flex;
                flex-wrap: nowrap;
                gap: 0.5rem;
            }
            
            .period-btn {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <div class="header-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Admin Paneli</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Statistika</li>
                    </ol>
                </nav>
                <h1 class="h2 fw-bold mb-0">Statistik Tahlillar</h1>
                <p class="text-muted mb-0">Tizimning umumiy statistik ma'lumotlari va tahlillari</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export qilish
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Format tanlang</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="exportStatistics('pdf')">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>PDF hisobot
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportStatistics('excel')">
                            <i class="fas fa-file-excel me-2 text-success"></i>Excel ma'lumotlar
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportStatistics('csv')">
                            <i class="fas fa-file-csv me-2 text-primary"></i>CSV ma'lumotlar
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="printStatistics()">
                            <i class="fas fa-print me-2"></i>Chop etish
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Period Selector -->
    <div class="period-selector">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold">Vaqt oralig'i</h6>
            <div class="text-muted small">
                <?= date('d.m.Y') ?> holatiga
            </div>
        </div>
        
        <div class="period-btns">
            <button type="button" class="period-btn active" data-period="daily">Kunlik</button>
            <button type="button" class="period-btn" data-period="weekly">Haftalik</button>
            <button type="button" class="period-btn" data-period="monthly">Oylik</button>
            <button type="button" class="period-btn" data-period="yearly">Yillik</button>
            <button type="button" class="period-btn" data-period="all">Hammasi</button>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Boshlang'ich sana</label>
                <input type="date" class="form-control" id="startDate" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tugash sanasi</label>
                <input type="date" class="form-control" id="endDate" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
    </div>
    
    <!-- Summary Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number">
                    <?= array_sum(array_column($stats['crimes_by_year'], 'total')) ?>
                </div>
                <div class="stat-label">Jami Jinoyatlar</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    <?= round((end($stats['crimes_by_year'])['total'] - reset($stats['crimes_by_year'])['total']) / reset($stats['crimes_by_year'])['total'] * 100, 1) ?>%
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">
                    <?= array_sum(array_column($stats['nizok_by_region'], 'total')) ?>
                </div>
                <div class="stat-label">Nizok Oilalar</div>
                <div class="stat-label">
                    <?= array_sum(array_column($stats['nizok_by_region'], 'active')) ?> ta faol
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number">
                    <?php
                    $currentYear = date('Y');
                    $currentYearOrders = array_sum(array_filter($stats['crimes_by_year'], fn($item) => $item['year'] == $currentYear));
                    ?>
                    <?= $currentYearOrders ?>
                </div>
                <div class="stat-label"><?= $currentYear ?> yil Jinoyatlar</div>
                <div class="stat-change positive">
                    <i class="fas fa-calendar me-1"></i>Joriy yil
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number">
                    <?= count($stats['top_mahallas_crimes']) ?>
                </div>
                <div class="stat-label">Yuqori MFYlar</div>
                <div class="stat-label">
                    <?= $stats['top_mahallas_crimes'][0]['crime_count'] ?? 0 ?> ta eng ko'p
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Crimes by Year -->
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="chart-title">Jinoyatlar Dinamikasi (Yillik)</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>Filtr
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="filterChart('yearly', 'all')">Barcha yillar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterChart('yearly', 'last5')">So'nggi 5 yil</a></li>
                            <li><a class="dropdown-item" href="#" onclick="filterChart('yearly', 'last10')">So'nggi 10 yil</a></li>
                        </ul>
                    </div>
                </div>
                <div id="crimesByYearChart" style="height: 300px;"></div>
            </div>
        </div>
        
        <!-- Crimes by Severity -->
        <div class="col-lg-4">
            <div class="chart-container">
                <h5 class="chart-title">Jinoyatlar Og'irligi</h5>
                <div id="crimesBySeverityChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 2 -->
    <div class="row g-4 mb-4">
        <!-- Nizok by Region -->
        <div class="col-lg-6">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="chart-title">Nizok Oilalar (Viloyatlar bo'yicha)</h5>
                    <a href="#" class="export-btn btn-sm" onclick="exportRegionData()">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <div id="nizokByRegionChart" style="height: 350px;"></div>
            </div>
        </div>
        
        <!-- Time Analysis -->
        <div class="col-lg-6">
            <div class="chart-container">
                <h5 class="chart-title">Vaqt Tahlili</h5>
                <ul class="nav nav-tabs" id="timeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#hoursTab">
                            Soatlar bo'yicha
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#daysTab">
                            Kunlar bo'yicha
                        </button>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="hoursTab">
                        <div id="crimesByHourChart" style="height: 250px;"></div>
                    </div>
                    <div class="tab-pane fade" id="daysTab">
                        <div id="crimesByDayChart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Heatmap and Comparison -->
    <div class="row g-4 mb-4">
        <!-- Activity Heatmap -->
        <div class="col-lg-8">
            <div class="heatmap-container">
                <h5 class="chart-title">Faollik Xaritasi (Oxirgi 3 oy)</h5>
                <div id="activityHeatmap" class="mt-3">
                    <!-- Heatmap will be generated by JavaScript -->
                </div>
                <div class="heatmap-legend">
                    <div class="heatmap-legend-item">
                        <div class="heatmap-legend-color" style="background: #ebedf0;"></div>
                        <span>0 ta</span>
                    </div>
                    <div class="heatmap-legend-item">
                        <div class="heatmap-legend-color" style="background: #9be9a8;"></div>
                        <span>1-5 ta</span>
                    </div>
                    <div class="heatmap-legend-item">
                        <div class="heatmap-legend-color" style="background: #40c463;"></div>
                        <span>6-10 ta</span>
                    </div>
                    <div class="heatmap-legend-item">
                        <div class="heatmap-legend-color" style="background: #30a14e;"></div>
                        <span>11-20 ta</span>
                    </div>
                    <div class="heatmap-legend-item">
                        <div class="heatmap-legend-color" style="background: #216e39;"></div>
                        <span>20+ ta</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Year Comparison -->
        <div class="col-lg-4">
            <div class="comparison-card">
                <h5 class="mb-3 text-white">Yilni O'zaro Solishtirish</h5>
                
                <?php
                $currentYear = date('Y');
                $prevYear = $currentYear - 1;
                
                $currentYearData = array_filter($stats['crimes_by_year'], fn($item) => $item['year'] == $currentYear);
                $prevYearData = array_filter($stats['crimes_by_year'], fn($item) => $item['year'] == $prevYear);
                
                $currentTotal = $currentYearData ? reset($currentYearData)['total'] : 0;
                $prevTotal = $prevYearData ? reset($prevYearData)['total'] : 0;
                
                $changePercent = $prevTotal > 0 ? round((($currentTotal - $prevTotal) / $prevTotal) * 100, 1) : 0;
                ?>
                
                <div class="comparison-item">
                    <div>
                        <div class="comparison-label"><?= $currentYear ?> yil</div>
                        <div class="comparison-change">
                            <?= $changePercent >= 0 ? '+' : '' ?><?= $changePercent ?>% o'tgan yilga nisbatan
                        </div>
                    </div>
                    <div class="comparison-value"><?= $currentTotal ?></div>
                </div>
                
                <div class="comparison-item">
                    <div class="comparison-label"><?= $prevYear ?> yil</div>
                    <div class="comparison-value"><?= $prevTotal ?></div>
                </div>
                
                <div class="comparison-item">
                    <div class="comparison-label">O'rtacha oylik</div>
                    <div class="comparison-value"><?= round($currentTotal / 12) ?></div>
                </div>
                
                <div class="comparison-item">
                    <div class="comparison-label">Eng faol oy</div>
                    <div class="comparison-value">
                        <?php
                        $maxMonth = $stats['crimes_by_month'] ? max(array_column($stats['crimes_by_month'], 'count')) : 0;
                        echo $maxMonth;
                        ?>
                    </div>
                </div>
                
                <div class="comparison-item">
                    <div class="comparison-label">O'rtacha kunlik</div>
                    <div class="comparison-value"><?= round($currentTotal / 365, 1) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Mahallas Table -->
    <div class="chart-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="chart-title">Eng Ko'p Jinoyat Sodir Bo'lgan MFYlar</h5>
            <a href="#" class="btn btn-sm btn-outline-primary" onclick="showAllMahallas()">
                <i class="fas fa-list me-1"></i>Barchasini ko'rish
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>MFY Nomi</th>
                        <th>Viloyat</th>
                        <th>Tuman</th>
                        <th>Jinoyatlar soni</th>
                        <th>Foiz</th>
                        <th>Holat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['top_mahallas_crimes'] as $index => $mahalla): ?>
                    <tr>
                        <td>
                            <span class="badge bg-primary"><?= $index + 1 ?></span>
                        </td>
                        <td>
                            <a href="/mahalla/<?= $mahalla['id'] ?? '#' ?>" class="text-decoration-none fw-bold">
                                <?= htmlspecialchars($mahalla['mahalla']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($mahalla['viloyat']) ?></td>
                        <td><?= htmlspecialchars($mahalla['tuman']) ?></td>
                        <td>
                            <div class="fw-bold"><?= $mahalla['crime_count'] ?></div>
                            <div class="progress" style="height: 4px; width: 100px;">
                                <div class="progress-bar bg-danger" style="width: <?= min(100, $mahalla['crime_count']) ?>%"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?= $mahalla['percentage'] > 10 ? 'danger' : ($mahalla['percentage'] > 5 ? 'warning' : 'success') ?>">
                                <?= $mahalla['percentage'] ?>%
                            </span>
                        </td>
                        <td>
                            <?php if ($mahalla['crime_count'] > 50): ?>
                            <span class="badge bg-danger">Xavfli</span>
                            <?php elseif ($mahalla['crime_count'] > 20): ?>
                            <span class="badge bg-warning">Ehtiyot</span>
                            <?php else: ?>
                            <span class="badge bg-success">Xavfsiz</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- User Activity Statistics -->
    <div class="chart-container">
        <h5 class="chart-title">Foydalanuvchi Faolligi</h5>
        <div class="row">
            <div class="col-lg-6">
                <div id="userActivityChart" style="height: 300px;"></div>
            </div>
            <div class="col-lg-6">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Foydalanuvchi</th>
                                <th>Rol</th>
                                <th>Faol kunlar</th>
                                <th>Jami harakatlar</th>
                                <th>Oxirgi faollik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['user_activity'] as $user): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($user['full_name']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= $user['active_days'] ?></div>
                                    <div class="progress" style="height: 4px; width: 80px;">
                                        <div class="progress-bar bg-success" style="width: <?= min(100, ($user['active_days'] / 30) * 100) ?>%"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= $user['total_actions'] ?></span>
                                </td>
                                <td>
                                    <small><?= date('d.m.Y H:i', strtotime($user['last_activity'])) ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Crime Hotspots -->
    <?php if (!empty($stats['crime_hotspots'])): ?>
    <div class="chart-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="chart-title">Jinoyat "Issiq Nuqtalari"</h5>
            <span class="badge bg-danger">
                <i class="fas fa-fire me-1"></i>
                <?= count($stats['crime_hotspots']) ?> ta xavfli hudud
            </span>
        </div>
        
        <div class="row">
            <?php foreach ($stats['crime_hotspots'] as $hotspot): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-danger">
                            <i class="fas fa-fire me-2"></i>
                            <?= htmlspecialchars($hotspot['mahalla']) ?>
                        </h6>
                        <div class="mb-2">
                            <span class="badge bg-primary"><?= htmlspecialchars($hotspot['viloyat']) ?></span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($hotspot['tuman']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold fs-4"><?= $hotspot['crime_count'] ?></div>
                                <small class="text-muted">Jinoyatlar soni</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-danger" onclick="viewHotspotDetails('<?= $hotspot['mahalla'] ?>')">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Jinoyat turlari:</small>
                            <div class="small"><?= htmlspecialchars($hotspot['crime_types']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0"></script>
    
    <script>
        // Global chart instances
        let crimesByYearChart, crimesBySeverityChart, nizokByRegionChart;
        let crimesByHourChart, crimesByDayChart, userActivityChart;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize period buttons
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const period = this.dataset.period;
                    updateAllCharts(period);
                });
            });
            
            // Initialize date inputs
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            
            startDateInput.addEventListener('change', updateChartsByDate);
            endDateInput.addEventListener('change', updateChartsByDate);
            
            // Initialize charts
            initializeCharts();
            
            // Initialize heatmap
            generateHeatmap();
            
            // Theme toggle
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        });
        
        function initializeCharts() {
            // 1. Crimes by Year Chart
            const years = <?= json_encode(array_column($stats['crimes_by_year'], 'year')) ?>;
            const crimeData = <?= json_encode(array_column($stats['crimes_by_year'], 'total')) ?>;
            
            crimesByYearChart = new ApexCharts(document.querySelector("#crimesByYearChart"), {
                series: [{
                    name: 'Jinoyatlar soni',
                    data: crimeData
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                colors: ['#4361ee'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '60%'
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: years,
                    labels: { style: { colors: '#6c757d' } }
                },
                yaxis: {
                    title: { text: 'Jinoyatlar soni', style: { color: '#6c757d' } },
                    labels: { style: { colors: '#6c757d' } }
                },
                tooltip: {
                    theme: document.documentElement.getAttribute('data-bs-theme')
                }
            });
            
            crimesByYearChart.render();
            
            // 2. Crimes by Severity Chart
            const severityData = [
                <?= array_sum(array_column($stats['crimes_by_year'], 'ota_ogir')) ?>,
                <?= array_sum(array_column($stats['crimes_by_year'], 'ogir')) ?>,
                <?= array_sum(array_column($stats['crimes_by_year'], 'uncha_ogir')) ?>,
                <?= array_sum(array_column($stats['crimes_by_year'], 'ijtimoiy')) ?>
            ];
            
            crimesBySeverityChart = new ApexCharts(document.querySelector("#crimesBySeverityChart"), {
                series: severityData,
                labels: ['O\'ta og\'ir', 'Og\'ir', 'Uncha og\'ir emas', 'Ijtimoiy xavfsiz'],
                chart: {
                    type: 'donut',
                    height: 300
                },
                colors: ['#f72585', '#4361ee', '#4cc9f0', '#7209b7'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Jami',
                                    fontSize: '14px',
                                    fontWeight: 600
                                }
                            }
                        }
                    }
                },
                legend: { position: 'bottom' }
            });
            
            crimesBySeverityChart.render();
            
            // 3. Nizok by Region Chart
            const regions = <?= json_encode(array_column($stats['nizok_by_region'], 'viloyat')) ?>;
            const nizokData = <?= json_encode(array_column($stats['nizok_by_region'], 'total')) ?>;
            
            nizokByRegionChart = new ApexCharts(document.querySelector("#nizokByRegionChart"), {
                series: [{
                    name: 'Nizok oilalar',
                    data: nizokData
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false }
                },
                colors: ['#f72585'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        dataLabels: { position: 'center' }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) { return val },
                    offsetX: 0,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    }
                },
                xaxis: {
                    categories: regions,
                    labels: { style: { colors: '#6c757d' } }
                },
                yaxis: {
                    labels: { style: { colors: '#6c757d' } }
                }
            });
            
            nizokByRegionChart.render();
            
            // 4. Crimes by Hour Chart
            const hours = Array.from({length: 24}, (_, i) => i);
            const hourData = <?= json_encode(array_column($stats['crimes_by_hour'], 'count')) ?>;
            
            crimesByHourChart = new ApexCharts(document.querySelector("#crimesByHourChart"), {
                series: [{
                    name: 'Jinoyatlar',
                    data: hourData
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    toolbar: { show: false }
                },
                colors: ['#4361ee'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: hours.map(h => h.toString().padStart(2, '0') + ':00'),
                    labels: { style: { colors: '#6c757d' } }
                },
                yaxis: {
                    labels: { style: { colors: '#6c757d' } }
                },
                tooltip: {
                    x: { format: 'HH:mm' }
                }
            });
            
            crimesByHourChart.render();
            
            // 5. Crimes by Day Chart
            const days = <?= json_encode(array_column($stats['crimes_by_day'], 'day')) ?>;
            const dayData = <?= json_encode(array_column($stats['crimes_by_day'], 'count')) ?>;
            
            crimesByDayChart = new ApexCharts(document.querySelector("#crimesByDayChart"), {
                series: [{
                    name: 'Jinoyatlar',
                    data: dayData
                }],
                chart: {
                    type: 'line',
                    height: 250,
                    toolbar: { show: false }
                },
                colors: ['#f72585'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 5 },
                xaxis: {
                    categories: days,
                    labels: { style: { colors: '#6c757d' } }
                },
                yaxis: {
                    labels: { style: { colors: '#6c757d' } }
                }
            });
            
            crimesByDayChart.render();
            
            // 6. User Activity Chart
            const users = <?= json_encode(array_column($stats['user_activity'], 'username')) ?>;
            const activityData = <?= json_encode(array_column($stats['user_activity'], 'total_actions')) ?>;
            
            userActivityChart = new ApexCharts(document.querySelector("#userActivityChart"), {
                series: [{
                    name: 'Harakatlar soni',
                    data: activityData
                }],
                chart: {
                    type: 'radar',
                    height: 300,
                    toolbar: { show: false }
                },
                colors: ['#4cc9f0'],
                xaxis: {
                    categories: users
                },
                yaxis: {
                    min: 0,
                    labels: { style: { colors: '#6c757d' } }
                },
                markers: { size: 4 },
                tooltip: {
                    y: { formatter: function(val) { return val + ' ta harakat' } }
                }
            });
            
            userActivityChart.render();
        }
        
        function generateHeatmap() {
            const heatmapContainer = document.getElementById('activityHeatmap');
            
            // Generate mock data for 3 months
            const months = ['Dek', 'Yan', 'Fev'];
            const daysInWeek = ['Du', 'Se', 'Cho', 'Pa', 'Ju', 'Sha', 'Ya'];
            
            let html = '<div class="heatmap-grid">';
            
            // Add day labels
            html += '<div class="heatmap-day-label"></div>';
            daysInWeek.forEach(day => {
                html += `<div class="heatmap-day-label text-center small fw-bold">${day}</div>`;
            });
            
            // Generate heatmap cells
            for (let month = 0; month < 3; month++) {
                html += `<div class="heatmap-month-label fw-bold">${months[month]}</div>`;
                
                for (let day = 0; day < 7; day++) {
                    for (let week = 0; week < 4; week++) {
                        const activity = Math.floor(Math.random() * 30);
                        let color = '#ebedf0';
                        
                        if (activity > 20) color = '#216e39';
                        else if (activity > 10) color = '#30a14e';
                        else if (activity > 5) color = '#40c463';
                        else if (activity > 0) color = '#9be9a8';
                        
                        html += `<div class="heatmap-day" style="background-color: ${color};" 
                                 title="${activity} ta jinoyat"></div>`;
                    }
                }
            }
            
            html += '</div>';
            heatmapContainer.innerHTML = html;
        }
        
        function updateAllCharts(period) {
            // Show loading
            document.querySelectorAll('.chart-container').forEach(container => {
                container.style.opacity = '0.5';
            });
            
            // Fetch new data
            fetch(`/api/v1/statistics/${period}`)
                .then(response => response.json())
                .then(data => {
                    // Update charts with new data
                    updateChartsWithData(data);
                    
                    // Restore opacity
                    document.querySelectorAll('.chart-container').forEach(container => {
                        container.style.opacity = '1';
                    });
                })
                .catch(error => {
                    console.error('Error updating charts:', error);
                    alert('Ma\'lumotlarni yangilashda xatolik yuz berdi');
                });
        }
        
        function updateChartsByDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (!startDate || !endDate) return;
            
            fetch(`/api/v1/statistics/custom?start=${startDate}&end=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    updateChartsWithData(data);
                });
        }
        
        function updateChartsWithData(data) {
            // Update each chart with new data
            if (crimesByYearChart) {
                crimesByYearChart.updateSeries([{ data: data.crimesByYear }]);
                crimesByYearChart.updateOptions({ xaxis: { categories: data.years } });
            }
            
            if (nizokByRegionChart) {
                nizokByRegionChart.updateSeries([{ data: data.nizokByRegion }]);
                nizokByRegionChart.updateOptions({ xaxis: { categories: data.regions } });
            }
            
            // Update other charts similarly...
        }
        
        function filterChart(chartType, filter) {
            // Implement specific chart filtering
            console.log(`Filtering ${chartType} with ${filter}`);
            // In real application, this would fetch filtered data and update the chart
        }
        
        function exportStatistics(format) {
            const period = document.querySelector('.period-btn.active').dataset.period;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            const url = `/api/v1/statistics/export/${format}?period=${period}&start=${startDate}&end=${endDate}`;
            window.open(url, '_blank');
        }
        
        function printStatistics() {
            window.print();
        }
        
        function exportRegionData() {
            const regions = <?= json_encode(array_column($stats['nizok_by_region'], 'viloyat')) ?>;
            const data = <?= json_encode(array_column($stats['nizok_by_region'], 'total')) ?>;
            
            let csv = 'Viloyat,Nizok oilalar soni\n';
            regions.forEach((region, index) => {
                csv += `"${region}",${data[index]}\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `nizok-statistika-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
        
        function showAllMahallas() {
            // Show modal with all mahallas
            console.log('Show all mahallas');
            // In real application, this would open a modal with full table
        }
        
        function viewHotspotDetails(mahallaName) {
            alert(`${mahallaName} MFYsi haqida batafsil ma'lumot ochiladi`);
            // In real application, this would show detailed information
        }
        
        // Auto-refresh data every 5 minutes
        setInterval(() => {
            updateAllCharts(document.querySelector('.period-btn.active').dataset.period);
        }, 300000);
    </script>
</body>
</html>