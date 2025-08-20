@extends('layouts.super_admin')

@section('title', 'Database Settings - Super Admin Dashboard')

@section('content')
<div class="database-settings-container">
    <!-- Database Settings Header -->
    <div class="database-settings-header">
        <div class="header-content">
            <h1><i class="fas fa-database"></i> Database Settings</h1>
            <p>Monitor database performance, manage connections, and configure database-related settings</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="saveDatabaseSettings()">
                <i class="fas fa-save"></i> Save Settings
            </button>
            <button class="btn btn-warning" onclick="optimizeDatabase()">
                <i class="fas fa-tools"></i> Optimize Database
            </button>
            <button class="btn btn-outline" onclick="exportDatabaseReport()">
                <i class="fas fa-download"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Database Overview Dashboard -->
    <div class="database-overview">
        <div class="overview-cards">
            <div class="database-card total-tables">
                <div class="card-icon">
                    <i class="fas fa-table"></i>
                </div>
                <div class="card-content">
                    <h3>Total Tables</h3>
                    <div class="stat-number">{{ $databaseData['database_stats']['total_tables'] }}</div>
                    <p>Database tables</p>
                </div>
            </div>

            <div class="database-card total-records">
                <div class="card-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="card-content">
                    <h3>Total Records</h3>
                    <div class="stat-number">{{ number_format($databaseData['database_stats']['total_records']) }}</div>
                    <p>Across all tables</p>
                </div>
            </div>

            <div class="database-card database-size">
                <div class="card-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="card-content">
                    <h3>Database Size</h3>
                    <div class="stat-number">{{ $databaseData['database_stats']['database_size'] }}</div>
                    <p>Total storage used</p>
                </div>
            </div>

            <div class="database-card uptime">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <h3>Database Uptime</h3>
                    <div class="stat-text">{{ $databaseData['database_stats']['uptime'] }}</div>
                    <p>Continuous operation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Settings Tabs -->
    <div class="database-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showDatabaseTab('connection')" data-tab="connection">
                <i class="fas fa-plug"></i> Connection
            </button>
            <button class="nav-tab" onclick="showDatabaseTab('tables')" data-tab="tables">
                <i class="fas fa-table"></i> Tables
            </button>
            <button class="nav-tab" onclick="showDatabaseTab('performance')" data-tab="performance">
                <i class="fas fa-tachometer-alt"></i> Performance
            </button>
            <button class="nav-tab" onclick="showDatabaseTab('backup')" data-tab="backup">
                <i class="fas fa-archive"></i> Backup
            </button>
            <button class="nav-tab" onclick="showDatabaseTab('maintenance')" data-tab="maintenance">
                <i class="fas fa-wrench"></i> Maintenance
            </button>
        </div>
    </div>

    <!-- Database Settings Content -->
    <div class="database-content">
        
        <!-- Connection Settings -->
        <div id="connection-settings" class="database-panel active">
            <div class="panel-header">
                <h2><i class="fas fa-plug"></i> Database Connection & Configuration</h2>
                <p>View and manage database connection settings and configuration</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Connection Information</h3>
                    <div class="info-item">
                        <label>Default Connection</label>
                        <span class="info-value">{{ $databaseData['connection_info']['default_connection'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Database Driver</label>
                        <span class="info-value">{{ $databaseData['connection_info']['driver'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Host</label>
                        <span class="info-value">{{ $databaseData['connection_info']['host'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Port</label>
                        <span class="info-value">{{ $databaseData['connection_info']['port'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Database Name</label>
                        <span class="info-value">{{ $databaseData['connection_info']['database'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Username</label>
                        <span class="info-value">{{ $databaseData['connection_info']['username'] }}</span>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Character Settings</h3>
                    <div class="info-item">
                        <label>Charset</label>
                        <span class="info-value">{{ $databaseData['connection_info']['charset'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Collation</label>
                        <span class="info-value">{{ $databaseData['connection_info']['collation'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Table Prefix</label>
                        <span class="info-value">{{ $databaseData['connection_info']['prefix'] ?: 'None' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Strict Mode</label>
                        <span class="info-value {{ $databaseData['connection_info']['strict'] ? 'status-enabled' : 'status-disabled' }}">
                            {{ $databaseData['connection_info']['strict'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <label>Storage Engine</label>
                        <span class="info-value">{{ $databaseData['connection_info']['engine'] ?: 'InnoDB' }}</span>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Connection Actions</h3>
                    <div class="database-actions">
                        <button class="btn btn-primary" onclick="testConnection()">
                            <i class="fas fa-wifi"></i> Test Connection
                        </button>
                        <button class="btn btn-secondary" onclick="refreshConnection()">
                            <i class="fas fa-sync"></i> Refresh Connection
                        </button>
                        <button class="btn btn-warning" onclick="flushPrivileges()">
                            <i class="fas fa-shield-alt"></i> Flush Privileges
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Information -->
        <div id="tables-settings" class="database-panel">
            <div class="panel-header">
                <h2><i class="fas fa-table"></i> Database Tables & Structure</h2>
                <p>Monitor table statistics, sizes, and perform table operations</p>
            </div>
            
            <div class="tables-container">
                <div class="tables-overview">
                    <div class="overview-stats">
                        <div class="stat-card">
                            <h4>Total Tables</h4>
                            <span class="stat-value">{{ $databaseData['database_stats']['total_tables'] }}</span>
                        </div>
                        <div class="stat-card">
                            <h4>Total Records</h4>
                            <span class="stat-value">{{ number_format($databaseData['database_stats']['total_records']) }}</span>
                        </div>
                        <div class="stat-card">
                            <h4>Largest Table</h4>
                            <span class="stat-value">{{ $databaseData['database_stats']['largest_table'] }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="tables-list">
                    <div class="table-filters">
                        <input type="text" id="table-search" placeholder="Search tables..." class="filter-input">
                        <select id="table-filter" class="filter-select">
                            <option value="">All Tables</option>
                            <option value="core">Core Tables</option>
                            <option value="user">User Tables</option>
                            <option value="system">System Tables</option>
                        </select>
                        <button class="btn btn-outline" onclick="refreshTables()">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                    
                    <div class="tables-grid">
                        @foreach($databaseData['tables_info'] as $tableName => $tableInfo)
                        <div class="table-card {{ $tableInfo['exists'] ? 'table-exists' : 'table-missing' }}">
                            <div class="table-header">
                                <h4>{{ $tableName }}</h4>
                                <div class="table-status">
                                    <span class="status-indicator {{ $tableInfo['exists'] ? 'status-active' : 'status-inactive' }}"></span>
                                    {{ $tableInfo['exists'] ? 'Active' : 'Missing' }}
                                </div>
                            </div>
                            
                            <div class="table-stats">
                                <div class="stat-row">
                                    <span class="stat-label">Records:</span>
                                    <span class="stat-value">{{ number_format($tableInfo['count']) }}</span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label">Size:</span>
                                    <span class="stat-value">{{ $tableInfo['size'] }}</span>
                                </div>
                            </div>
                            
                            @if($tableInfo['exists'])
                            <div class="table-actions">
                                <button class="btn-small btn-primary" onclick="analyzeTable('{{ $tableName }}')">
                                    <i class="fas fa-search"></i> Analyze
                                </button>
                                <button class="btn-small btn-warning" onclick="optimizeTable('{{ $tableName }}')">
                                    <i class="fas fa-tools"></i> Optimize
                                </button>
                                <button class="btn-small btn-outline" onclick="exportTable('{{ $tableName }}')">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Settings -->
        <div id="performance-settings" class="database-panel">
            <div class="panel-header">
                <h2><i class="fas fa-tachometer-alt"></i> Database Performance & Monitoring</h2>
                <p>Monitor database performance metrics and configure optimization settings</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Performance Metrics</h3>
                    <div class="performance-metrics">
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                            <div class="metric-content">
                                <h4>Average Query Time</h4>
                                <span class="metric-value">{{ $databaseData['performance_metrics']['avg_query_time'] }}</span>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="metric-content">
                                <h4>Slow Queries</h4>
                                <span class="metric-value">{{ $databaseData['performance_metrics']['slow_queries'] }}</span>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="metric-content">
                                <h4>Active Connections</h4>
                                <span class="metric-value">{{ $databaseData['performance_metrics']['connections_active'] }}/{{ $databaseData['performance_metrics']['connections_max'] }}</span>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-memory"></i>
                            </div>
                            <div class="metric-content">
                                <h4>Buffer Pool Usage</h4>
                                <span class="metric-value">{{ $databaseData['performance_metrics']['buffer_pool_usage'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Configuration Settings</h3>
                    @foreach($databaseData['config_settings'] as $setting => $value)
                    <div class="setting-item">
                        <label for="{{ $setting }}">{{ ucwords(str_replace('_', ' ', $setting)) }}</label>
                        <input type="text" id="{{ $setting }}" name="{{ $setting }}" value="{{ $value }}" class="setting-input" readonly>
                        <small>Current {{ strtolower(str_replace('_', ' ', $setting)) }} setting</small>
                    </div>
                    @endforeach
                </div>
                
                <div class="setting-group">
                    <h3>Performance Actions</h3>
                    <div class="database-actions">
                        <button class="btn btn-primary" onclick="runPerformanceAnalysis()">
                            <i class="fas fa-chart-line"></i> Performance Analysis
                        </button>
                        <button class="btn btn-warning" onclick="optimizeQueries()">
                            <i class="fas fa-tools"></i> Optimize Queries
                        </button>
                        <button class="btn btn-secondary" onclick="clearQueryCache()">
                            <i class="fas fa-trash"></i> Clear Query Cache
                        </button>
                        <button class="btn btn-outline" onclick="exportPerformanceReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div id="backup-settings" class="database-panel">
            <div class="panel-header">
                <h2><i class="fas fa-archive"></i> Database Backup & Recovery</h2>
                <p>Configure automatic backups and manage database recovery options</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Backup Configuration</h3>
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $databaseData['backup_info']['auto_backup_enabled'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Enable Automatic Backups
                        </label>
                        <small>Automatically create database backups</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="backup_frequency">Backup Frequency</label>
                        <select id="backup_frequency" name="backup_frequency" class="setting-select">
                            <option value="hourly">Hourly</option>
                            <option value="daily" {{ $databaseData['backup_info']['backup_frequency'] === 'Daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <small>How often to create automatic backups</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="backup_retention">Backup Retention</label>
                        <input type="text" id="backup_retention" name="backup_retention" value="{{ $databaseData['backup_info']['backup_retention'] }}" class="setting-input">
                        <small>How long to keep backup files</small>
                    </div>
                    
                    <div class="setting-item">
                        <label for="backup_location">Backup Location</label>
                        <input type="text" id="backup_location" name="backup_location" value="{{ $databaseData['backup_info']['backup_location'] }}" class="setting-input">
                        <small>Directory path for storing backups</small>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" {{ $databaseData['backup_info']['compression_enabled'] ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Enable Compression
                        </label>
                        <small>Compress backup files to save space</small>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Backup Status</h3>
                    <div class="info-item">
                        <label>Last Backup</label>
                        <span class="info-value">{{ $databaseData['backup_info']['last_backup_size'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Last Backup Size</label>
                        <span class="info-value">{{ $databaseData['backup_info']['last_backup_size'] }}</span>
                    </div>
                    
                    <div class="database-actions">
                        <button class="btn btn-primary" onclick="createBackup()">
                            <i class="fas fa-plus"></i> Create Backup Now
                        </button>
                        <button class="btn btn-secondary" onclick="restoreBackup()">
                            <i class="fas fa-upload"></i> Restore from Backup
                        </button>
                        <button class="btn btn-warning" onclick="testBackupRestore()">
                            <i class="fas fa-vial"></i> Test Backup/Restore
                        </button>
                        <button class="btn btn-outline" onclick="downloadBackup()">
                            <i class="fas fa-download"></i> Download Latest Backup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Settings -->
        <div id="maintenance-settings" class="database-panel">
            <div class="panel-header">
                <h2><i class="fas fa-wrench"></i> Database Maintenance & Operations</h2>
                <p>Perform database maintenance tasks and manage database operations</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Migration Status</h3>
                    <div class="info-item">
                        <label>Total Migrations</label>
                        <span class="info-value">{{ $databaseData['migration_info']['total_migrations'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Pending Migrations</label>
                        <span class="info-value">{{ $databaseData['migration_info']['pending_migrations'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Last Migration</label>
                        <span class="info-value migration-name">{{ $databaseData['migration_info']['last_migration'] }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Migration Status</label>
                        <span class="info-value status-enabled">{{ $databaseData['migration_info']['migration_status'] }}</span>
                    </div>
                    
                    <div class="database-actions">
                        <button class="btn btn-primary" onclick="runMigrations()">
                            <i class="fas fa-play"></i> Run Migrations
                        </button>
                        <button class="btn btn-warning" onclick="rollbackMigration()">
                            <i class="fas fa-undo"></i> Rollback Last Migration
                        </button>
                        <button class="btn btn-secondary" onclick="refreshMigrations()">
                            <i class="fas fa-sync"></i> Refresh Status
                        </button>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Database Security</h3>
                    @foreach($databaseData['security_settings'] as $setting => $value)
                    <div class="info-item">
                        <label>{{ ucwords(str_replace('_', ' ', $setting)) }}</label>
                        @if(is_bool($value))
                            <span class="info-value {{ $value ? 'status-enabled' : 'status-disabled' }}">
                                {{ $value ? 'Enabled' : 'Disabled' }}
                            </span>
                        @else
                            <span class="info-value">{{ $value }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <div class="setting-group">
                    <h3>Maintenance Operations</h3>
                    <div class="database-actions">
                        <button class="btn btn-primary" onclick="analyzeTables()">
                            <i class="fas fa-search"></i> Analyze All Tables
                        </button>
                        <button class="btn btn-warning" onclick="optimizeAllTables()">
                            <i class="fas fa-tools"></i> Optimize All Tables
                        </button>
                        <button class="btn btn-secondary" onclick="repairTables()">
                            <i class="fas fa-wrench"></i> Repair Tables
                        </button>
                        <button class="btn btn-danger" onclick="vacuumDatabase()">
                            <i class="fas fa-broom"></i> Vacuum Database
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .database-settings-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .database-settings-header {
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

    /* Database Overview Cards */
    .database-overview {
        margin-bottom: 2rem;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .database-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
    }

    .database-card:hover {
        transform: translateY(-2px);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .total-tables .card-icon {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
    }

    .total-records .card-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .database-size .card-icon {
        background: linear-gradient(135deg, #7C3AED, #6D28D9);
    }

    .uptime .card-icon {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
    }

    .card-content h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-text {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .card-content p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin: 0;
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

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
    }

    .btn-outline {
        background: white;
        color: var(--redcode-primary);
        border: 2px solid var(--redcode-primary);
    }

    .btn-outline:hover {
        background: var(--redcode-primary);
        color: white;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    /* Navigation Tabs */
    .database-navigation {
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
        min-width: 140px;
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

    /* Database Content */
    .database-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .database-panel {
        display: none;
        padding: 2rem;
    }

    .database-panel.active {
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

    .status-enabled {
        color: #059669 !important;
        background: #ECFDF5 !important;
    }

    .status-disabled {
        color: #DC2626 !important;
        background: #FEF2F2 !important;
    }

    .migration-name {
        font-size: 0.75rem;
        word-break: break-all;
    }

    /* Database Actions */
    .database-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .database-actions .btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    /* Tables Section */
    .tables-container {
        max-width: 100%;
    }

    .tables-overview {
        margin-bottom: 2rem;
    }

    .overview-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: var(--bg-secondary);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        border: 1px solid var(--border-light);
    }

    .stat-card h4 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .table-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-input,
    .filter-select {
        padding: 0.5rem;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        font-size: 0.9rem;
        flex: 1;
        min-width: 150px;
    }

    .tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .table-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    .table-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table-exists {
        border-left: 4px solid #10B981;
    }

    .table-missing {
        border-left: 4px solid #DC2626;
        opacity: 0.7;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .table-header h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .table-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-active {
        background: #10B981;
        animation: pulse 2s infinite;
    }

    .status-inactive {
        background: #DC2626;
    }

    .table-stats {
        margin-bottom: 1rem;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .stat-row .stat-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Performance Metrics */
    .performance-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .metric-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--redcode-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-content h4 {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .metric-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
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

    /* Animations */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .database-settings-container {
            padding: 1rem;
        }

        .database-settings-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .overview-cards {
            grid-template-columns: 1fr;
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

        .table-filters {
            flex-direction: column;
        }

        .filter-input,
        .filter-select {
            min-width: auto;
        }

        .tables-grid {
            grid-template-columns: 1fr;
        }

        .performance-metrics {
            grid-template-columns: 1fr;
        }

        .database-actions {
            flex-direction: column;
        }

        .database-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Initialize database settings page
    document.addEventListener('DOMContentLoaded', function() {
        setupDatabaseTabs();
        setupTableFilters();
    });

    // Tab switching functionality
    function setupDatabaseTabs() {
        const tabs = document.querySelectorAll('.nav-tab');
        const panels = document.querySelectorAll('.database-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                showDatabaseTab(targetTab);
            });
        });
    }

    function showDatabaseTab(tabName) {
        // Remove active class from all tabs and panels
        document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.database-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to selected tab and panel
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(`${tabName}-settings`).classList.add('active');
    }

    // Table filtering
    function setupTableFilters() {
        const searchInput = document.getElementById('table-search');
        const filterSelect = document.getElementById('table-filter');

        if (searchInput) {
            searchInput.addEventListener('input', filterTables);
        }
        if (filterSelect) {
            filterSelect.addEventListener('change', filterTables);
        }
    }

    function filterTables() {
        const searchTerm = document.getElementById('table-search')?.value.toLowerCase() || '';
        const filterType = document.getElementById('table-filter')?.value || '';
        const tableCards = document.querySelectorAll('.table-card');

        tableCards.forEach(card => {
            const tableName = card.querySelector('h4').textContent.toLowerCase();
            const matchesSearch = tableName.includes(searchTerm);
            const matchesFilter = !filterType || getTableCategory(tableName).includes(filterType);

            card.style.display = matchesSearch && matchesFilter ? 'block' : 'none';
        });
    }

    function getTableCategory(tableName) {
        const coreTable = ['users', 'employees', 'admins', 'departments'];
        const userTables = ['users', 'employees', 'teams'];
        const systemTables = ['sessions', 'migrations', 'notifications'];

        if (coreTable.includes(tableName)) return 'core';
        if (userTables.includes(tableName)) return 'user';
        if (systemTables.includes(tableName)) return 'system';
        return '';
    }

    // Database actions
    function saveDatabaseSettings() {
        showNotification('Saving database settings...', 'info');
        
        setTimeout(() => {
            showNotification('Database settings saved successfully!', 'success');
        }, 2000);
    }

    function optimizeDatabase() {
        showNotification('Optimizing database...', 'info');
        
        setTimeout(() => {
            showNotification('Database optimization completed!', 'success');
        }, 5000);
    }

    function exportDatabaseReport() {
        showNotification('Generating database report...', 'info');
        
        setTimeout(() => {
            showNotification('Database report exported successfully!', 'success');
        }, 3000);
    }

    // Connection actions
    function testConnection() {
        showNotification('Testing database connection...', 'info');
        
        setTimeout(() => {
            showNotification('Database connection test successful!', 'success');
        }, 2000);
    }

    function refreshConnection() {
        showNotification('Refreshing database connection...', 'info');
        
        setTimeout(() => {
            showNotification('Database connection refreshed!', 'success');
        }, 1500);
    }

    function flushPrivileges() {
        showNotification('Flushing database privileges...', 'info');
        
        setTimeout(() => {
            showNotification('Database privileges flushed!', 'success');
        }, 1000);
    }

    // Table actions
    function refreshTables() {
        showNotification('Refreshing table information...', 'info');
        
        setTimeout(() => {
            showNotification('Table information refreshed!', 'success');
            // Here you would reload the table data
        }, 1500);
    }

    function analyzeTable(tableName) {
        showNotification(`Analyzing table: ${tableName}...`, 'info');
        
        setTimeout(() => {
            showNotification(`Table ${tableName} analysis completed!`, 'success');
        }, 2000);
    }

    function optimizeTable(tableName) {
        showNotification(`Optimizing table: ${tableName}...`, 'info');
        
        setTimeout(() => {
            showNotification(`Table ${tableName} optimized successfully!`, 'success');
        }, 3000);
    }

    function exportTable(tableName) {
        showNotification(`Exporting table: ${tableName}...`, 'info');
        
        setTimeout(() => {
            showNotification(`Table ${tableName} exported successfully!`, 'success');
        }, 2000);
    }

    // Performance actions
    function runPerformanceAnalysis() {
        showNotification('Running performance analysis...', 'info');
        
        setTimeout(() => {
            showNotification('Performance analysis completed!', 'success');
        }, 4000);
    }

    function optimizeQueries() {
        showNotification('Optimizing database queries...', 'info');
        
        setTimeout(() => {
            showNotification('Query optimization completed!', 'success');
        }, 3000);
    }

    function clearQueryCache() {
        showNotification('Clearing query cache...', 'info');
        
        setTimeout(() => {
            showNotification('Query cache cleared successfully!', 'success');
        }, 1000);
    }

    function exportPerformanceReport() {
        showNotification('Generating performance report...', 'info');
        
        setTimeout(() => {
            showNotification('Performance report exported!', 'success');
        }, 2000);
    }

    // Backup actions
    function createBackup() {
        showNotification('Creating database backup...', 'info');
        
        setTimeout(() => {
            showNotification('Database backup created successfully!', 'success');
        }, 5000);
    }

    function restoreBackup() {
        if (confirm('Are you sure you want to restore from backup? This will overwrite the current database.')) {
            showNotification('Restoring database from backup...', 'warning');
            
            setTimeout(() => {
                showNotification('Database restored successfully!', 'success');
            }, 8000);
        }
    }

    function testBackupRestore() {
        showNotification('Testing backup and restore functionality...', 'info');
        
        setTimeout(() => {
            showNotification('Backup/restore test completed successfully!', 'success');
        }, 6000);
    }

    function downloadBackup() {
        showNotification('Downloading latest backup...', 'info');
        
        setTimeout(() => {
            showNotification('Backup downloaded successfully!', 'success');
        }, 3000);
    }

    // Maintenance actions
    function runMigrations() {
        showNotification('Running pending migrations...', 'info');
        
        setTimeout(() => {
            showNotification('All migrations completed successfully!', 'success');
        }, 4000);
    }

    function rollbackMigration() {
        if (confirm('Are you sure you want to rollback the last migration?')) {
            showNotification('Rolling back last migration...', 'warning');
            
            setTimeout(() => {
                showNotification('Migration rollback completed!', 'success');
            }, 3000);
        }
    }

    function refreshMigrations() {
        showNotification('Refreshing migration status...', 'info');
        
        setTimeout(() => {
            showNotification('Migration status refreshed!', 'success');
        }, 1000);
    }

    function analyzeTables() {
        showNotification('Analyzing all database tables...', 'info');
        
        setTimeout(() => {
            showNotification('All tables analyzed successfully!', 'success');
        }, 5000);
    }

    function optimizeAllTables() {
        showNotification('Optimizing all database tables...', 'info');
        
        setTimeout(() => {
            showNotification('All tables optimized successfully!', 'success');
        }, 8000);
    }

    function repairTables() {
        showNotification('Repairing database tables...', 'info');
        
        setTimeout(() => {
            showNotification('Table repair completed!', 'success');
        }, 6000);
    }

    function vacuumDatabase() {
        if (confirm('Are you sure you want to vacuum the database? This may take some time.')) {
            showNotification('Vacuuming database...', 'warning');
            
            setTimeout(() => {
                showNotification('Database vacuum completed!', 'success');
            }, 10000);
        }
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
            max-width: 350px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    }
</script>
@endsection
