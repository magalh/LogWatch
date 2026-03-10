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
        
        // Handle Pro enable/disable
        $pro_mod = cms_utils::get_module('LogWatchPro');
        if (is_object($pro_mod)) {
            $pro_active = isset($params['pro_active']) && $params['pro_active'] == '1';
            $pro_mod->SetPreference('logwatchpro_active', $pro_active ? '1' : '0');
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



// LogWatchPro status
$pro_mod = cms_utils::get_module('LogWatchPro');
$pro_installed = is_object($pro_mod);
$pro_enabled = $pro_installed && $pro_mod->IsProEnabled();
// Display header
$smarty = cmsms()->GetSmarty();
$header_tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_header.tpl'), null, null, $smarty);
$header_tpl->assign('pro_enabled', $pro_enabled);
$header_tpl->display();

echo $this->StartTabHeaders();
	if ($has_valid_log_source) {
		echo $this->SetTabHeader('logs', $this->Lang('tab_logs'));
		$hidden_count = $this->getHiddenErrorsCount();
		$hidden_label = $this->Lang('tab_hidden') . ($hidden_count > 0 ? " ({$hidden_count})" : "");
		echo $this->SetTabHeader('hidden', $hidden_label);
	}
	echo $this->SetTabHeader('settings', $this->Lang('tab_settings'));
	
	if ($pro_installed && $pro_enabled) {
		echo $this->SetTabHeader('integrations', 'Integrations');
		echo $this->SetTabHeader('analytics', 'Analytics');
		echo $this->SetTabHeader('notifications', 'Notification History');
	}
	
	if ($pro_installed) {
		echo $this->SetTabHeader('license', 'License');
	} else {
		echo $this->SetTabHeader('premium', $this->Lang('tab_premium'));
	}
	
	$config = cms_config::get_instance();
	if ($this->CheckPermission(LogWatch::MANAGE_PERM) && isset($config['logwatch_debug_mode']) && $config['logwatch_debug_mode'] == '1') {
		echo $this->SetTabHeader('debug', 'Debug');
	}
echo $this->EndTabHeaders();

echo $this->StartTabContent();
	if ($has_valid_log_source) {
		echo $this->StartTab('logs');
		include(__DIR__.'/function.admin_file_items.php');
		echo $this->EndTab();

		echo $this->StartTab('hidden');
		include(__DIR__.'/function.admin_hidden_errors.php');
		echo $this->EndTab();
	}

	echo $this->StartTab('settings');
	include(__DIR__.'/function.admin_settings_tab.php');
	echo $this->EndTab();
	
	// LogWatchPro tabs
	if ($pro_installed && $pro_enabled) {
		echo $this->StartTab('integrations', $params);
		include($pro_mod->GetModulePath() . '/function.admin_integrations.php');
		echo $this->EndTab();
		
		echo $this->StartTab('analytics', $params);
		include($pro_mod->GetModulePath() . '/function.admin_analytics.php');
		echo $this->EndTab();
		
		echo $this->StartTab('notifications', $params);
		include($pro_mod->GetModulePath() . '/function.admin_notifications.php');
		echo $this->EndTab();
	}
	
	if ($pro_installed) {
		echo $this->StartTab('license', $params);
		include($pro_mod->GetModulePath() . '/function.admin_license.php');
		echo $this->EndTab();
	} else {
		echo $this->StartTab('premium');
		include(__DIR__.'/function.admin_premium_tab.php');
		echo $this->EndTab();
	}
	
	$config = cms_config::get_instance();
	if ($this->CheckPermission(LogWatch::MANAGE_PERM) && isset($config['logwatch_debug_mode']) && $config['logwatch_debug_mode'] == '1') {
		echo $this->StartTab('debug');
		include(__DIR__.'/function.admin_debug.php');
		echo $this->EndTab();
	}
echo $this->EndTabContent();

?>
