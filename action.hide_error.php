<?php
if (!defined('CMS_VERSION')) exit;
if (!$this->CheckPermission('manage_LogWatch')) return;

if (isset($params['hide_error'])) {
    $error_hash = $params['error_hash'] ?? '';
    $file = $params['file'] ?? '';
    $line = (int)($params['line'] ?? 0);
    $message = $params['message'] ?? '';
    $notes = $params['notes'] ?? '';
    
    if (!empty($error_hash) && $this->hideErrorComplete($error_hash, $file, $line, $message, $notes)) {
        $this->SetMessage('Error marked as fixed');
    } else {
        $this->SetError('Failed to hide error');
    }
    
    $this->RedirectToAdminTab();
}

if (isset($params['unhide_error'])) {
    $error_hash = $params['error_hash'] ?? '';
    
    if ($this->unhideError($error_hash)) {
        $this->SetMessage('Error unhidden');
    } else {
        $this->SetError('Failed to unhide error');
    }
    
    $this->RedirectToAdminTab();
}
?>