<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$logwatch_pro = cms_utils::get_module('LogWatchPro');
$pro_available = (is_object($logwatch_pro));
$pro_enabled = $pro_available && $logwatch_pro->IsProEnabled();
$pro_active = $pro_available && $logwatch_pro->GetPreference('logwatchpro_active', '1') == '1';

$available_logs = LogWatch::detectAvailableLogFiles();
$selected_log_source = $this->GetPreference('log_source', '');
$manual_log_path = $this->GetPreference('manual_log_path', '');

// Add manual log to available logs if configured
if ($selected_log_source === 'manual' && !empty($manual_log_path)) {
    $available_logs['manual'] = [
        'name' => 'Manual Log Path',
        'path' => $manual_log_path,
        'type' => 'manual',
        'exists' => file_exists($manual_log_path) && is_readable($manual_log_path)
    ];
}

$selected_log_info = $available_logs[$selected_log_source] ?? null;
$manual_log_error = ($selected_log_source === 'manual' && !empty($manual_log_path) && (!file_exists($manual_log_path) || !is_readable($manual_log_path)));

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty);
$tpl->assign('available_logs', $available_logs);
$tpl->assign('selected_log_source', $selected_log_source);
$tpl->assign('manual_log_path', $manual_log_path);
$tpl->assign('selected_log_info', $selected_log_info);
$tpl->assign('manual_log_error', $manual_log_error);
$tpl->assign('pro_available', $pro_available);
$tpl->assign('pro_enabled', $pro_enabled);
$tpl->assign('pro_active', $pro_active);
$tpl->display();

?>
