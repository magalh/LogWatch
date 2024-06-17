<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$startelement = 0;
$pagenum = 1;
$agelimit = -1;
$pagelimit = 10000;
$thispage = 1;

if ( isset($params['pagenum']) ) $thispage = (int)$params['pagenum'];

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_logs_tab.tpl'),null,null,$smarty);

$error = null;
$message = null;

try{

    $logfilepath = $this->GetPreference('logfilepath');

    // Debugging output
    if (!file_exists($logfilepath)) {
        throw new LogicException($this->Lang('log_file_does_not_exist',$logfilepath));
    }
    if (!is_readable($logfilepath)) {
        throw new LogicException($this->Lang('log_file_is_not_readable',$logfilepath));
    }
    if (!is_writable($logfilepath)) {
        throw new LogicException($this->Lang('log_file_is_not_writable',$logfilepath));
    }

    // Read the log file and parse the contents
    $logQuery = new LogQuery($logfilepath);
    $logs = $logQuery->parseLogFile();

    $matchcount = count($logs);
    $pagelimit = 10; // Define your page limit
    $thispage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate page variables
    $npages = (int)($matchcount / $pagelimit);
    if ($matchcount % $pagelimit > 0) $npages++;
    $startoffset = ($thispage - 1) * $pagelimit;

    // Slice logs to display only the current page logs
    $logs = array_slice($logs, $startoffset, $pagelimit);


} catch (LogicException $e) {
    $error = 1;	
    $message = $e->getMessage();
}

$tpl->assign('logfilepath',$logfilepath);
$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->display();