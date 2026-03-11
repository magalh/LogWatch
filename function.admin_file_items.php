<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$error = 0;
$message = '';

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_file_items.tpl'),null,null,$smarty);

// Get filter settings for display
$logsettings_pref = $this->GetPreference('logsettings', 'E_ALL');
$selected_logsettings = explode(',', $logsettings_pref);
$exceptions = [
    'E_ALL' => 'All Errors',
    'Fatal error' => 'Fatal Error',
    'Warning' => 'Warning',
    'Notice' => 'Notice',
    'Deprecated' => 'Deprecated'
];

$pagelimit = 50;
$pagenumber = 1;

if (isset($params['pagenumber']) && $params['pagenumber'] !== '') {
    $pagenumber = (int)$params['pagenumber'];
}

// Get view mode preference
$view_mode = 'grouped'; // Default to grouped
if (isset($params['view_mode'])) {
    $view_mode = $params['view_mode'];
    $this->SetPreference('view_mode', $view_mode);
} else {
    $view_mode = $this->GetPreference('view_mode', 'grouped');
}

$offset = ($pagenumber - 1) * $pagelimit;

// Get selected log source
$selected_log_source = $this->GetPreference('log_source', '');
$available_logs = LogWatch::detectAvailableLogFiles();

// Handle manual log path
if ($selected_log_source === 'manual') {
    $manual_path = $this->GetPreference('manual_log_path', '');
    if (!empty($manual_path)) {
        $available_logs['manual'] = [
            'name' => 'Manual Log Path',
            'path' => $manual_path,
            'type' => 'manual',
            'exists' => file_exists($manual_path) && is_readable($manual_path)
        ];
    }
}

// Auto-select first available if none selected
if (empty($selected_log_source) || !isset($available_logs[$selected_log_source])) {
    $first_available = array_key_first($available_logs);
    if ($first_available) {
        $selected_log_source = $first_available;
        $this->SetPreference('log_source', $selected_log_source);
    }
}

// Get filter settings
$logsettings = $this->GetPreference('logsettings', 'E_ALL');
$selected_filters = explode(',', $logsettings);
$show_all = in_array('E_ALL', $selected_filters);

$logs = [];
if ($selected_log_source && isset($available_logs[$selected_log_source])) {
    $log_file_path = $available_logs[$selected_log_source]['path'];
    $logQuery = new FileQuery($log_file_path);
    $all_logs = $logQuery->parseLogFile();
    
    // Apply error type filter
    if (!$show_all && !empty($selected_filters)) {
        $all_logs = array_filter($all_logs, function($log) use ($selected_filters) {
            return in_array($log->type, $selected_filters) || 
                   in_array('E_' . strtoupper($log->type), $selected_filters);
        });
    }
    
    // Filter out hidden errors
    $all_logs = array_filter($all_logs, function($log) {
        return !$this->isErrorHidden($log);
    });
    
    // Handle view mode
    if ($view_mode === 'grouped') {
        // Group errors
        $grouped_logs = FileQuery::groupErrors($all_logs);
        $total_items = count($grouped_logs);
        
        // Paginate groups
        $grouped_logs = array_slice($grouped_logs, $offset, $pagelimit);
        $tpl->assign('grouped_logs', $grouped_logs);
    } else {
        // List view (original)
        $total_items = is_array($all_logs) ? count($all_logs) : 0;
        $logs = is_array($all_logs) ? array_slice($all_logs, $offset, $pagelimit) : [];
        $tpl->assign('logs', $logs);
    }
} else {
    $total_items = 0;
    if ($view_mode === 'grouped') {
        $tpl->assign('grouped_logs', []);
    } else {
        $tpl->assign('logs', []);
    }
}

$total_pages = ceil($total_items / $pagelimit);

$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('view_mode', $view_mode);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $total_items);
$tpl->assign('total_pages', $total_pages);
$tpl->assign('selected_log_info', $available_logs[$selected_log_source] ?? null);
$tpl->assign('exceptions', $exceptions);
$tpl->assign('selected_logsettings', $selected_logsettings);

// Check if Pro is enabled
$pro_mod = cms_utils::get_module('LogWatchPro');
$pro_installed = is_object($pro_mod);
$pro_enabled = $pro_installed && $pro_mod->IsProEnabled();
$tpl->assign('pro_installed', $pro_installed);
$tpl->assign('pro_enabled', $pro_enabled);

$clear_logs = false;
if( $this->CheckPermission(LogWatch::CLEAR_LOGS) ) {
    $clear_logs = true;
    $tpl->assign('clear_logs', $clear_logs);
}
$export_logs = false;
if( $this->CheckPermission(LogWatch::EXPORT_LOGS) ) {
    $export_logs = true;
    $tpl->assign('export_logs', $export_logs);
}

$tpl->display();

?>
