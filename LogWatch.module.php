<?php
class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	const LOGWATCH_FILE = TMP_CACHE_LOCATION . '/logwatch.log';
	
	public function GetVersion() { return '1.0.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return true;}
	public function HasAdmin() { return TRUE; }
	public function VisibleToAdminUser() { return $this->CheckPermission(self::MANAGE_PERM); }
	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'magal@pixelsolutions.biz'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'siteadmin'; }
	
	public function InitializeAdmin() {
        //$this->CreateParameter('hid',null,$this->Lang('param_hid'));
    }

	 public function InitializeFrontend() {
		$this->RegisterModulePlugin();
	 }

	 public function __construct(){

		parent::__construct();
		$config = \cms_config::get_instance();
        $smarty = \CmsApp::get_instance()->GetSmarty();
        if( !$smarty ) return;

		$plugins_dir = cms_join_path( $this->GetModulePath(), 'lib', 'plugins' );
		$smarty->addPluginsDir($plugins_dir);

    }
	
	public function GetHelp() { return @file_get_contents(__DIR__.'/README.md'); }
	public function GetChangeLog() { return @file_get_contents(__DIR__.'/doc/changelog.inc'); }

}

?>