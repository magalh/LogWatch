<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$logwatch_pro = cms_utils::get_module('LogWatchPro');
$pro_available = (is_object($logwatch_pro));
$pro_enabled = $pro_available && $logwatch_pro->IsProEnabled();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_premium.tpl'),null,null,$smarty);
$tpl->assign('pro_available', $pro_available);
$tpl->assign('pro_enabled', $pro_enabled);
$tpl->display();
?>