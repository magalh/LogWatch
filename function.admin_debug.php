<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$logwatch_pro = cms_utils::get_module('LogWatchPro');

// Handle clear preferences
if (isset($params['clear_all_prefs'])) {
    $basic_prefs = [
        'log_source',
        'manual_log_path',
        'logsettings'
    ];
    
    $pro_prefs = [
        'logwatchpro_license_key',
        'logwatchpro_enabled',
        'logwatchpro_license_verified',
        'logwatchpro_slack_enabled',
        'logwatchpro_slack_webhook',
        'logwatchpro_discord_enabled',
        'logwatchpro_discord_webhook',
        'logwatchpro_email_enabled',
        'logwatchpro_email_to',
        'logwatchpro_cooldown_minutes',
        'logwatchpro_max_per_hour',
        'logwatchpro_notify_types',
        'logwatchpro_error_grouping_enabled',
        'logwatchpro_performance_metrics_enabled',
        'logwatchpro_scheduled_reports_enabled'
    ];
    
    foreach ($basic_prefs as $pref) {
        $this->RemovePreference($pref);
    }
    if ($logwatch_pro) {
        foreach ($pro_prefs as $pref) {
            $logwatch_pro->RemovePreference($pref);
        }
    }
    $this->SetMessage('All preferences cleared');
    $this->RedirectToAdminTab('debug');
    return;
}

// Handle test error triggers
if (isset($params['submit_1'])) {
    $uninitialized_variable = $undefinedVariable; // Notice
    sleep(1); // Wait for log to be written
    $this->SetMessage('Notice triggered - check logs tab');
    $this->RedirectToAdminTab('logs');
}
if (isset($params['submit_2'])) {
    @($result = 1 / 0); // Warning
    sleep(1); // Wait for log to be written
    $this->SetMessage('Warning triggered - check logs tab');
    $this->RedirectToAdminTab('logs');
}
if (isset($params['submit_3'])) {
    trigger_error('This is a user error', E_USER_ERROR);
}
if (isset($params['submit_4'])) {
    trigger_error('This is a user warning', E_USER_WARNING);
    sleep(1); // Wait for log to be written
    $this->SetMessage('User warning triggered - check logs tab');
    $this->RedirectToAdminTab('logs');
}
if (isset($params['submit_5'])) {
    trigger_error('This is a user notice', E_USER_NOTICE);
    sleep(1); // Wait for log to be written
    $this->SetMessage('User notice triggered - check logs tab');
    $this->RedirectToAdminTab('logs');
}
if (isset($params['submit_fatal'])) {
    trigger_error('This is a test fatal error for notifications', E_USER_ERROR);
}

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_debug.tpl'), null, null, $smarty);

// Basic preferences
$basic_prefs = [
    'log_source',
    'manual_log_path',
    'logsettings'
];

$basic_prefs_data = [];
foreach ($basic_prefs as $pref) {
    $val = $this->GetPreference($pref, '');
    $basic_prefs_data[] = ['name' => $pref, 'value' => $val];
}

// Pro preferences
$pro_prefs = [
    'logwatchpro_license_key',
    'logwatchpro_enabled',
    'logwatchpro_license_verified',
    'logwatchpro_slack_enabled',
    'logwatchpro_slack_webhook',
    'logwatchpro_discord_enabled',
    'logwatchpro_discord_webhook',
    'logwatchpro_email_enabled',
    'logwatchpro_email_to',
    'logwatchpro_cooldown_minutes',
    'logwatchpro_max_per_hour',
    'logwatchpro_history_retention_days',
    'logwatchpro_notify_types',
    'logwatchpro_error_grouping_enabled',
    'logwatchpro_performance_metrics_enabled',
    'logwatchpro_scheduled_reports_enabled'
];

$pro_prefs_data = [];
if ($logwatch_pro) {
    foreach ($pro_prefs as $pref) {
        $val = $logwatch_pro->GetPreference($pref, '');
        if (strpos($pref, 'license_key') !== false || strpos($pref, 'webhook') !== false) {
            $val = $val ? '***' : '';
        }
        $pro_prefs_data[] = ['name' => $pref, 'value' => $val];
    }
}

// Debug information
$debug_info = [
    'module_version' => $this->GetVersion(),
    'cms_version' => CMS_VERSION,
    'php_version' => PHP_VERSION,
    'selected_log_source' => $this->GetPreference('log_source', 'none'),
    'manual_log_path' => $this->GetPreference('manual_log_path', ''),
    'available_logs' => LogWatch::detectAvailableLogFiles(),
    'hidden_errors_count' => $this->getHiddenErrorsCount(),
    'error_log_ini' => ini_get('error_log'),
    'display_errors' => ini_get('display_errors'),
    'log_errors' => ini_get('log_errors'),
];

$tpl->assign('debug_info', $debug_info);
$tpl->assign('basic_prefs', $basic_prefs_data);
$tpl->assign('pro_prefs', $pro_prefs_data);
$tpl->assign('pro_installed', $logwatch_pro !== false);
$tpl->assign('clear_url', $this->create_url('m1_', 'defaultadmin', '', ['active_tab' => 'debug', 'clear_all_prefs' => '1']));
$tpl->display();
?>
