<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
 
    function smarty_modifier_noroot($value) {

        $rootPath = CMS_ROOT_PATH;

        $cleanedValue = str_replace($rootPath, '', $value);
        return $cleanedValue;

    }
?>