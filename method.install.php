<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

$logfilepath = ini_get('error_log');
if (!$logfilepath) {
    throw new LogicException('Unable to determine the PHP error log file location.');
    $this->SetPreference('logfilepath','/var/log/apache2/error.log');
} else {
    $this->SetPreference('logfilepath',$logfilepath);
}


?>