<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

// Handle form submissions
if( isset($params['submit']) ) {
    // Settings tab submission
    if (isset($params['log_source'])) {
        $selected_log_source = $params['log_source'];
        $this->SetPreference('log_source', $selected_log_source);
        
        // Handle manual log path
        if ($selected_log_source === 'manual' && isset($params['manual_log_path'])) {
            $manual_path = trim($params['manual_log_path']);
            $this->SetPreference('manual_log_path', $manual_path);
        }
        
        $this->SetMessage($this->Lang('settings_saved'));
        $this->RedirectToAdminTab();
    }
    
    // Filters tab submission
    if (isset($params['logsettings'])) {
        $logsettings = $params['logsettings'];
        $this->SetPreference('logsettings', implode(',', $logsettings));
        
        $this->SetMessage($this->Lang('settings_saved'));
        $this->RedirectToAdminTab();
    }
}

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

$has_valid_log_source = !empty($available_logs) && isset($available_logs[$selected_log_source]) && $available_logs[$selected_log_source]['exists'];

// Display header
$smarty = cmsms()->GetSmarty();
$header_tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_header.tpl'), null, null, $smarty);
$header_tpl->display();

echo $this->StartTabHeaders();
	if ($has_valid_log_source) {
		echo $this->SetTabHeader('logs', $this->Lang('tab_logs'));
		echo $this->SetTabHeader('filters', $this->Lang('tab_filters'));
	}
	echo $this->SetTabHeader('settings', $this->Lang('tab_settings'));
	echo $this->SetTabHeader('premium', $this->Lang('tab_premium'));
echo $this->EndTabHeaders();

echo $this->StartTabContent();
	if ($has_valid_log_source) {
		echo $this->StartTab('logs');
		include(__DIR__.'/function.admin_file_items.php');
		echo $this->EndTab();

		echo $this->StartTab('filters');
		include(__DIR__.'/function.admin_filters_tab.php');
		echo $this->EndTab();
	}

	echo $this->StartTab('settings');
	include(__DIR__.'/function.admin_settings_tab.php');
	echo $this->EndTab();
	
	echo $this->StartTab('premium');
	include(__DIR__.'/function.admin_premium_tab.php');
	echo $this->EndTab();
echo $this->EndTabContent();

?>