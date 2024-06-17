<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

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

} catch (LogicException $e) {
    $error = 1;	
    $message = $e->getMessage();
}

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_logs_tab.tpl'),null,null,$smarty);

// Read the log file and parse the contents
$logQuery = new LogQuery($logfilepath);
$logs = $logQuery->parseLogFile();

$matchcount = count($logs);
$pagelimit = 25;
if( isset( $params['pagenumber'] ) && $params['pagenumber'] !== '' ) {
    $pagenumber = (int)$params['pagenumber'];
    $startelement = ($pagenumber-1) * $pagelimit;
  }

// Calculate page variables
$npages = (int)($matchcount / $pagelimit);
if ($matchcount % $pagelimit > 0) $npages++;
$startelement = ($pagenumber - 1) * $pagelimit;

// Slice logs to display only the current page logs
$logs = array_slice($logs, $startelement, $pagelimit);

$tpl->assign('logfilepath',$logfilepath);
$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->assign('startelement', $startelement);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $matchcount);
$tpl->assign('total_pages', $npages);

$tpl->display();