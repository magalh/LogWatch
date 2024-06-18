<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$error = 0;
$message = '';

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_logs_tab.tpl'),null,null,$smarty);

$pagelimit = 10;
$pagenumber = 1;

if (isset($params['pagenumber']) && $params['pagenumber'] !== '') {
    $pagenumber = (int)$params['pagenumber'];
}

$offset = ($pagenumber - 1) * $pagelimit;

$query = new LogQuery();
$query->set_limit($pagelimit);
$query->set_offset($offset);
$logs = $query->GetMatches();
$total_items = $query->_totalmatchingrows;
$total_pages = ceil($total_items / $pagelimit);

$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $query->_totalmatchingrows);
$tpl->assign('total_pages', $total_pages);

$tpl->display();

?>