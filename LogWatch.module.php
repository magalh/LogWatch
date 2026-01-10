<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#          see /LogWatch/README.md or <http://www.gnu.org/licenses/gpl-2.0.html>
#---------------------------------------------------------------------------------------------------
# CMS Made Simple(TM) is (c) CMS Made Simple Foundation 2004-2020 (info@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple. You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#---------------------------------------------------------------------------------------------------

class LogWatch extends CMSModule
{
	const MANAGE_PERM = 'manage_LogWatch';
	const CLEAR_LOGS = 'clear_logs';
	const EXPORT_LOGS = 'export_logs';
	
	public function IsPluginModule() { return true;}
	public function GetVersion() { return '2.1.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
    public function MinimumCMSVersion() { return '2.2.0'; }
	public function HasAdmin() { return TRUE; }

	function VisibleToAdminUser()
    {
        return $this->CheckPermission(self::MANAGE_PERM) || $this->CheckPermission(self::EXPORT_LOGS);
    }

	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'magal@pixelsolutions.biz'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'siteadmin'; }
	public function GetModuleIcon() { return $this->GetModuleURLPath() . '/assets/icon.svg'; }
	
	public function InitializeAdmin() {
        //$this->CreateParameter('hid',null,$this->Lang('param_hid'));
    }

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

		$config = \cms_config::get_instance();
        $smarty = \CmsApp::get_instance()->GetSmarty();
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