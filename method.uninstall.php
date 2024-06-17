<?php
if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(LogWatch::MANAGE_PERM);
// Remove all preferences for this module
$this->RemovePreference();
?>