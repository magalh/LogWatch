<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

echo '<div style="display: flex; align-items: center; margin-bottom: 20px;">';
echo '<img src="' . $this->GetModuleURLPath() . '/assets/icon.svg" alt="LogWatch" style="width: 96px; height: 96px; margin-right: 10px;">';
echo '<div>';
echo '<h2 style="margin: 0;">LogWatch</h2>';
echo '<p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">' . $this->Lang('description') . '</p>';
echo '</div>';
echo '</div>';

echo $this->StartTabHeaders();
	echo $this->SetTabHeader('logs', "Logs");
	echo $this->SetTabHeader('settings',"Settings");
echo $this->EndTabHeaders();

echo $this->StartTabContent();
	echo $this->StartTab('logs');
	include(__DIR__.'/function.admin_file_items.php');
	echo $this->EndTab();

	echo $this->StartTab('settings');
	include(__DIR__.'/function.admin_settings_tab.php');
	echo $this->EndTab();
echo $this->EndTabContent();

?>