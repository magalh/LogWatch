<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if( isset($params['submit']) ) {
    $logsettings = isset($params['logsettings']) ? $params['logsettings'] : [];
    $this->SetPreference('logsettings', implode(',', $logsettings));
    $this->SetMessage("Saved");
    $this->RedirectToAdminTab();
}

$logsettings = $this->GetPreference('logsettings', '');
$selected_logsettings = explode(',', $logsettings);

$exceptions = [
    'E_ALL' => 'E_ALL',
    'E_ERROR' => 'E_ERROR',
    'E_WARNING' => 'E_WARNING',
    'E_PARSE' => 'E_PARSE',
    'E_NOTICE' => 'E_NOTICE',
    'E_CORE_ERROR' => 'E_CORE_ERROR',
    'E_CORE_WARNING' => 'E_CORE_WARNING',
    'E_COMPILE_ERROR' => 'E_COMPILE_ERROR',
    'E_COMPILE_WARNING' => 'E_COMPILE_WARNING',
    'E_USER_ERROR' => 'E_USER_ERROR',
    'E_USER_WARNING' => 'E_USER_WARNING',
    'E_USER_NOTICE' => 'E_USER_NOTICE',
    'E_STRICT' => 'E_STRICT',
    'E_RECOVERABLE_ERROR' => 'E_RECOVERABLE_ERROR',
    'E_DEPRECATED' => 'E_DEPRECATED',
    'E_USER_DEPRECATED' => 'E_USER_DEPRECATED'
];

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty );
$tpl->assign('selected_logsettings', $selected_logsettings);
$tpl->assign('exceptions', $exceptions);
$tpl->display();


?>