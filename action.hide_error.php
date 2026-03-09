<?php
if (!defined('CMS_VERSION')) exit;
if (!$this->CheckPermission('manage_LogWatch')) return;

if (isset($params['hide_error'])) {
    $error_hash = $params['error_hash'] ?? '';
    $file = $params['file'] ?? '';
    $line = (int)($params['line'] ?? 0);
    $message = $params['message'] ?? '';
    $notes = $params['notes'] ?? '';
    
    if ($this->hideError($error_hash, $file, $line, $message, $notes)) {
        $this->SetMessage($this->Lang('error_hidden'));
    } else {
        $this->SetError($this->Lang('error_hide_failed'));
    }
    
    $this->RedirectToAdminTab();
}

if (isset($params['unhide_error'])) {
    $error_hash = $params['error_hash'] ?? '';
    
    if ($this->unhideError($error_hash)) {
        $this->SetMessage($this->Lang('error_unhidden'));
    } else {
        $this->SetError($this->Lang('error_unhide_failed'));
    }
    
    $this->RedirectToAdminTab('hidden');
}
?>