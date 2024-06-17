<?php
class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	
	public function GetVersion() { return '1.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return false; }
	public function HasAdmin() { return TRUE; }
	public function VisibleToAdminUser() { return $this->CheckPermission(self::MANAGE_PERM); }
	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'h_magal@hotmail.com'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'siteadmin'; }
	
	 public function InitializeAdmin() {
		 $this->SetParameters();
	 }
	
	public function GetHelp() { return @file_get_contents(__DIR__.'/README.md'); }
	public function GetChangeLog() { return @file_get_contents(__DIR__.'/doc/changelog.inc'); }

}

?>