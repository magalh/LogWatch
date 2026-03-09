<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$error = 0;
$message = '';

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_hidden_errors.tpl'),null,null,$smarty);

// Get hidden errors from database
$db = $this->GetDb();
$sql = "SELECT h.*, u.username 
        FROM " . cms_db_prefix() . "module_logwatch_hidden h 
        LEFT JOIN " . cms_db_prefix() . "users u ON h.hidden_by = u.user_id 
        ORDER BY h.hidden_date DESC";

$result = $db->Execute($sql);
$hidden_errors = [];

if ($result) {
    while ($row = $result->FetchRow()) {
        $hidden_errors[] = $row;
    }
}

$tpl->assign('message', $message);
$tpl->assign('error', $error);
$tpl->assign('hidden_errors', $hidden_errors);
$tpl->assign('total_count', count($hidden_errors));

$tpl->display();
?>