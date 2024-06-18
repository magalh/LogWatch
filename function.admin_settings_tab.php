<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if( isset($params['submit']) ) {
    $this->SetPreference('logfilepath',$params['logfilepath']);
    $this->SetMessage("Saved");
    $this->RedirectToAdminTab();
}

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty );
$tpl->assign('logfilepath',$logfilepath);
$tpl->assign('root_path',CMS_ROOT_PATH);
$tpl->display();


