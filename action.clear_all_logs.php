<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;
if( !$this->CheckPermission(LogWatch::CLEAR_LOGS) ) return;

try {
   // Truncate or recreate the log file
   $logFilePath = LogWatch::LOGWATCH_FILE;
   $handle = @fopen($logFilePath, 'w');
   if ($handle) {
       ftruncate($handle, 0);
       fclose($handle);
       
       // Add success message
       $this->SetMessage($this->Lang('logs_cleared_success'));
   } else {
       $this->SetError($this->Lang('error_clearing_logs'));
   }
} catch (Exception $e) {
   $this->SetError($this->Lang('error_clearing_logs'));
}

$this->RedirectToAdminTab();

?>