<!DOCTYPE html>
<html lang="uz" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foydalanuvchilarni Boshqarish - MFY Tizimi</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        
        .user-avatar {
            width: 50px;
            height: 50px;
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
        
        .role-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .role-super-admin {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .role-admin {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
        }
        
        .role-operator {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .role-user {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .data-table {
            background: #16213e;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="dark"] .filter-card {
            background: #16213e;
        }
        
        .user-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        [data-bs-theme="dark"] .user-card {
            background: #1e3058;
            border-color: #2d3748;
        }
        
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }
        
        .online-indicator {
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            position: absolute;
            top: 5px;
            right: 5px;
            border: 2px solid white;
        }
        
        [data-bs-theme="dark"] .online-indicator {
            border-color: #1e3058;
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
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
                        <li class="breadcrumb-item active" aria-current="page">Foydalanuvchilar</li>
                    </ol>
                </nav>
                <h1 class="h2 fw-bold mb-0">Foydalanuvchilarni Boshqarish</h1>
                <p class="text-muted mb-0">Tizimdagi barcha foydalanuvchilarni ko'rish va boshqarish</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="btn-group" role="group">
                    <a href="/admin/users/create" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Yangi Foydalanuvchi
                    </a>
                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="exportUsers('excel')">
                            <i class="fas fa-file-excel me-2 text-success"></i>Excel ga export
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportUsers('pdf')">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>PDF ga export
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportUsers('csv')">
                            <i class="fas fa-file-csv me-2 text-primary"></i>CSV ga export
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="printUsers()">
                            <i class="fas fa-print me-2"></i>Chop etish
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="/admin/users" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Qidirish</label>
                    <input type="text" class="form-control" name="search" placeholder="Ism, familiya, username yoki email..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rol</label>
                    <select class="form-select" name="role">
                        <option value="">Barchasi</option>
                        <option value="super_admin" <?= ($_GET['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                        <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="operator" <?= ($_GET['role'] ?? '') === 'operator' ? 'selected' : '' ?>>Operator</option>
                        <option value="user" <?= ($_GET['role'] ?? '') === 'user' ? 'selected' : '' ?>>Foydalanuvchi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Holati</label>
                    <select class="form-select" name="status">
                        <option value="">Barchasi</option>
                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Faol</option>
                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Faol emas</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Kutishda</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Qidirish
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="/admin/users" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Filterni tozalash
                        </a>
                        <div class="text-muted small">
                            <?= count($users) ?> ta foydalanuvchi topildi
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Users Table (Desktop) -->
    <div class="data-table d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th>Foydalanuvchi</th>
                        <th>Rol</th>
                        <th>MFY</th>
                        <th>Qo'shilgan</th>
                        <th>Holati</th>
                        <th>Harakatlar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <?php if ($user['profile_picture'] !== 'default.png'): ?>
                                    <img src="/uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>" 
                                         alt="<?= htmlspecialchars($user['username']) ?>" 
                                         class="user-avatar">
                                    <?php else: ?>
                                    <div class="user-avatar bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-primary fs-4"></i>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($user['is_online']): ?>
                                    <span class="online-indicator"></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </div>
                                    <div class="small">
                                        <i class="fas fa-envelope me-1"></i>
                                        <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $roleClass = 'role-user';
                            if ($user['role'] === 'super_admin') $roleClass = 'role-super-admin';
                            elseif ($user['role'] === 'admin') $roleClass = 'role-admin';
                            elseif ($user['role'] === 'operator') $roleClass = 'role-operator';
                            ?>
                            <span class="role-badge <?= $roleClass ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['mahalla_nomi']): ?>
                            <div class="fw-bold"><?= htmlspecialchars($user['mahalla_nomi']) ?></div>
                            <div class="text-muted small">MFY</div>
                            <?php else: ?>
                            <span class="text-muted">Tayinlanmagan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold"><?= date('d.m.Y', strtotime($user['created_at'])) ?></div>
                            <div class="text-muted small"><?= date('H:i', strtotime($user['created_at'])) ?></div>
                        </td>
                        <td>
                            <?php
                            $statusClass = $user['is_online'] ? 'status-active' : 'status-inactive';
                            $statusText = $user['is_online'] ? 'Onlayn' : 'Offlayn';
                            ?>
                            <span class="status-badge <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-primary action-btn" 
                                   title="Tahrirlash" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/users/<?= $user['id'] ?>/view" class="btn btn-sm btn-info action-btn" 
                                   title="Ko'rish" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button type="button" class="btn btn-sm btn-danger action-btn delete-user" 
                                        data-user-id="<?= $user['id'] ?>" 
                                        data-user-name="<?= htmlspecialchars($user['username']) ?>"
                                        title="O'chirish" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Users Cards (Mobile) -->
    <div class="row d-lg-none g-3">
        <?php foreach ($users as $user): ?>
        <div class="col-12">
            <div class="user-card">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="position-relative">
                            <?php if ($user['profile_picture'] !== 'default.png'): ?>
                            <img src="/uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>" 
                                 alt="<?= htmlspecialchars($user['username']) ?>" 
                                 class="user-avatar">
                            <?php else: ?>
                            <div class="user-avatar bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-primary fs-4"></i>
                            </div>
                            <?php endif; ?>
                            <?php if ($user['is_online']): ?>
                            <span class="online-indicator"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                            </div>
                            <div class="text-end">
                                <?php
                                $roleClass = 'role-user';
                                if ($user['role'] === 'super_admin') $roleClass = 'role-super-admin';
                                elseif ($user['role'] === 'admin') $roleClass = 'role-admin';
                                elseif ($user['role'] === 'operator') $roleClass = 'role-operator';
                                ?>
                                <span class="role-badge <?= $roleClass ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <div class="small">
                                <i class="fas fa-envelope me-1"></i>
                                <?= htmlspecialchars($user['email']) ?>
                            </div>
                            <?php if ($user['mahalla_nomi']): ?>
                            <div class="small mt-1">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?= htmlspecialchars($user['mahalla_nomi']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d.m.Y', strtotime($user['created_at'])) ?>
                                </small>
                            </div>
                            <div class="action-buttons">
                                <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-primary action-btn" 
                                   title="Tahrirlash">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/users/<?= $user['id'] ?>/view" class="btn btn-sm btn-info action-btn" 
                                   title="Ko'rish">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button type="button" class="btn btn-sm btn-danger action-btn delete-user" 
                                        data-user-id="<?= $user['id'] ?>" 
                                        data-user-name="<?= htmlspecialchars($user['username']) ?>"
                                        title="O'chirish">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Foydalanuvchini O'chirish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage"></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Bu amalni ortga qaytarib bo'lmaydi. Foydalanuvchining barcha ma'lumotlari o'chib ketadi.
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete">
                        <label class="form-check-label" for="confirmDelete">
                            Men bu amalning ogibatlarini tushunib yetdim
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>O'chirish
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Barchasi']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/uz.json'
                },
                order: [[3, 'desc']], // Sort by creation date
                responsive: true,
                dom: '<"top"f>rt<"bottom"lp><"clear">'
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize Select2
            $('select').select2({
                minimumResultsForSearch: 10
            });
            
            // Delete user functionality
            let deleteUserId = null;
            
            document.querySelectorAll('.delete-user').forEach(button => {
                button.addEventListener('click', function() {
                    deleteUserId = this.dataset.userId;
                    const userName = this.dataset.userName;
                    
                    document.getElementById('deleteMessage').textContent = 
                        `"${userName}" foydalanuvchisini o'chirishni istaysizmi?`;
                    
                    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    modal.show();
                });
            });
            
            // Confirm delete checkbox
            document.getElementById('confirmDelete').addEventListener('change', function() {
                document.getElementById('confirmDeleteBtn').disabled = !this.checked;
            });
            
            // Confirm delete button
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (!deleteUserId) return;
                
                fetch(`/admin/users/${deleteUserId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?? '' ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Xatolik yuz berdi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Xatolik yuz berdi');
                });
            });
            
            // Export functions
            window.exportUsers = function(format) {
                const filters = {
                    search: '<?= $_GET['search'] ?? '' ?>',
                    role: '<?= $_GET['role'] ?? '' ?>',
                    status: '<?= $_GET['status'] ?? '' ?>'
                };
                
                const queryString = new URLSearchParams(filters).toString();
                
                window.location.href = `/api/v1/export/users?format=${format}&${queryString}`;
            };
            
            window.printUsers = function() {
                window.print();
            };
            
            // Real-time status updates
            function updateOnlineStatus() {
                fetch('/api/v1/users/online-status')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(user => {
                            const indicator = document.querySelector(`[data-user-id="${user.id}"] .online-indicator`);
                            const statusBadge = document.querySelector(`[data-user-id="${user.id}"] .status-badge`);
                            
                            if (indicator) {
                                if (user.is_online) {
                                    indicator.style.display = 'block';
                                    if (statusBadge) {
                                        statusBadge.className = 'status-badge status-active';
                                        statusBadge.textContent = 'Onlayn';
                                    }
                                } else {
                                    indicator.style.display = 'none';
                                    if (statusBadge) {
                                        statusBadge.className = 'status-badge status-inactive';
                                        statusBadge.textContent = 'Offlayn';
                                    }
                                }
                            }
                        });
                    });
            }
            
            // Update every 30 seconds
            setInterval(updateOnlineStatus, 30000);
            
            // Theme toggle
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            
            // Quick actions
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '/admin/users/create';
                }
                
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                }
            });
        });
    </script>
</body>
</html>