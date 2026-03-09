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
    $mod = cms_utils::get_module('LogWatch');
    $icon_url = $mod->GetModuleURLPath() . '/assets/icons/';
    
    $icons = [
        'Fatal error' => '<img src="' . $icon_url . 'fatal.svg" alt="Fatal Error" title="Fatal Error" style="width:16px;height:16px;">',
        'Warning' => '<img src="' . $icon_url . 'warning.svg" alt="Warning" title="Warning" style="width:16px;height:16px;">', 
        'Notice' => '<img src="' . $icon_url . 'notice.svg" alt="Notice" title="Notice" style="width:16px;height:16px;">',
        'Deprecated' => '<img src="' . $icon_url . 'deprecated.svg" alt="Deprecated" title="Deprecated" style="width:16px;height:16px;">',
        'Error' => '<img src="' . $icon_url . 'error.svg" alt="Error" title="Error" style="width:16px;height:16px;">',
        'Fatal' => '<img src="' . $icon_url . 'fatal.svg" alt="Fatal" title="Fatal" style="width:16px;height:16px;">',
        'Crit' => '<img src="' . $icon_url . 'fatal.svg" alt="Critical" title="Critical" style="width:16px;height:16px;">',
        'Alert' => '<img src="' . $icon_url . 'warning.svg" alt="Alert" title="Alert" style="width:16px;height:16px;">',
        'Emerg' => '<img src="' . $icon_url . 'fatal.svg" alt="Emergency" title="Emergency" style="width:16px;height:16px;">'  
    ];
    
    return $icons[$type] ?? '<img src="' . $icon_url . 'notice.svg" alt="Unknown" title="Unknown" style="width:16px;height:16px;">';
}
?>