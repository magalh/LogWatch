<?php
 
    function smarty_modifier_typeicon($value) {

        $themeObject = cms_utils::get_theme_object();
        $icon = '';
        switch ($value) {
            case 'WARN':
                case 'WARNING':
                $icon = $themeObject->DisplayImage('icons/extra/warning.png', 'warning', '', '', 'systemicon');
                break;
            case 'INFO':
                case 'NOTICE':
                $icon = $themeObject->DisplayImage('icons/extra/info.png', 'info', '', '', 'systemicon');
                break;
            case 'ERROR':
                case 'CRITICAL':
                $icon = $themeObject->DisplayImage('icons/extra/block.png', 'stop', '', '', 'systemicon');
                break;
            default:
                $icon = '';
        }
        return $icon;

    }
?>

