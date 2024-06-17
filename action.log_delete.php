<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if (isset($params['hid'])) {
   $lineNumber = (int)$params['hid'];
   $logFilePath = $this->GetPreference('logfilepath');
   $logFile = new LogFile($logFilePath);
   $success = $logFile->removeLine($lineNumber);
   if ($success) {
       $this->SetMessage($this->Lang('log_line_deleted'));
   } else {
      $error = $logFile->getLastError();
      $this->SetMessage($error, 'error');
   }
   $this->RedirectToAdminTab();
}
?>