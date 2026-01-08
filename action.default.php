<?php
if( !defined('CMS_VERSION') ) exit;

// Test error generation for demonstration
if (isset($params['submit_1'])) {
    $uninitialized_variable = $undefinedVariable; // Notice
}
if (isset($params['submit_2'])) {
    $result = 1 / 0; // Warning
}
if (isset($params['submit_3'])) {
    trigger_error('This is a user error', E_USER_ERROR);
}
if (isset($params['submit_4'])) {
    trigger_error('This is a user warning', E_USER_WARNING);
}
if (isset($params['submit_5'])) {
    trigger_error('This is a user notice', E_USER_NOTICE);
}

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('default.tpl'),null,null,$smarty);
$tpl->display();
?>