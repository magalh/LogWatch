<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------

if( !defined('CMS_VERSION') ) exit;
$this->RemovePermission(LogWatch::MANAGE_PERM);
$this->RemovePermission(LogWatch::EXPORT_LOGS);

$this->RemovePreference();

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
\LogWatch\ModuleTracker::track($this->GetName(), 'uninstall', CMS_VERSION, $this->GetVersion());
?>