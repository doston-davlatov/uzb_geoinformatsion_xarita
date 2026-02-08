                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Faol Sessiyalar</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Qurilma/Agent</th>
                                                <th>IP Manzil</th>
                                                <th>Kirish vaqti</th>
                                                <th>Oxirgi Faollik</th>
                                                <th>Harakat</th>
                                            </tr>
                                        </thead>
                                        <tbody id="activeSessions">
                                            <!-- Sessions will be loaded via JavaScript -->
                                            <tr>
                                                <td colspan="5" class="text-center py-3">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                        <span class="visually-hidden">Yuklanmoqda...</span>
                                                    </div>
                                                    Sessiyalar yuklanmoqda...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="logoutAllSessions()">
                                        <i class="fas fa-sign-out-alt me-1"></i>Barcha Sessiyalarni Tugatish
                                    </button>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="saveSettings('security')">
                                    <i class="fas fa-save me-2"></i>Saqlash
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Email Settings -->
                    <div class="tab-pane fade" id="email">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-envelope me-2"></i>Email Sozlamalari
                            </h2>
                            <p class="section-subtitle">Email xizmatlari va shablonlarini sozlang</p>
                        </div>
                        
                        <form method="POST" id="emailForm">
                            <input type="hidden" name="action" value="email">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">SMTP Server Sozlamalari</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_host" class="form-label">SMTP Server *</label>
                                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                                   value="<?= htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com') ?>" required>
                                            <div class="form-text">Masalan: smtp.gmail.com</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_port" class="form-label">SMTP Port *</label>
                                            <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                                   value="<?= $settings['smtp_port'] ?? 587 ?>" min="1" max="65535" required>
                                            <div class="form-text">Odatda: 465 (SSL) yoki 587 (TLS)</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_username" class="form-label">SMTP Foydalanuvchi *</label>
                                            <input type="text" class="form-control" id="smtp_username" name="smtp_username" 
                                                   value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_password" class="form-label">SMTP Parol *</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                                       value="<?= htmlspecialchars($settings['smtp_password'] ?? '') ?>">
                                                <button class="btn btn-outline-secondary" type="button" id="toggleSmtpPassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_encryption" class="form-label">Shifrlash</label>
                                            <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                                <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' ?>>TLS</option>
                                                <option value="ssl" <?= ($settings['smtp_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' ?>>SSL</option>
                                                <option value="" <?= empty($settings['smtp_encryption'] ?? '') ? 'selected' : '' ?>>Shifrlanmagan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_from_email" class="form-label">Yuboruvchi Email *</label>
                                            <input type="email" class="form-control" id="smtp_from_email" name="smtp_from_email" 
                                                   value="<?= htmlspecialchars($settings['smtp_from_email'] ?? $settings['site_email'] ?? '') ?>" required>
                                            <div class="form-text">Xabarlar qaysi manzildan kelayotgandek ko'rinadi</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_from_name" class="form-label">Yuboruvchi Nomi *</label>
                                            <input type="text" class="form-control" id="smtp_from_name" name="smtp_from_name" 
                                                   value="<?= htmlspecialchars($settings['smtp_from_name'] ?? $settings['site_name'] ?? 'MFY Tizimi') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_timeout" class="form-label">Kutish Vaqti (soniya)</label>
                                            <input type="number" class="form-control" id="smtp_timeout" name="smtp_timeout" 
                                                   value="<?= $settings['smtp_timeout'] ?? 30 ?>" min="5" max="120">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="button" class="btn btn-outline-primary" onclick="testEmailConnection()">
                                        <i class="fas fa-vial me-2"></i>Test Xabarini Yuborish
                                    </button>
                                    <div class="form-text">Sozlamalarni saqlamasdan SMTP ulanishini sinab ko'ring</div>
                                    <div id="emailTestResult" class="test-result mt-2"></div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Email Shablonlari</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_header" class="form-label">Email Sarlavhasi (HTML)</label>
                                            <textarea class="form-control" id="email_header" name="email_header" rows="3"><?= htmlspecialchars($settings['email_header'] ?? '<div style="background: #4361ee; color: white; padding: 20px; text-align: center;"><h1>MFY Tizimi</h1></div>') ?></textarea>
                                            <div class="form-text">Barcha xabarlar uchun sarlavha</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_footer" class="form-label">Email Footer (HTML)</label>
                                            <textarea class="form-control" id="email_footer" name="email_footer" rows="3"><?= htmlspecialchars($settings['email_footer'] ?? '<div style="background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; border-top: 1px solid #dee2e6;"><p>© 2024 MFY Tizimi. Barcha huquqlar himoyalangan.</p></div>') ?></textarea>
                                            <div class="form-text">Barcha xabarlar uchun footer</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_html_enabled" 
                                               name="email_html_enabled" <?= ($settings['email_html_enabled'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email_html_enabled">
                                            HTML xabarlarni yoqish
                                        </label>
                                    </div>
                                    <div class="form-text">HTML formatdagi xabarlarni yuborish</div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Xabar Turlari</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_new_user" 
                                                   name="email_new_user" <?= ($settings['email_new_user'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="email_new_user">
                                                Yangi foydalanuvchi ro'yxatdan o'tganda
                                            </label>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_password_reset" 
                                                   name="email_password_reset" <?= ($settings['email_password_reset'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="email_password_reset">
                                                Parolni tiklash so'rovi
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" 
                                                   name="email_notifications" <?= ($settings['email_notifications'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="email_notifications">
                                                Tizim bildirishnomalari
                                            </label>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_backup_reports" 
                                                   name="email_backup_reports" <?= ($settings['email_backup_reports'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="email_backup_reports">
                                                Backup hisobotlari
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="saveSettings('email')">
                                    <i class="fas fa-save me-2"></i>Saqlash
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Appearance Settings -->
                    <div class="tab-pane fade" id="appearance">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-palette me-2"></i>Tashqi Ko'rinish
                            </h2>
                            <p class="section-subtitle">Tizimning tashqi ko'rinishi va ranglarini sozlang</p>
                        </div>
                        
                        <form method="POST" id="appearanceForm">
                            <input type="hidden" name="action" value="appearance">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Asosiy Mavzu</h5>
                                
                                <div class="theme-preview">
                                    <div class="theme-option theme-light <?= ($settings['theme_mode'] ?? 'light') == 'light' ? 'active' : '' ?>" 
                                         data-theme="light" onclick="selectTheme('light')">
                                        <div class="theme-label">Yorqin</div>
                                    </div>
                                    <div class="theme-option theme-dark <?= ($settings['theme_mode'] ?? 'light') == 'dark' ? 'active' : '' ?>" 
                                         data-theme="dark" onclick="selectTheme('dark')">
                                        <div class="theme-label">Qorong'i</div>
                                    </div>
                                    <div class="theme-option theme-blue <?= ($settings['theme_mode'] ?? 'light') == 'blue' ? 'active' : '' ?>" 
                                         data-theme="blue" onclick="selectTheme('blue')">
                                        <div class="theme-label">Moviy</div>
                                    </div>
                                    <div class="theme-option theme-green <?= ($settings['theme_mode'] ?? 'light') == 'green' ? 'active' : '' ?>" 
                                         data-theme="green" onclick="selectTheme('green')">
                                        <div class="theme-label">Yashil</div>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="theme_mode" name="theme_mode" value="<?= $settings['theme_mode'] ?? 'light' ?>">
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="dark_mode_toggle" 
                                                       name="dark_mode_toggle" <?= ($settings['dark_mode_toggle'] ?? 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="dark_mode_toggle">
                                                    Qorong'i rejimga o'tish tugmasini ko'rsatish
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="auto_dark_mode" 
                                                       name="auto_dark_mode" <?= ($settings['auto_dark_mode'] ?? 0) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="auto_dark_mode">
                                                    Tizim mavzusiga moslashuvchi rejim
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Rang Sozlamalari</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primary_color" class="form-label">Asosiy Rang</label>
                                            <input type="color" class="form-control form-control-color" id="primary_color" 
                                                   name="primary_color" value="<?= $settings['primary_color'] ?? '#4361ee' ?>" 
                                                   title="Asosiy rangni tanlang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secondary_color" class="form-label">Ikkinchi Rang</label>
                                            <input type="color" class="form-control form-control-color" id="secondary_color" 
                                                   name="secondary_color" value="<?= $settings['secondary_color'] ?? '#3a0ca3' ?>" 
                                                   title="Ikkinchi rangni tanlang">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="success_color" class="form-label">Muvaffaqiyat Rangi</label>
                                            <input type="color" class="form-control form-control-color" id="success_color" 
                                                   name="success_color" value="<?= $settings['success_color'] ?? '#4cc9f0' ?>" 
                                                   title="Muvaffaqiyat rangi">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="danger_color" class="form-label">Xavf Rangi</label>
                                            <input type="color" class="form-control form-control-color" id="danger_color" 
                                                   name="danger_color" value="<?= $settings['danger_color'] ?? '#f72585' ?>" 
                                                   title="Xavf rangi">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warning_color" class="form-label">Ogohlantirish Rangi</label>
                                            <input type="color" class="form-control form-control-color" id="warning_color" 
                                                   name="warning_color" value="<?= $settings['warning_color'] ?? '#f8961e' ?>" 
                                                   title="Ogohlantirish rangi">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="info_color" class="form-label">Ma'lumot Rangi</label>
                                            <input type="color" class="form-control form-control-color" id="info_color" 
                                                   name="info_color" value="<?= $settings['info_color'] ?? '#7209b7' ?>" 
                                                   title="Ma'lumot rangi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Logo va Favicon</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_logo" class="form-label">Sayt Logosi</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="site_logo" name="site_logo" 
                                                       value="<?= htmlspecialchars($settings['site_logo'] ?? '') ?>" 
                                                       placeholder="Logo fayl yo'li">
                                                <button class="btn btn-outline-secondary" type="button" onclick="openFileBrowser('site_logo')">
                                                    <i class="fas fa-folder-open"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">PNG, JPG yoki SVG formatida (masalan: /assets/img/logo.png)</div>
                                        </div>
                                        
                                        <div class="settings-preview">
                                            <div class="preview-logo" id="logoPreview">
                                                <?php if(!empty($settings['site_logo'])): ?>
                                                    <img src="<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" style="max-width: 100%; max-height: 100%;">
                                                <?php else: ?>
                                                    <i class="fas fa-city"></i>
                                                <?php endif; ?>
                                            </div>
                                            <h5 id="previewSiteName"><?= htmlspecialchars($settings['site_name'] ?? 'MFY Tizimi') ?></h5>
                                            <p class="text-muted" id="previewSiteTagline"><?= htmlspecialchars($settings['site_tagline'] ?? 'Mahalla Fuqarolar Yig\'ini') ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_favicon" class="form-label">Favicon</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="site_favicon" name="site_favicon" 
                                                       value="<?= htmlspecialchars($settings['site_favicon'] ?? '') ?>" 
                                                       placeholder="Favicon fayl yo'li">
                                                <button class="btn btn-outline-secondary" type="button" onclick="openFileBrowser('site_favicon')">
                                                    <i class="fas fa-folder-open"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">ICO, PNG yani SVG formatida (16x16, 32x32)</div>
                                        </div>
                                        
                                        <div class="settings-preview">
                                            <div class="preview-logo mb-3">
                                                <?php if(!empty($settings['site_favicon'])): ?>
                                                    <img src="<?= htmlspecialchars($settings['site_favicon']) ?>" alt="Favicon" style="max-width: 100%; max-height: 100%;">
                                                <?php else: ?>
                                                    <i class="fas fa-flag"></i>
                                                <?php endif; ?>
                                            </div>
                                            <h6>Favicon brauzerda ko'rinadi:</h6>
                                            <div class="d-flex justify-content-center gap-3 mt-3">
                                                <div class="text-center">
                                                    <div class="mb-2">
                                                        <div style="width: 32px; height: 32px; background: #e9ecef; border-radius: 5px; margin: 0 auto;"></div>
                                                    </div>
                                                    <small>Browser Tab</small>
                                                </div>
                                                <div class="text-center">
                                                    <div class="mb-2">
                                                        <div style="width: 16px; height: 16px; background: #e9ecef; border-radius: 3px; margin: 0 auto;"></div>
                                                    </div>
                                                    <small>Favorites</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Shriftlar va Typography</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="font_family" class="form-label">Asosiy Shrift</label>
                                            <select class="form-select" id="font_family" name="font_family">
                                                <option value="Inter, -apple-system, system-ui" <?= ($settings['font_family'] ?? 'Inter, -apple-system, system-ui') == 'Inter, -apple-system, system-ui' ? 'selected' : '' ?>>Inter (Zamonaviy)</option>
                                                <option value="'Segoe UI', Tahoma, Geneva" <?= ($settings['font_family'] ?? '') == "'Segoe UI', Tahoma, Geneva" ? 'selected' : '' ?>>Segoe UI (Windows)</option>
                                                <option value="'Roboto', sans-serif" <?= ($settings['font_family'] ?? '') == "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto (Google)</option>
                                                <option value="Arial, Helvetica, sans-serif" <?= ($settings['font_family'] ?? '') == 'Arial, Helvetica, sans-serif' ? 'selected' : '' ?>>Arial (Standart)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="font_size_base" class="form-label">Asosiy Shrift O'lchami</label>
                                            <select class="form-select" id="font_size_base" name="font_size_base">
                                                <option value="14px" <?= ($settings['font_size_base'] ?? '14px') == '14px' ? 'selected' : '' ?>>Kichik (14px)</option>
                                                <option value="16px" <?= ($settings['font_size_base'] ?? '14px') == '16px' ? 'selected' : '' ?>>Standart (16px)</option>
                                                <option value="18px" <?= ($settings['font_size_base'] ?? '14px') == '18px' ? 'selected' : '' ?>>Katta (18px)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="font_smoothing" 
                                               name="font_smoothing" <?= ($settings['font_smoothing'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="font_smoothing">
                                            Shrift silliqligini yoqish
                                        </label>
                                    </div>
                                    <div class="form-text">Shriftlarni tekis va chiroyli ko'rinishi</div>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="saveSettings('appearance')">
                                    <i class="fas fa-save me-2"></i>Saqlash
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Backup & Storage -->
                    <div class="tab-pane fade" id="backup">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-database me-2"></i>Backup & Saqlash
                            </h2>
                            <p class="form-text">Ma'lumotlaringizni himoyalang va boshqaring</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Avtomatik Backup</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="backup_schedule" class="form-label">Backup Jadvali</label>
                                                <select class="form-select" id="backup_schedule" name="backup_schedule">
                                                    <option value="daily" <?= ($settings['backup_schedule'] ?? 'daily') == 'daily' ? 'selected' : '' ?>>Har kuni</option>
                                                    <option value="weekly" <?= ($settings['backup_schedule'] ?? 'daily') == 'weekly' ? 'selected' : '' ?>>Haftada bir</option>
                                                    <option value="monthly" <?= ($settings['backup_schedule'] ?? 'daily') == 'monthly' ? 'selected' : '' ?>>Oyiga bir</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="backup_time" class="form-label">Backup Vaqti</label>
                                                <input type="time" class="form-control" id="backup_time" name="backup_time" 
                                                       value="<?= $settings['backup_time'] ?? '02:00' ?>">
                                                <div class="form-text">Server yuki kam bo'lgan vaqtni tanlang</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="backup_compress" 
                                                   name="backup_compress" <?= ($settings['backup_compress'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="backup_compress">
                                                Backupni siqish (ZIP)
                                            </label>
                                        </div>
                                        <div class="form-text">Backup fayl hajmini kamaytiradi</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="backup_retention" class="form-label">Saqlash Muddatlari</label>
                                        <input type="number" class="form-control" id="backup_retention" name="backup_retention" 
                                               value="<?= $settings['backup_retention'] ?? 30 ?>" min="1" max="365">
                                        <div class="form-text">Backup fayllari necha kun saqlansin</div>
                                    </div>
                                    
                                    <div class="d-grid gap-2 d-md-flex mt-3">
                                        <button type="button" class="btn btn-primary" onclick="createBackup()">
                                            <i class="fas fa-database me-2"></i>Zahira Nusxa Yaratish
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="restoreBackup()">
                                            <i class="fas fa-history me-2"></i>Backup'dan Tiklash
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Backup Arxivi</h5>
                                    <div id="backupList">
                                        <div class="backup-card">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="fw-bold mb-1">full_backup_2024_01_15_020000.zip</h6>
                                                    <small class="text-muted">2024-01-15 02:00 | 45.2 MB</small>
                                                    <div class="backup-status">
                                                        <span class="status-dot status-success"></span>
                                                        <small>Muvaffaqiyatli</small>
                                                    </div>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary" title="Yuklab olish">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" title="Tiklash">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" title="O'chirish">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Disk Foydalanuvi</h5>
                                    
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 35%"></div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%"></div>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 15%"></div>
                                    </div>
                                    
                                    <div class="system-info-grid">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-database"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Ma'lumotlar Bazasi</div>
                                                <div class="info-value">15.2 MB</div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-file-archive"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Backup Fayllari</div>
                                                <div class="info-value">245.8 MB</div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-images"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Media Fayllar</div>
                                                <div class="info-value">89.3 MB</div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-hdd"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Bo'sh Joy</div>
                                                <div class="info-value">5.2 GB</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Tezkor Harakatlar</h5>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="cleanupTempFiles()">
                                            <i class="fas fa-broom me-2"></i>Vaqtinchalik Fayllarni Tozalash
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" onclick="optimizeDatabase()">
                                            <i class="fas fa-tachometer-alt me-2"></i>Ma'lumotlar Bazasini Optimallashtirish
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="checkDiskSpace()">
                                            <i class="fas fa-hdd me-2"></i>Disk Bo'shlig'ini Tekshirish
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Information -->
                    <div class="tab-pane fade" id="system">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-server me-2"></i>Tizim Ma'lumotlari
                            </h2>
                            <p class="section-subtitle">Tizim texnik holati va resurslari</p>
                        </div>
                        
                        <div class="form-card">
                            <h5 class="fw-bold mb-3">Tizim Spetsifikatsiyalari</h5>
                            
                            <div class="system-info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Tizim Versiyasi</div>
                                        <div class="info-value">v2.1.0</div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Qurilish Sanasi</div>
                                        <div class="info-value">2024-01-15</div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fab fa-php"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">PHP Versiyasi</div>
                                        <div class="info-value"><?= phpversion() ?></div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">MySQL Versiyasi</div>
                                        <div class="info-value"><?= $db_version ?? '8.0+' ?></div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Server Dasturi</div>
                                        <div class="info-value"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Noma\'lum' ?></div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Operatsion Tizim</div>
                                        <div class="info-value"><?= php_uname('s') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Server Resurslari</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">CPU Foydalanishi</label>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-info" style="width: 45%"></div>
                                        </div>
                                        <small class="text-muted">45% foydalanilmoqda</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Xotira Foydalanishi</label>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-success" style="width: 68%"></div>
                                        </div>
                                        <small class="text-muted">256 MB / 512 MB</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Disk Foydalanishi</label>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-warning" style="width: 75%"></div>
                                        </div>
                                        <small class="text-muted">7.5 GB / 10 GB</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-card">
                                    <h5 class="fw-bold mb-3">Tizim Holati</h5>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium">Ma'lumotlar Bazasi</span>
                                            <span class="badge bg-success">Faol</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium">Email Server</span>
                                            <span class="badge bg-warning">Ogohlantirish</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium">Backup Service</span>
                                            <span class="badge bg-success">Faol</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium">Cache Service</span>
                                            <span class="badge bg-danger">Nosoz</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-medium">API Gateway</span>
                                            <span class="badge bg-success">Faol</span>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="runSystemDiagnostics()">
                                        <i class="fas fa-stethoscope me-2"></i>Tizim Diagnostikasi
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-card">
                            <h5 class="fw-bold mb-3">Tizim Loglari</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Vaqt</th>
                                            <th>Turi</th>
                                            <th>Xabar</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2024-01-15 10:30:15</td>
                                            <td><span class="badge bg-info">INFO</span></td>
                                            <td>Foydalanuvchi tizimga kirdi: admin</td>
                                            <td>192.168.1.100</td>
                                        </tr>
                                        <tr>
                                            <td>2024-01-15 09:45:22</td>
                                            <td><span class="badge bg-warning">WARNING</span></td>
                                            <td>Noto'g'ri kirish urinishi</td>
                                            <td>192.168.1.150</td>
                                        </tr>
                                        <tr>
                                            <td>2024-01-15 08:15:10</td>
                                            <td><span class="badge bg-success">SUCCESS</span></td>
                                            <td>Backup muvaffaqiyatli yakunlandi</td>
                                            <td>127.0.0.1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download me-1"></i>Loglarni Yuklab Olish
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- API Settings -->
                    <div class="tab-pane fade" id="api">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-code me-2"></i>API Sozlamalari
                            </h2>
                            <p class="section-subtitle">API interfeysi va autentifikatsiya sozlamalari</p>
                        </div>
                        
                        <form method="POST" id="apiForm">
                            <input type="hidden" name="action" value="api">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">Asosiy API Sozlamalari</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="api_enabled" 
                                                       name="api_enabled" <?= ($settings['api_enabled'] ?? 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="api_enabled">
                                                    API ni yoqish
                                                </label>
                                            </div>
                                            <div class="form-text">REST API interfeysini faollashtirish</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="api_rate_limit" class="form-label">So'rovlar Chegarasi (daqiqada)</label>
                                            <input type="number" class="form-control" id="api_rate_limit" name="api_rate_limit" 
                                                   value="<?= $settings['api_rate_limit'] ?? 60 ?>" min="10" max="1000">
                                            <div class="form-text">Har bir kalit uchun maksimal so'rovlar soni</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="api_docs_enabled" 
                                                       name="api_docs_enabled" <?= ($settings['api_docs_enabled'] ?? 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="api_docs_enabled">
                                                    API hujjatlari
                                                </label>
                                            </div>
                                            <div class="form-text">Swagger/OpenAPI hujjatlarini ko'rsatish</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="api_token_expiry" class="form-label">Token Amal Qilish Muddati (kun)</label>
                                            <input type="number" class="form-control" id="api_token_expiry" name="api_token_expiry" 
                                                   value="<?= $settings['api_token_expiry'] ?? 30 ?>" min="1" max="365">
                                            <div class="form-text">API tokenlari qancha vaqt amal qiladi</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">API Kalitlari</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kalit</th>
                                                <th>Yaratilgan</th>
                                                <th>Amal Qilish</th>
                                                <th>So'rovlar</th>
                                                <th>Harakatlar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <code class="api-key">sk_live_*********</code>
                                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyApiKey(this)">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td>2024-01-10</td>
                                                <td><span class="badge bg-success">2024-02-10</span></td>
                                                <td>1,245</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary" onclick="generateApiKey()">
                                        <i class="fas fa-plus me-2"></i>Yangi API Kaliti Yaratish
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h5 class="fw-bold mb-3">API Endpoint Test</h5>
                                
                                <div class="form-group">
                                    <label for="api_endpoint" class="form-label">Endpoint URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">/api/v1/</span>
                                        <input type="text" class="form-control" id="api_endpoint" placeholder="users" value="system/status">
                                        <button class="btn btn-outline-primary" type="button" onclick="testApiEndpoint()">
                                            <i class="fas fa-vial me-1"></i> Test
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Response</label>
                                    <div class="border rounded p-3 bg-light" style="min-height: 150px; max-height: 300px; overflow: auto;" id="apiResponse">
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-code fa-2x mb-3"></i>
                                            <p>API javobi bu yerda ko'rinadi</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-success" onclick="setApiEndpoint('system/status')">
                                        System Status
                                    </button>
                                    <button class="btn btn-outline-info" onclick="setApiEndpoint('users')">
                                        Users
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="setApiEndpoint('settings')">
                                        Settings
                                    </button>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="saveSettings('api')">
                                    <i class="fas fa-save me-2"></i>Saqlash
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Maintenance Settings -->
                    <div class="tab-pane fade" id="maintenance">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-tools me-2"></i>Xizmat Ko'rsatish
                            </h2>
                            <p class="section-subtitle">Tizimni texnik xizmat ko'rsatish va boshqarish</p>
                        </div>
                        
                        <div class="form-card">
                            <h5 class="fw-bold mb-3">Texnik Xizmat Rejimi</h5>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Ogohlantirish:</strong> Texnik xizmat rejimi yoqilganda, sayt oddiy foydalanuvchilar uchun yopiladi. Faqat adminlar tizimga kira oladi.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="maintenance_mode_main" 
                                                   name="maintenance_mode" <?= ($settings['maintenance_mode'] ?? 0) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="maintenance_mode_main">
                                                Texnik xizmat rejimini yoqish
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mt-3">
                                        <label for="maintenance_message" class="form-label">Xabar</label>
                                        <textarea class="form-control" id="maintenance_message" name="maintenance_message" 
                                                  rows="3"><?= htmlspecialchars($settings['maintenance_message'] ?? 'Texnik ishlar olib borilmoqda. Iltimos, keyinroq urinib ko\'ring.') ?></textarea>
                                        <div class="form-text">Foydalanuvchilar ko'radigan xabar</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="maintenance_schedule" class="form-label">Rejalashtirilgan Vaqt</label>
                                        <div class="input-group">
                                            <input type="datetime-local" class="form-control" id="maintenance_schedule" 
                                                   name="maintenance_schedule" value="<?= $settings['maintenance_schedule'] ?? '' ?>">
                                        </div>
                                        <div class="form-text">Texnik ishlarni boshlash vaqti</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="maintenance_duration" class="form-label">Davomiylik (soat)</label>
                                        <input type="number" class="form-control" id="maintenance_duration" 
                                               name="maintenance_duration" value="<?= $settings['maintenance_duration'] ?? 2 ?>" 
                                               min="0.5" max="24" step="0.5">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex mt-3">
                                <button type="button" class="btn btn-warning" onclick="enableMaintenanceMode()">
                                    <i class="fas fa-tools me-2"></i>Texnik Xizmat Rejimini Yoqish
                                </button>
                                <button type="button" class="btn btn-success" onclick="disableMaintenanceMode()">
                                    <i class="fas fa-play me-2"></i>Texnik Xizmat Rejimini O'chirish
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-card">
                            <h5 class="fw-bold mb-3">Cache Boshqaruvi</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cache_driver" class="form-label">Cache Drayveri</label>
                                        <select class="form-select" id="cache_driver" name="cache_driver">
                                            <option value="file" <?= ($settings['cache_driver'] ?? 'file') == 'file' ? 'selected' : '' ?>>Fayl Tizimi</option>
                                            <option value="redis" <?= ($settings['cache_driver'] ?? 'file') == 'redis' ? 'selected' : '' ?>>Redis</option>
                                            <option value="memcached" <?= ($settings['cache_driver'] ?? 'file') == 'memcached' ? 'selected' : '' ?>>Memcached</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="cache_ttl" class="form-label">Cache Muddati (soniya)</label>
                                        <input type="number" class="form-control" id="cache_ttl" name="cache_ttl" 
                                               value="<?= $settings['cache_ttl'] ?? 3600 ?>" min="60" max="86400">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Cache Statistikasi</label>
                                        <div class="system-info-grid">
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-hdd"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">Cache Fayllari</div>
                                                    <div class="info-value">124</div>
                                                </div>
                                            </div>
                                            
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-memory"></i>
                                                </div>
                                                <div class="info-content">
                                                    <div class="info-label">Cache Hajmi</div>
                                                    <div class="info-value">5.2 MB</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2 mt-3">
                                        <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                                            <i class="fas fa-broom me-2"></i>Cache'ni Tozalash
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-card">
                            <h5 class="fw-bold mb-3">Tizimni Optimallashtirish</h5>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="optimizeDatabase()">
                                        <i class="fas fa-database me-2"></i>DB Optimallashtirish
                                    </button>
                                    <small class="text-muted">Ma'lumotlar bazasi jadvallarini optimallashtirish</small>
                                </div>
                                
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-success w-100 mb-2" onclick="cleanupLogs()">
                                        <i class="fas fa-trash-alt me-2"></i>Loglarni Tozalash
                                    </button>
                                    <small class="text-muted">Eski log fayllarini o'chirish</small>
                                </div>
                                
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-info w-100 mb-2" onclick="updateSystem()">
                                        <i class="fas fa-sync-alt me-2"></i>Tizimni Yangilash
                                    </button>
                                    <small class="text-muted">Tizim versiyasini yangilash</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="danger-zone">
                            <h5 class="danger-title">
                                <i class="fas fa-radiation-alt me-2"></i>Xavfli Amallar
                            </h5>
                            <p class="danger-description">
                                Quyidagi amallar tizim ma'lumotlariga zarar yetkazishi mumkin. Faqat zarurat bo'lganda bajaring.
                            </p>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-danger w-100 mb-2" onclick="resetSystem()">
                                        <i class="fas fa-redo me-2"></i>Tizimni Qayta O'rnatish
                                    </button>
                                </div>
                                
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-danger w-100 mb-2" onclick="purgeAllData()">
                                        <i class="fas fa-fire me-2"></i>Barcha Ma'lumotlarni O'chirish
                                    </button>
                                </div>
                                
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-danger w-100 mb-2" onclick="reinstallSystem()">
                                        <i class="fas fa-download me-2"></i>Tizimni Qayta O'rnatish
                                    </button>
                                </div>
                            </div>
                            
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Diqqat!</strong> Bu amallarni bajargandan so'ng, ma'lumotlarni qaytarib bo'lmaydi. Oldin backup oling!
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"></script>

<script>
    // Theme management
    function selectTheme(theme) {
        const themeOptions = document.querySelectorAll('.theme-option');
        themeOptions.forEach(option => option.classList.remove('active'));
        
        const selectedTheme = document.querySelector(`.theme-option[data-theme="${theme}"]`);
        selectedTheme.classList.add('active');
        
        document.getElementById('theme_mode').value = theme;
        
        // Live preview for theme
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else if (theme === 'light') {
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    }
    
    // Form controls
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabLinks = document.querySelectorAll('.settings-nav-link');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                tabLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const target = this.getAttribute('href');
                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                document.querySelector(target).classList.add('show', 'active');
            });
        });
        
        // Password expiry toggle
        const passwordExpiry = document.getElementById('password_expiry');
        const expirySettings = document.getElementById('passwordExpirySettings');
        
        passwordExpiry.addEventListener('change', function() {
            expirySettings.style.display = this.checked ? 'flex' : 'none';
        });
        
        // Trigger on load
        if (passwordExpiry) {
            expirySettings.style.display = passwordExpiry.checked ? 'flex' : 'none';
        }
        
        // Site description counter
        const descriptionInput = document.getElementById('site_description');
        const counter = document.getElementById('descriptionCounter');
        
        if (descriptionInput && counter) {
            descriptionInput.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length}/160`;
                counter.style.color = length > 160 ? '#dc3545' : '#6c757d';
            });
            
            // Initialize counter
            counter.textContent = `${descriptionInput.value.length}/160`;
        }
        
        // Toggle SMTP password visibility
        const toggleBtn = document.getElementById('toggleSmtpPassword');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const passwordInput = document.getElementById('smtp_password');
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
        }
        
        // Initialize Select2
        $('.form-select').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Load active sessions
        loadActiveSessions();
        
        // Initialize color pickers
        $('[type="color"]').colorpicker();
    });
    
    // Save settings
    function saveSettings(section) {
        const form = document.getElementById(`${section}Form`);
        if (!form) return;
        
        const formData = new FormData(form);
        
        fetch('save_settings.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Sozlamalar muvaffaqiyatli saqlandi!', 'success');
            } else {
                showAlert(data.message || 'Xatolik yuz berdi', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Server bilan aloqa uzildi', 'error');
        });
    }
    
    function saveAllSettings() {
        // Collect all forms and send them
        const sections = ['general', 'security', 'email', 'appearance', 'api'];
        const allData = new FormData();
        allData.append('action', 'all');
        
        sections.forEach(section => {
            const form = document.getElementById(`${section}Form`);
            if (form) {
                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                    allData.append(`${section}_${key}`, value);
                }
            }
        });
        
        fetch('save_settings.php', {
            method: 'POST',
            body: allData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Barcha sozlamalar muvaffaqiyatli saqlandi!', 'success');
            } else {
                showAlert(data.message || 'Xatolik yuz berdi', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Server bilan aloqa uzildi', 'error');
        });
    }
    
    function resetToDefaults() {
        if (confirm('Barcha sozlamalar standart qiymatlarga qaytariladi. Davom ettirishni xohlaysizmi?')) {
            fetch('reset_settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'reset', csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Sozlamalar standart qiymatlarga qaytarildi. Sahifa yangilanadi...', 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message || 'Xatolik yuz berdi', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Server bilan aloqa uzildi', 'error');
            });
        }
    }
    
    // Alert function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Active sessions
    function loadActiveSessions() {
        fetch('get_sessions.php')
        .then(response => response.json())
        .then(data => {
            const sessionsContainer = document.getElementById('activeSessions');
            if (!sessionsContainer) return;
            
            if (data.length === 0) {
                sessionsContainer.innerHTML = '<tr><td colspan="5" class="text-center py-3">Faol sessiyalar topilmadi</td></tr>';
                return;
            }
            
            let html = '';
            data.forEach(session => {
                html += `
                    <tr>
                        <td>
                            <i class="fas ${session.device === 'mobile' ? 'fa-mobile-alt' : 'fa-desktop'} me-2"></i>
                            ${session.browser}
                        </td>
                        <td>${session.ip}</td>
                        <td>${session.login_time}</td>
                        <td>${session.last_activity}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="logoutSession(${session.id})">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            sessionsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading sessions:', error);
        });
    }
    
    function logoutSession(sessionId) {
        if (confirm('Bu sessiyani tugatishni xohlaysizmi?')) {
            fetch('logout_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Sessiya muvaffaqiyatli tugatildi', 'success');
                    loadActiveSessions();
                }
            });
        }
    }
    
    function logoutAllSessions() {
        if (confirm('Barcha faol sessiyalarni tugatishni xohlaysizmi? O'zingiz ham tizimdan chiqarilasiz.')) {
            fetch('logout_all_sessions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Barcha sessiyalar tugatildi. Qayta kirishingiz kerak.', 'success');
                    setTimeout(() => location.href = 'login.php', 2000);
                }
            });
        }
    }
    
    // Email test
    function testEmailConnection() {
        const form = document.getElementById('emailForm');
        const formData = new FormData(form);
        formData.append('test_email', true);
        
        const resultDiv = document.getElementById('emailTestResult');
        resultDiv.className = 'test-result';
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div> Test xabari yuborilmoqda...';
        
        fetch('test_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.className = 'test-result test-success';
                resultDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${data.message}`;
            } else {
                resultDiv.className = 'test-result test-error';
                resultDiv.innerHTML = `<i class="fas fa-times-circle me-2"></i> ${data.message}`;
            }
        })
        .catch(error => {
            resultDiv.className = 'test-result test-error';
            resultDiv.innerHTML = `<i class="fas fa-times-circle me-2"></i> Server bilan aloqa uzildi`;
        });
    }
    
    // File browser simulation
    function openFileBrowser(inputId) {
        // In a real application, this would open a file browser dialog
        // For demo purposes, we'll just show an alert
        alert('Haqiqiy ilovada bu funksiya fayl tanlash dialogini ochadi.');
    }
    
    // Backup functions
    function createBackup() {
        if (confirm('Zahira nusxasi yaratilsinmi? Bu bir necha soniya davom etishi mumkin.')) {
            fetch('create_backup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'create_backup' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Zahira nusxasi muvaffaqiyatli yaratildi', 'success');
                } else {
                    showAlert(data.message || 'Xatolik yuz berdi', 'error');
                }
            });
        }
    }
    
    function restoreBackup() {
        alert('Backup\'dan tiklash funksiyasi ishlab chiqilmoqda...');
    }
    
    // System maintenance functions
    function enableMaintenanceMode() {
        if (confirm('Texnik xizmat rejimi yoqilsinmi? Oddiy foydalanuvchilar saytga kira olmaydi.')) {
            fetch('maintenance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'enable' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Texnik xizmat rejimi yoqildi', 'success');
                }
            });
        }
    }
    
    function disableMaintenanceMode() {
        fetch('maintenance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ action: 'disable' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Texnik xizmat rejimi o\'chirildi', 'success');
            }
        });
    }
    
    function clearCache() {
        fetch('clear_cache.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Cache muvaffaqiyatli tozalandi', 'success');
            }
        });
    }
    
    function optimizeDatabase() {
        fetch('optimize_db.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Ma\'lumotlar bazasi optimallashtirildi', 'success');
            }
        });
    }
    
    // API functions
    function copyApiKey(button) {
        const apiKey = button.parentElement.querySelector('.api-key').textContent;
        navigator.clipboard.writeText(apiKey)
            .then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-outline-success');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-outline-success');
                    button.classList.add('btn-outline-secondary');
                }, 2000);
            });
    }
    
    function generateApiKey() {
        if (confirm('Yangi API kaliti yaratilsinmi?')) {
            fetch('generate_api_key.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Yangi API kaliti yaratildi', 'success');
                    // Refresh API keys list
                    location.reload();
                }
            });
        }
    }
    
    function setApiEndpoint(endpoint) {
        document.getElementById('api_endpoint').value = endpoint;
    }
    
    function testApiEndpoint() {
        const endpoint = document.getElementById('api_endpoint').value;
        const responseDiv = document.getElementById('apiResponse');
        
        responseDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div> Testing...</div>';
        
        fetch(`/api/v1/${endpoint}`)
            .then(response => response.json())
            .then(data => {
                responseDiv.innerHTML = `<pre class="mb-0">${JSON.stringify(data, null, 2)}</pre>`;
            })
            .catch(error => {
                responseDiv.innerHTML = `<div class="alert alert-danger">Xatolik: ${error.message}</div>`;
            });
    }
    
    // Danger zone functions
    function resetSystem() {
        if (confirm('Bu tizimni to\'liq qayta o\'rnatadi. Barcha sozlamalar standart holatga qaytariladi. Davom ettirishni xohlaysizmi?')) {
            if (prompt('Davom ettirish uchun "CONFIRM-RESET" so\'zini kiriting:') === 'CONFIRM-RESET') {
                alert('Tizim qayta o\'rnatish jarayoni boshlanadi...');
                // In real app, redirect to reset script
            }
        }
    }
    
    function purgeAllData() {
        if (confirm('BU HARAKAT ORQASIGA QAYTIB BO\'LMAYDI! Barcha ma\'lumotlar o\'chiriladi. Davom ettirishni xohlaysizmi?')) {
            if (prompt('Davom ettirish uchun "DELETE-ALL-DATA" so\'zini kiriting:') === 'DELETE-ALL-DATA') {
                alert('Barcha ma\'lumotlar o\'chirish jarayoni boshlanadi...');
                // In real app, redirect to purge script
            }
        }
    }
    
    function reinstallSystem() {
        alert('Tizimni qayta o\'rnatish funksiyasi ishlab chiqilmoqda...');
    }
</script>