<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------

class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	const CLEAR_LOGS = 'clear_logs';
	const EXPORT_LOGS = 'export_logs';
	
	public function IsPluginModule() { return true;}
	public function GetVersion() { return '2.2.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
    public function MinimumCMSVersion() { return '2.2.0'; }
	public function HasAdmin() { return TRUE; }

	function VisibleToAdminUser()
    {
        return $this->CheckPermission(self::MANAGE_PERM) || $this->CheckPermission(self::EXPORT_LOGS);
    }

    public function GetAuthor() { return 'Pixel Solutions'; }
    public function GetAuthorEmail() { return 'info@pixelsolutions.biz'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'extensions'; }
	public function GetModuleIcon() { return $this->GetModuleURLPath() . '/assets/icon.svg'; }
	public function GetDependencies() {}
	
	public function InitializeAdmin() {}

	 public function InitializeFrontend() {
		$this->RegisterModulePlugin();
	 }

	 public function __construct(){
		
		// Load composer dependencies
		$autoload_file = cms_join_path($this->GetModulePath(), 'vendor', 'autoload.php');
		if (file_exists($autoload_file)) {
			require_once $autoload_file;
		}
		
		spl_autoload_register( array($this, '_autoloader') );
		
		parent::__construct();

		$smarty = cmsms()->GetSmarty();
        if( !$smarty ) return;

		$plugins_dir = cms_join_path( $this->GetModulePath(), 'lib', 'plugins' );
		$smarty->addPluginsDir($plugins_dir);

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

	public static function detectAvailableLogFiles() {
		$logs = [];
		
		// Virtual host error logs (prioritize these)
		$server_name = $_SERVER['SERVER_NAME'] ?? '';
		if (defined('CMS_ROOT_URL')) {
			$parsed_url = parse_url(CMS_ROOT_URL);
			if (isset($parsed_url['host'])) {
				$server_name = $parsed_url['host'];
			}
		}
		$vhost_patterns = [
			"/var/log/apache2/{$server_name}.error.log",
			"/var/log/apache2/{$server_name}.local.error.log",
			"/var/log/httpd/{$server_name}.error.log",
			"/var/log/nginx/{$server_name}.error.log",
			"/var/log/apache2/{$server_name}_error.log",
			"/var/log/httpd/{$server_name}_error.log"
		];
		
		// Add subdomain-specific patterns if it's a subdomain
		if (strpos($server_name, '.') !== false) {
			$domain_parts = explode('.', $server_name);
			if (count($domain_parts) > 2) {
				// It's a subdomain, add subdomain-specific patterns
				$subdomain = $domain_parts[0];
				$main_domain = implode('.', array_slice($domain_parts, 1));
				
				// Add subdomain-specific log patterns (higher priority)
				array_unshift($vhost_patterns, 
					"/var/log/apache2/{$subdomain}.{$main_domain}.error.log",
					"/var/log/apache2/{$subdomain}_{$main_domain}.error.log",
					"/var/log/httpd/{$subdomain}.{$main_domain}.error.log",
					"/var/log/httpd/{$subdomain}_{$main_domain}.error.log",
					"/var/log/nginx/{$subdomain}.{$main_domain}.error.log"
				);
			}
		}
		
		foreach ($vhost_patterns as $path) {
			if (file_exists($path) && is_readable($path)) {
				$logs['vhost_' . md5($path)] = [
					'name' => 'Virtual Host Log (' . basename($path) . ')',
					'path' => $path,
					'type' => 'server',
					'exists' => true
				];
				break; // Only add first found
			}
		}
		
		// PHP ini error log
		$php_log = ini_get('error_log');
		if ($php_log && $php_log !== 'syslog') {
			$logs['php_ini'] = [
				'name' => 'PHP Error Log (ini)',
				'path' => $php_log,
				'type' => 'server',
				'exists' => file_exists($php_log) && is_readable($php_log)
			];
		}
		
		// Common server error logs
		$root_path = defined('CMS_ROOT_PATH') ? CMS_ROOT_PATH : $_SERVER['DOCUMENT_ROOT'];
		$common_paths = [
			$root_path . '/error_log' => 'CMS Root Error Log',
			dirname($root_path) . '/logs/error_log' => 'Parent Logs Directory',
			'/var/log/apache2/error.log' => 'Apache Error Log',
			'/var/log/httpd/error_log' => 'HTTPd Error Log'
		];
		
		foreach ($common_paths as $path => $name) {
			if (file_exists($path) && is_readable($path)) {
				$key = 'common_' . md5($path);
				if (!isset($logs[$key])) {
					$logs[$key] = [
						'name' => $name,
						'path' => $path,
						'type' => 'server',
						'exists' => true
					];
				}
			}
		}
		
		// Scan home directory log patterns
		$home_patterns = [
			'/home/*/logs/*.log',
			'/home/*/logs/error*.log'
		];
		
		// Add CMS root-relative patterns
		if (defined('CMS_ROOT_PATH')) {
			$root_path = CMS_ROOT_PATH;
			$home_patterns[] = dirname($root_path) . '/logs/*.log';
			$home_patterns[] = dirname($root_path) . '/logs/error*.log';
			$home_patterns[] = dirname(dirname($root_path)) . '/logs/*.log';
			$home_patterns[] = dirname($root_path) . '/logs/error_log';
			$home_patterns[] = dirname($root_path) . '/error_log';
		}
		
		foreach ($home_patterns as $pattern) {
			$files = glob($pattern);
			if ($files) {
				foreach ($files as $path) {
					if (is_readable($path)) {
						$key = 'home_' . md5($path);
						$logs[$key] = [
							'name' => 'Home Log (' . basename($path) . ')',
							'path' => $path,
							'type' => 'server',
							'exists' => true
						];
					}
				}
			}
		}
		
		return $logs;
	}
	
	public function hideError($error_hash, $file, $line, $message, $notes = '')
	{
		if (empty($error_hash) || empty($message)) return false;
		
		$user_id = get_userid();
		$db = $this->GetDb();
		$sql = "INSERT IGNORE INTO " . cms_db_prefix() . "module_logwatch_hidden 
				(error_hash, file_path, line_number, error_message, hidden_by, hidden_date, notes) 
				VALUES (?, ?, ?, ?, ?, NOW(), ?)";
		
		return $db->Execute($sql, [$error_hash, $file ?: null, $line ?: null, $message, $user_id, $notes]);
	}
	
	public function unhideError($error_hash)
	{
		if (empty($error_hash)) return false;
		
		$db = $this->GetDb();
		$sql = "DELETE FROM " . cms_db_prefix() . "module_logwatch_hidden WHERE error_hash = ?";
		return $db->Execute($sql, [$error_hash]);
	}
	
	public function isErrorHidden($log)
	{
		$file = $log->file ?? '';
		$line = $log->line ?? '';
		$description = trim($log->description ?? '');
		$error_hash = md5($file . ':' . $line . ':' . $description);
		
		$db = $this->GetDb();
		$sql = "SELECT id FROM " . cms_db_prefix() . "module_logwatch_hidden WHERE error_hash = ?";
		$result = $db->Execute($sql, [$error_hash]);
		
		return $result && !$result->EOF;
	}
	
	public function getErrorHash($log)
	{
		$file = $log->file ?? '';
		$line = $log->line ?? '';
		$description = trim($log->description ?? '');
		return md5($file . ':' . $line . ':' . $description);
	}
	
	public function getHiddenErrorsCount()
	{
		$db = $this->GetDb();
		$sql = "SELECT COUNT(DISTINCT error_hash) as count FROM " . cms_db_prefix() . "module_logwatch_hidden";
		$result = $db->Execute($sql);
		
		return $result ? (int)$result->fields['count'] : 0;
	}
	
    public function GetHelp() {
        $base_dir = realpath(__DIR__);
        $file = realpath(__DIR__.'/README.md');
        if (!$file || !$base_dir || !is_file($file) || !is_readable($file)) return '';
        if (strpos($file, $base_dir) !== 0) return '';
        if (basename($file) !== 'README.md') return '';
        return @file_get_contents($file);
    }

    public function GetChangeLog() {
        $base_dir = realpath(__DIR__);
        $file = realpath(__DIR__.'/doc/changelog.inc');
        if (!$file || !$base_dir || !is_file($file) || !is_readable($file)) return '';
        if (strpos($file, $base_dir) !== 0) return '';
        if (basename($file) !== 'changelog.inc') return '';
        return @file_get_contents($file);
    }

}

?>