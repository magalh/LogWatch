<?php
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

echo '<div style="display: flex; align-items: center; margin-bottom: 20px;">';
echo '<img src="' . $this->GetModuleURLPath() . '/assets/icon.svg" alt="LogWatch" style="width: 96px; height: 96px; margin-right: 10px;">';
echo '<div>';
echo '<h2 style="margin: 0;">LogWatch</h2>';
echo '<p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">' . $this->Lang('admindescription') . '</p>';
echo '</div>';
echo '</div>';

echo $this->StartTabHeaders();
	if ($has_valid_log_source) {
		echo $this->SetTabHeader('logs', "Logs");
		echo $this->SetTabHeader('filters', "Filters");
	}
	echo $this->SetTabHeader('settings',"Settings");
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
echo $this->EndTabContent();

?>