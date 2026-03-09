<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$logsettings = $this->GetPreference('logsettings', 'E_ALL');
$selected_logsettings = explode(',', $logsettings);

$exceptions = [
    'E_ALL' => 'All Error Types',
    'Fatal error' => 'Fatal Error',
    'Warning' => 'Warning', 
    'Deprecated' => 'Deprecated',
    'Notice' => 'Notice',
    'Error' => 'Error'
];

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_filters_tab.tpl'), null, null, $smarty );
$tpl->assign('selected_logsettings', $selected_logsettings);
$tpl->assign('exceptions', $exceptions);
$tpl->display();

?>
