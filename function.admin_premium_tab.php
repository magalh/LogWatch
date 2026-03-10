<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

// Check if LogWatchPro is installed
$pro_mod = cms_utils::get_module('LogWatchPro');
$pro_installed = ($pro_mod !== false);

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_premium_tab.tpl'), null, null, $smarty);
$tpl->assign('pro_installed', $pro_installed);
$tpl->assign('mod', $this);

if ($pro_installed) {
    $tpl->assign('mod_pro', $pro_mod);
}

$tpl->display();

?>
