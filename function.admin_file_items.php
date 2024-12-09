<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$error = 0;
$message = '';

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_file_items.tpl'),null,null,$smarty);

$pagelimit = 50;
$pagenumber = 1;

if (isset($params['pagenumber']) && $params['pagenumber'] !== '') {
    $pagenumber = (int)$params['pagenumber'];
}

$offset = ($pagenumber - 1) * $pagelimit;

$logs = [];
$logQuery = new FileQuery();
$logs = $logQuery->parseLogFile();
$logs = array_slice($logs, $offset, $pagelimit);

$total_items = count($logs);
$total_pages = ceil($total_items / $pagelimit);

$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $total_items);
$tpl->assign('total_pages', $total_pages);

$clear_logs = false;
if( $this->CheckPermission(LogWatch::CLEAR_LOGS) ) {
    $clear_logs = true;
    $tpl->assign('clear_logs', $clear_logs);
}
$export_logs = false;
if( $this->CheckPermission(LogWatch::EXPORT_LOGS) ) {
    $export_logs = true;
    $tpl->assign('export_logs', $export_logs);
}

$tpl->display();

?>