<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_premium.tpl'),null,null,$smarty);
$tpl->display();
?>