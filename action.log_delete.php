<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if( isset($params['hid']) && $params['hid'] > 1) {
   $log = LogItem::load_by_id((int)$params['hid']);
   $log->delete();
   $this->SetMessage($this->Lang('log_deleted'));
   $this->RedirectToAdminTab();
}
?>