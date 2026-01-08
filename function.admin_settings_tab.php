<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if( isset($params['submit']) ) {
    $logsettings = isset($params['logsettings']) ? $params['logsettings'] : [];
    $this->SetPreference('logsettings', implode(',', $logsettings));
    
    $selected_log_source = isset($params['log_source']) ? $params['log_source'] : 'logwatch';
    $this->SetPreference('log_source', $selected_log_source);
    
    $this->SetMessage("Saved");
    $this->RedirectToAdminTab();
}

$logsettings = $this->GetPreference('logsettings', 'E_ALL');
$selected_logsettings = explode(',', $logsettings);
$selected_log_source = $this->GetPreference('log_source', '');

// Auto-select first available log if none selected
if (empty($selected_log_source)) {
    $available_logs = LogWatch::detectAvailableLogFiles();
    $first_available = array_key_first($available_logs);
    if ($first_available) {
        $selected_log_source = $first_available;
        $this->SetPreference('log_source', $selected_log_source);
    }
}

$exceptions = [
    'E_ALL' => 'All Error Types',
    'Fatal error' => 'Fatal Error',
    'Warning' => 'Warning', 
    'Deprecated' => 'Deprecated',
    'Notice' => 'Notice',
    'Error' => 'Error'
];

$available_logs = LogWatch::detectAvailableLogFiles();

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty );
$tpl->assign('selected_logsettings', $selected_logsettings);
$tpl->assign('exceptions', $exceptions);
$tpl->assign('available_logs', $available_logs);
$tpl->assign('selected_log_source', $selected_log_source);
$tpl->display();

?>