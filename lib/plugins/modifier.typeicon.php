<?php
function smarty_modifier_typeicon($value) {
    $themeObject = cms_utils::get_theme_object();
    $icon = '';

    switch ($value) {
        case 'WARN':
        case 'E_WARNING':
        case 'E_USER_WARNING':
        case 'E_CORE_WARNING':
        case 'E_COMPILE_WARNING':
            $icon = $themeObject->DisplayImage('icons/extra/warning.png', 'warning', '', '', 'systemicon');
            break;
        case 'INFO':
        case 'E_NOTICE':
        case 'E_USER_NOTICE':
        case 'E_DEPRECATED':
        case 'E_USER_DEPRECATED':
        case 'E_STRICT':
            $icon = $themeObject->DisplayImage('icons/extra/info.png', 'info', '', '', 'systemicon');
            break;
        case 'ERROR':
        case 'E_ERROR':
        case 'E_PARSE':
        case 'E_CORE_ERROR':
        case 'E_COMPILE_ERROR':
        case 'E_USER_ERROR':
        case 'E_RECOVERABLE_ERROR':
        case 'E_CRITICAL':
            $icon = $themeObject->DisplayImage('icons/extra/block.png', 'stop', '', '', 'systemicon');
            break;
        default:
            $icon = $value;
    }

    return $icon;
}

?>

