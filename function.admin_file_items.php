<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$error = 0;
$message = '';

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_file_items.tpl'),null,null,$smarty);

$pagelimit = 50;
$pagenumber = 1;

if (isset($params['pagenumber']) && $params['pagenumber'] !== '') {
    $pagenumber = (int)$params['pagenumber'];
}

$offset = ($pagenumber - 1) * $pagelimit;

// Get selected log source
$selected_log_source = $this->GetPreference('log_source', '');
$available_logs = LogWatch::detectAvailableLogFiles();

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
    
    $total_items = count($all_logs);
    $logs = array_slice($all_logs, $offset, $pagelimit);
} else {
    $total_items = 0;
}

$total_pages = ceil($total_items / $pagelimit);

$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $total_items);
$tpl->assign('total_pages', $total_pages);
$tpl->assign('selected_log_info', $available_logs[$selected_log_source] ?? null);

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