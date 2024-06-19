<?php
if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(LogWatch::MANAGE_PERM);

$this->RemovePreference();
?>