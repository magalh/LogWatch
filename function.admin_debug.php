<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

// Handle test error triggers
if (isset($params['submit_1'])) {
    $uninitialized_variable = $undefinedVariable; // Notice
}
if (isset($params['submit_2'])) {
    $result = 1 / 0; // Warning
}
if (isset($params['submit_3'])) {
    trigger_error('This is a user error', E_USER_ERROR);
}
if (isset($params['submit_4'])) {
    trigger_error('This is a user warning', E_USER_WARNING);
}
if (isset($params['submit_5'])) {
    trigger_error('This is a user notice', E_USER_NOTICE);
}

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_debug.tpl'), null, null, $smarty);

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
$tpl->display();
?>
