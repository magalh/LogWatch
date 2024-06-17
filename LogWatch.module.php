<?php
class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	
	public function GetVersion() { return '1.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return TRUE; }
	public function HasAdmin() { return TRUE; }
	public function VisibleToAdminUser() { return $this->CheckPermission(self::MANAGE_PERM); }
	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'h_magal@hotmail.com'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'extentions'; }
	
	public function InitializeFrontend() {
		$this->RegisterModulePlugin();
		$this->SetParameterType('biz',CLEAN_STRING);
	}

	 public function InitializeAdmin() {
		 $this->SetParameters();
	 }
	
	public function GetHelp() { return @file_get_contents(__DIR__.'/doc/help.inc'); }
	public function GetChangeLog() { return @file_get_contents(__DIR__.'/doc/changelog.inc'); }

	public function getLineIcon($type){
		$themeObject = cms_utils::get_theme_object();
        $icon = '';
        switch($type){
            case 'WARN':
                $icon = $themeObject->DisplayImage('icons/system/warning.gif', 'warning', '', '', 'systemicon');
            break;
            case 'INFO':
                $icon = $themeObject->DisplayImage('icons/system/info.gif', 'info', '', '', 'systemicon');
            break;
            case 'ERROR':
                $icon = $themeObject->DisplayImage('icons/system/stop.gif', 'stop', '', '', 'systemicon');
            break;
            default:
                $icon = '';
        }
        return $icon;
	}
}

?>