<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#          see /LogWatch/README.md or <http://www.gnu.org/licenses/gpl-2.0.html>
#---------------------------------------------------------------------------------------------------
# CMS Made Simple(TM) is (c) CMS Made Simple Foundation 2004-2020 (info@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple. You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#---------------------------------------------------------------------------------------------------

function smarty_modifier_typeicon($type) {
    $config = cms_config::get_instance();
    $base_url = $config['admin_url'] . '/themes/OneEleven/images/icons/system/';
    $extra_url = $config['admin_url'] . '/themes/OneEleven/images/icons/extra/';
    
    $icons = [
        'Fatal error' => '<img src="' . $base_url . 'stop.gif" alt="Fatal Error" title="Fatal Error" style="width:16px;height:16px;">',
        'Warning' => '<img src="' . $base_url . 'warning.gif" alt="Warning" title="Warning" style="width:16px;height:16px;">', 
        'Notice' => '<img src="' . $base_url . 'info.gif" alt="Notice" title="Notice" style="width:16px;height:16px;">',
        'Deprecated' => '<img src="' . $extra_url . 'yellow.gif" alt="Deprecated" title="Deprecated" style="width:16px;height:16px;">',
        'Error' => '<img src="' . $base_url . 'delete.gif" alt="Error" title="Error" style="width:16px;height:16px;">',
        'Fatal' => '<img src="' . $base_url . 'stop.gif" alt="Fatal" title="Fatal" style="width:16px;height:16px;">',
        'Crit' => '<img src="' . $base_url . 'stop.gif" alt="Critical" title="Critical" style="width:16px;height:16px;">',
        'Alert' => '<img src="' . $base_url . 'warning.gif" alt="Alert" title="Alert" style="width:16px;height:16px;">',
        'Emerg' => '<img src="' . $base_url . 'stop.gif" alt="Emergency" title="Emergency" style="width:16px;height:16px;">'  
    ];
    
    return $icons[$type] ?? '<img src="' . $base_url . 'info.gif" alt="Unknown" title="Unknown" style="width:16px;height:16px;">';
}
?>