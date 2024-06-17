<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

try{

    $logfilepath = ini_get('error_log');
    if (!$logfilepath) {
        $this->SetPreference('logfilepath','/var/log/apache2/error.log');
        throw new LogicException('Unable to determine the PHP error log file location.');
    } else {
        $this->SetPreference('logfilepath',$logfilepath);
    }

} catch (LogicException $e) {
    $error = 1;	
    $message = $e->getMessage();
    audit('',$this->GetName(),$message);
}

?>