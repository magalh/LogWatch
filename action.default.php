<?php
if( !defined('CMS_VERSION') ) exit;

$logger = new LogIt;
if (isset($params['submit_1'])) {
    $logger::triggerPhpErrors(1);
}
if (isset($params['submit_2'])) {
    $logger::triggerPhpErrors(2);
}
if (isset($params['submit_3'])) {
    $logger::triggerPhpErrors(3);
}
if (isset($params['submit_4'])) {
    $logger::triggerPhpErrors(4);
}
if (isset($params['submit_5'])) {
    $logger::triggerPhpErrors(5);
}
if (isset($params['submit_6'])) {
    $logger::triggerPhpErrors(6);
}
if (isset($params['submit_7'])) {
    $logger::triggerPhpErrors(7);
}
if (isset($params['submit_8'])) {
    $logger::triggerPhpErrors(8);
}

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('default.tpl'),null,null,$smarty);
$tpl->display();
?>