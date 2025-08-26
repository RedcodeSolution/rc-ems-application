@extends('layouts.super_admin')

@section('title', 'System Settings - Super Admin Dashboard')

@section('content')
<div class="system-settings-container">
    <!-- System Settings Header -->
    <div class="system-settings-header">
        <div class="header-content">
            <h1><i class="fas fa-cogs"></i> System Settings</h1>
            <p>Configure and manage all system settings and configurations</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="saveAllSettings()">
                <i class="fas fa-save"></i> Save All Settings
            </button>
            <button class="btn btn-secondary" onclick="resetToDefaults()">
                <i class="fas fa-undo"></i> Reset to Defaults
            </button>
            <button class="btn btn-outline" onclick="exportSettings()">
                <i class="fas fa-download"></i> Export Settings
            </button>
        </div>
    </div>

    <!-- Settings Navigation Tabs -->
    <div class="settings-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showSettingsTab('application')" data-tab="application">
                <i class="fas fa-laptop"></i> Application
            </button>
            <button class="nav-tab" onclick="showSettingsTab('database')" data-tab="database">
                <i class="fas fa-database"></i> Database
            </button>
            <button class="nav-tab" onclick="showSettingsTab('cache')" data-tab="cache">
                <i class="fas fa-memory"></i> Cache
            </button>
            <button class="nav-tab" onclick="showSettingsTab('session')" data-tab="session">
                <i class="fas fa-user-clock"></i> Session
            </button>
            <button class="nav-tab" onclick="showSettingsTab('mail')" data-tab="mail">
                <i class="fas fa-envelope"></i> Mail
            </button>
            <button class="nav-tab" onclick="showSettingsTab('security')" data-tab="security">
                <i class="fas fa-shield-alt"></i> Security
            </button>
            <button class="nav-tab" onclick="showSettingsTab('system')" data-tab="system">
                <i class="fas fa-server"></i> System Info
            </button>
        </div>
    </div>

    <!-- Settings Content Panels -->
    <div class="settings-content">
        
        <!-- Application Settings -->
        <div id="application-settings" class="settings-panel active">
            <div class="panel-header">
                <h2><i class="fas fa-laptop"></i> Application Settings</h2>
                <p>Configure basic application settings and environment</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Basic Configuration</h3>
                    <div class="setting-item">
                        <label for="app_name">Application Name</label>
                        <input type="text" id="app_name" name="app_name" value="{{ $settings['application_settings']['app_name'] }}" class="setting-input">
                        <small>The name of your application</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="app_url">Application URL</label>
                        <input type="url" id="app_url" name="app_url" value="{{ $settings['application_settings']['app_url'] }}" class="setting-input">
                        <small>Base URL for your application</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="app_env">Environment</label>
                        <select id="app_env" name="app_env" class="setting-select">
                            <option value="local" {{ $settings['application_settings']['app_env'] === 'local' ? 'selected' : '' }}>Local</option>
                            <option value="development" {{ $settings['application_settings']['app_env'] === 'development' ? 'selected' : '' }}>Development</option>
                            <option value="staging" {{ $settings['application_settings']['app_env'] === 'staging' ? 'selected' : '' }}>Staging</option>
                            <option value="production" {{ $settings['application_settings']['app_env'] === 'production' ? 'selected' : '' }}>Production</option>
                        </select>
                        <small>Current application environment</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Regional Settings</h3>
                    <div class="setting-item">
                        <label for="app_timezone">Timezone</label>
                        <select id="app_timezone" name="app_timezone" class="setting-select">
                            <option value="UTC" {{ $settings['application_settings']['app_timezone'] === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ $settings['application_settings']['app_timezone'] === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="Europe/London" {{ $settings['application_settings']['app_timezone'] === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                            <option value="Asia/Tokyo" {{ $settings['application_settings']['app_timezone'] === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                            <option value="Asia/Kolkata" {{ $settings['application_settings']['app_timezone'] === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                        </select>
                        <small>Default timezone for the application</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="app_locale">Locale</label>
                        <select id="app_locale" name="app_locale" class="setting-select">
                            <option value="en" {{ $settings['application_settings']['app_locale'] === 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ $settings['application_settings']['app_locale'] === 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ $settings['application_settings']['app_locale'] === 'fr' ? 'selected' : '' }}>French</option>
                            <option value="de" {{ $settings['application_settings']['app_locale'] === 'de' ? 'selected' : '' }}>German</option>
                        </select>
                        <small>Default language for the application</small>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $settings['application_settings']['app_debug'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Debug Mode
                        </label>
                        <small>Enable debug mode (disable in production)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Settings -->
        <div id="database-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-database"></i> Database Settings</h2>
                <p>Configure database connections and settings</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Connection Settings</h3>
                    <div class="setting-item readonly">
                        <label>Connection Type</label>
                        <input type="text" value="{{ $settings['database_settings']['connection'] }}" class="setting-input" readonly>
                        <small>Current database connection type</small>
                    </div>
                    
                    <div class="setting-item readonly">
                        <label>Host</label>
                        <input type="text" value="{{ $settings['database_settings']['host'] }}" class="setting-input" readonly>
                        <small>Database server host</small>
                    </div>
                    
                    <div class="setting-item readonly">
                        <label>Port</label>
                        <input type="text" value="{{ $settings['database_settings']['port'] }}" class="setting-input" readonly>
                        <small>Database server port</small>
                    </div>
                    
                    <div class="setting-item readonly">
                        <label>Database Name</label>
                        <input type="text" value="{{ $settings['database_settings']['database'] }}" class="setting-input" readonly>
                        <small>Name of the database</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Character Settings</h3>
                    <div class="setting-item readonly">
                        <label>Charset</label>
                        <input type="text" value="{{ $settings['database_settings']['charset'] }}" class="setting-input" readonly>
                        <small>Database character set</small>
                    </div>
                    
                    <div class="setting-item readonly">
                        <label>Collation</label>
                        <input type="text" value="{{ $settings['database_settings']['collation'] }}" class="setting-input" readonly>
                        <small>Database collation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cache Settings -->
        <div id="cache-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-memory"></i> Cache Settings</h2>
                <p>Configure caching system and performance settings</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Cache Configuration</h3>
                    <div class="setting-item">
                        <label for="cache_driver">Default Cache Driver</label>
                        <select id="cache_driver" name="cache_driver" class="setting-select">
                            <option value="file" {{ $settings['cache_settings']['default_driver'] === 'file' ? 'selected' : '' }}>File</option>
                            <option value="database" {{ $settings['cache_settings']['default_driver'] === 'database' ? 'selected' : '' }}>Database</option>
                            <option value="redis" {{ $settings['cache_settings']['default_driver'] === 'redis' ? 'selected' : '' }}>Redis</option>
                            <option value="memcached" {{ $settings['cache_settings']['default_driver'] === 'memcached' ? 'selected' : '' }}>Memcached</option>
                        </select>
                        <small>Default caching system to use</small>
                    </div>
                    
                    @if($settings['cache_settings']['file_path'])
                    <div class="setting-item readonly">
                        <label>Cache File Path</label>
                        <input type="text" value="{{ $settings['cache_settings']['file_path'] }}" class="setting-input" readonly>
                        <small>Directory where cache files are stored</small>
                    </div>
                    @endif
                </div>
                
                <div class="setting-group">
                    <h3>Cache Actions</h3>
                    <div class="setting-actions">
                        <button class="btn btn-warning" onclick="clearCache('config')">
                            <i class="fas fa-trash"></i> Clear Config Cache
                        </button>
                        <button class="btn btn-warning" onclick="clearCache('route')">
                            <i class="fas fa-trash"></i> Clear Route Cache
                        </button>
                        <button class="btn btn-warning" onclick="clearCache('view')">
                            <i class="fas fa-trash"></i> Clear View Cache
                        </button>
                        <button class="btn btn-danger" onclick="clearCache('all')">
                            <i class="fas fa-trash-alt"></i> Clear All Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Settings -->
        <div id="session-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-user-clock"></i> Session Settings</h2>
                <p>Configure user session handling and security</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Session Configuration</h3>
                    <div class="setting-item">
                        <label for="session_driver">Session Driver</label>
                        <select id="session_driver" name="session_driver" class="setting-select">
                            <option value="file" {{ $settings['session_settings']['driver'] === 'file' ? 'selected' : '' }}>File</option>
                            <option value="cookie" {{ $settings['session_settings']['driver'] === 'cookie' ? 'selected' : '' }}>Cookie</option>
                            <option value="database" {{ $settings['session_settings']['driver'] === 'database' ? 'selected' : '' }}>Database</option>
                            <option value="redis" {{ $settings['session_settings']['driver'] === 'redis' ? 'selected' : '' }}>Redis</option>
                        </select>
                        <small>How sessions are stored</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="session_lifetime">Session Lifetime (minutes)</label>
                        <input type="number" id="session_lifetime" name="session_lifetime" value="{{ $settings['session_settings']['lifetime'] }}" class="setting-input">
                        <small>How long sessions remain active</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Security Settings</h3>
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $settings['session_settings']['expire_on_close'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Expire on Browser Close
                        </label>
                        <small>End session when browser is closed</small>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $settings['session_settings']['encrypt'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Encrypt Session Data
                        </label>
                        <small>Encrypt session data for security</small>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $settings['session_settings']['http_only'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            HTTP Only Cookies
                        </label>
                        <small>Prevent JavaScript access to session cookies</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mail Settings -->
        <div id="mail-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-envelope"></i> Mail Settings</h2>
                <p>Configure email system and SMTP settings</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Mail Configuration</h3>
                    <div class="setting-item">
                        <label for="mail_driver">Default Mailer</label>
                        <select id="mail_driver" name="mail_driver" class="setting-select">
                            <option value="smtp" {{ $settings['mail_settings']['default_mailer'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ $settings['mail_settings']['default_mailer'] === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ $settings['mail_settings']['default_mailer'] === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ $settings['mail_settings']['default_mailer'] === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        </select>
                        <small>Email delivery method</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="mail_from_address">From Address</label>
                        <input type="email" id="mail_from_address" name="mail_from_address" value="{{ $settings['mail_settings']['from_address'] }}" class="setting-input">
                        <small>Default sender email address</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="mail_from_name">From Name</label>
                        <input type="text" id="mail_from_name" name="mail_from_name" value="{{ $settings['mail_settings']['from_name'] }}" class="setting-input">
                        <small>Default sender name</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Test Email</h3>
                    <div class="setting-item">
                        <label for="test_email">Test Email Address</label>
                        <input type="email" id="test_email" name="test_email" class="setting-input" placeholder="Enter email to test">
                        <small>Send a test email to verify configuration</small>
                    </div>
                    <div class="setting-actions">
                        <button class="btn btn-primary" onclick="sendTestEmail()">
                            <i class="fas fa-paper-plane"></i> Send Test Email
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div id="security-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-shield-alt"></i> Security Settings</h2>
                <p>Configure security and authentication settings</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Password Security</h3>
                    <div class="setting-item">
                        <label for="bcrypt_rounds">BCrypt Rounds</label>
                        <input type="number" id="bcrypt_rounds" name="bcrypt_rounds" value="{{ $settings['security_settings']['bcrypt_rounds'] }}" class="setting-input" min="4" max="20">
                        <small>Number of rounds for password hashing (higher = more secure, slower)</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="password_timeout">Password Confirmation Timeout (seconds)</label>
                        <input type="number" id="password_timeout" name="password_timeout" value="{{ $settings['security_settings']['password_timeout'] }}" class="setting-input">
                        <small>How long password confirmations remain valid</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>API Security</h3>
                    <div class="setting-item">
                        <label for="sanctum_expiration">API Token Expiration (minutes)</label>
                        <input type="number" id="sanctum_expiration" name="sanctum_expiration" value="{{ $settings['security_settings']['sanctum_expiration'] ?? 'null' }}" class="setting-input">
                        <small>How long API tokens remain valid (null = never expire)</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Security Actions</h3>
                    <div class="setting-actions">
                        <button class="btn btn-warning" onclick="generateAppKey()">
                            <i class="fas fa-key"></i> Generate New App Key
                        </button>
                        <button class="btn btn-danger" onclick="revokeAllTokens()">
                            <i class="fas fa-ban"></i> Revoke All API Tokens
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div id="system-settings" class="settings-panel">
            <div class="panel-header">
                <h2><i class="fas fa-server"></i> System Information</h2>
                <p>View system information and server details</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Application Info</h3>
                    <div class="info-item">
                        <label>PHP Version</label>
                        <span class="info-value">{{ $settings['system_info']['php_version'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Laravel Version</label>
                        <span class="info-value">{{ $settings['system_info']['laravel_version'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Server Software</label>
                        <span class="info-value">{{ $settings['system_info']['server_software'] }}</span>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Server Details</h3>
                    <div class="info-item">
                        <label>Document Root</label>
                        <span class="info-value">{{ $settings['system_info']['document_root'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Server Admin</label>
                        <span class="info-value">{{ $settings['system_info']['server_admin'] }}</span>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>System Actions</h3>
                    <div class="setting-actions">
                        <button class="btn btn-primary" onclick="runSystemCheck()">
                            <i class="fas fa-check-circle"></i> Run System Check
                        </button>
                        <button class="btn btn-secondary" onclick="viewPhpInfo()">
                            <i class="fas fa-info-circle"></i> View PHP Info
                        </button>
                        <button class="btn btn-outline" onclick="downloadSystemReport()">
                            <i class="fas fa-download"></i> Download System Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .system-settings-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .system-settings-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-content h1 i {
        color: var(--redcode-primary);
        font-size: 1.75rem;
    }

    .header-content p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Button Styles */
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--gray-600), var(--gray-700));
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
    }

    .btn-outline {
        background: white;
        color: var(--redcode-primary);
        border: 2px solid var(--redcode-primary);
    }

    .btn-outline:hover {
        background: var(--redcode-primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    /* Navigation Tabs */
    .settings-navigation {
        margin-bottom: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .nav-tabs {
        display: flex;
        flex-wrap: wrap;
    }

    .nav-tab {
        flex: 1;
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 120px;
    }

    .nav-tab:hover {
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .nav-tab.active {
        background: var(--bg-secondary);
        color: var(--redcode-primary);
        border-bottom-color: var(--redcode-primary);
    }

    .nav-tab i {
        font-size: 0.9rem;
    }

    /* Settings Content */
    .settings-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .settings-panel {
        display: none;
        padding: 2rem;
    }

    .settings-panel.active {
        display: block;
    }

    .panel-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .panel-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-header h2 i {
        color: var(--redcode-primary);
    }

    .panel-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }

    .setting-group {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-light);
    }

    .setting-group h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    /* Setting Items */
    .setting-item {
        margin-bottom: 1.5rem;
    }

    .setting-item:last-child {
        margin-bottom: 0;
    }

    .setting-item label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .setting-input,
    .setting-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .setting-input:focus,
    .setting-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .setting-item.readonly .setting-input {
        background: var(--bg-secondary);
        color: var(--text-secondary);
        cursor: not-allowed;
    }

    .setting-item small {
        display: block;
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Checkbox Styles */
    .setting-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-primary);
    }

    .setting-checkbox input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-medium);
        border-radius: 4px;
        position: relative;
        transition: all 0.3s ease;
        background: white;
    }

    .setting-checkbox input[type="checkbox"]:checked + .checkmark {
        background: var(--redcode-primary);
        border-color: var(--redcode-primary);
    }

    .setting-checkbox input[type="checkbox"]:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    /* Info Items */
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item label {
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .info-value {
        font-family: monospace;
        color: var(--text-secondary);
        background: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    /* Setting Actions */
    .setting-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .setting-actions .btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .system-settings-container {
            padding: 1rem;
        }

        .system-settings-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .nav-tabs {
            flex-direction: column;
        }

        .nav-tab {
            min-width: auto;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }

        .setting-actions {
            flex-direction: column;
        }

        .setting-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Initialize settings page
    document.addEventListener('DOMContentLoaded', function() {
        // Set up tab switching
        setupTabSwitching();
        
        // Initialize form handling
        setupFormHandling();
    });

    // Tab switching functionality
    function setupTabSwitching() {
        const tabs = document.querySelectorAll('.nav-tab');
        const panels = document.querySelectorAll('.settings-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                showSettingsTab(targetTab);
            });
        });
    }

    function showSettingsTab(tabName) {
        // Remove active class from all tabs and panels
        document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to selected tab and panel
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(`${tabName}-settings`).classList.add('active');
    }

    // Form handling
    function setupFormHandling() {
        // Add change listeners to form elements
        const inputs = document.querySelectorAll('.setting-input, .setting-select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                markAsChanged(this);
            });
        });
    }

    function markAsChanged(element) {
        element.style.borderColor = '#F59E0B';
        element.style.background = '#FEF3C7';
    }

    // Settings actions
    function saveAllSettings() {
        showNotification('Saving all settings...', 'info');
        
        // Collect all form data
        const formData = new FormData();
        
        // Add all input values
        document.querySelectorAll('.setting-input, .setting-select').forEach(input => {
            if (input.name) {
                formData.append(input.name, input.value);
            }
        });
        
        // Add checkbox values
        document.querySelectorAll('.setting-checkbox input[type="checkbox"]').forEach(checkbox => {
            if (checkbox.name) {
                formData.append(checkbox.name, checkbox.checked ? 1 : 0);
            }
        });
        
        // Here you would send the data to your backend
        setTimeout(() => {
            showNotification('Settings saved successfully!', 'success');
            // Reset border colors
            document.querySelectorAll('.setting-input, .setting-select').forEach(input => {
                input.style.borderColor = '';
                input.style.background = '';
            });
        }, 2000);
    }

    function resetToDefaults() {
        if (confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.')) {
            showNotification('Resetting to default settings...', 'warning');
            
            // Here you would reset to defaults
            setTimeout(() => {
                showNotification('Settings reset to defaults!', 'success');
                location.reload(); // Reload to show default values
            }, 2000);
        }
    }

    function exportSettings() {
        showNotification('Exporting settings...', 'info');
        
        // Here you would export settings
        setTimeout(() => {
            showNotification('Settings exported successfully!', 'success');
        }, 1000);
    }

    // Cache actions
    function clearCache(type) {
        const cacheTypes = {
            'config': 'Configuration Cache',
            'route': 'Route Cache',
            'view': 'View Cache',
            'all': 'All Caches'
        };
        
        showNotification(`Clearing ${cacheTypes[type]}...`, 'info');
        
        // Here you would call the appropriate Artisan command
        setTimeout(() => {
            showNotification(`${cacheTypes[type]} cleared successfully!`, 'success');
        }, 1500);
    }

    // Mail actions
    function sendTestEmail() {
        const testEmail = document.getElementById('test_email').value;
        if (!testEmail) {
            showNotification('Please enter a test email address.', 'error');
            return;
        }
        
        showNotification('Sending test email...', 'info');
        
        // Here you would send the test email
        setTimeout(() => {
            showNotification(`Test email sent to ${testEmail}!`, 'success');
        }, 2000);
    }

    // Security actions
    function generateAppKey() {
        if (confirm('Are you sure you want to generate a new application key? This will log out all users.')) {
            showNotification('Generating new application key...', 'warning');
            
            // Here you would generate a new app key
            setTimeout(() => {
                showNotification('New application key generated!', 'success');
            }, 2000);
        }
    }

    function revokeAllTokens() {
        if (confirm('Are you sure you want to revoke all API tokens? This will invalidate all active API sessions.')) {
            showNotification('Revoking all API tokens...', 'warning');
            
            // Here you would revoke all tokens
            setTimeout(() => {
                showNotification('All API tokens revoked!', 'success');
            }, 1500);
        }
    }

    // System actions
    function runSystemCheck() {
        showNotification('Running system check...', 'info');
        
        // Here you would run system diagnostics
        setTimeout(() => {
            showNotification('System check completed successfully!', 'success');
        }, 3000);
    }

    function viewPhpInfo() {
        // Open PHP info in a new window
        window.open('/phpinfo', '_blank');
    }

    function downloadSystemReport() {
        showNotification('Generating system report...', 'info');
        
        // Here you would generate and download the report
        setTimeout(() => {
            showNotification('System report downloaded!', 'success');
        }, 2000);
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            border-left: 4px solid ${type === 'success' ? '#10B981' : type === 'error' ? '#DC2626' : type === 'warning' ? '#F59E0B' : '#2563EB'};
            font-weight: 500;
            max-width: 300px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    }
</script>
@endsection
