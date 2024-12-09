<?php
class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	const CLEAR_LOGS = 'clear_logs';
	const EXPORT_LOGS = 'export_logs';

	const LOGWATCH_FILE = TMP_CACHE_LOCATION . '/logwatch.cms';
	
	public function GetVersion() { return '1.4.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return true;}
	public function HasAdmin() { return TRUE; }

	function VisibleToAdminUser()
    {
        return $this->CheckPermission(self::MANAGE_PERM) || $this->CheckPermission(self::CLEAR_LOGS) ||
            $this->CheckPermission(self::EXPORT_LOGS);
    }

	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'magal@pixelsolutions.biz'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'siteadmin'; }
	
	public function InitializeAdmin() {
        //$this->CreateParameter('hid',null,$this->Lang('param_hid'));
    }

	 public function InitializeFrontend() {
		$this->RegisterModulePlugin();
		$this->RegisterEvents();
		$this->initLogIt();
	 }

	 public function __construct(){
		
		spl_autoload_register( array($this, '_autoloader') );
		
		parent::__construct();

		$config = \cms_config::get_instance();
        $smarty = \CmsApp::get_instance()->GetSmarty();
        if( !$smarty ) return;

		$plugins_dir = cms_join_path( $this->GetModulePath(), 'lib', 'plugins' );
		$smarty->addPluginsDir($plugins_dir);
		$smarty->registerClass('LogIt', 'LogIt');

    }

	private function _autoloader($classname)
	{
		$parts = explode('\\', $classname);
		$classname = end($parts);
		
		$fn = cms_join_path(
			$this->GetModulePath(),
			'lib',
			'class.' . $classname . '.php'
		);
		
		if(file_exists($fn))
		{
			require_once($fn);
		}

	}

	private static $logItInitialized = false;

	private function ensureLogFileExists() {
		if (!file_exists(self::LOGWATCH_FILE)) {
			// Create the file with write permissions
			$handle = @fopen(self::LOGWATCH_FILE, 'w');
			if ($handle) {
				fclose($handle);
				// Only try to chmod on non-Windows systems
				if (DIRECTORY_SEPARATOR !== '\\') {
					@chmod(self::LOGWATCH_FILE, 0644);
				}
			} else {
				throw new \RuntimeException("Failed to create log file: " . self::LOGWATCH_FILE);
			}
		}
	
		// Verify the file is writable
		if (!is_writable(self::LOGWATCH_FILE)) {
			throw new \RuntimeException("Log file is not writable: " . self::LOGWATCH_FILE);
		}
	}

	private function initLogIt() {
        if (!self::$logItInitialized) {

			// Ensure log file exists before initializing
            $this->ensureLogFileExists();

            // Include the LogIt class file
            require_once(cms_join_path($this->GetModulePath(), 'lib', 'class.LogIt.php'));

            // Initialize LogIt to set error and shutdown handlers
            new LogIt();

            self::$logItInitialized = true;
        }
    }

	function DoEvent($originator, $eventname, &$params) {
		if ($originator == 'Core' && $eventname == 'ContentPostRender')
		{
			$this->initLogIt();
		}
	}

	public function RegisterEvents()
    {
        $this->AddEventHandler('Core', 'ContentPostRender', false);
    }
	
	public function GetHelp() {
        return @file_get_contents(__DIR__.'/README.md');
    }

    public function GetChangeLog() {
        return @file_get_contents(__DIR__.'/doc/changelog.inc');
    }

}

?>