<?php
$lang['admindescription'] = 'Supervise y analice los errores de PHP y los registros del servidor en una interfaz fácil de usar';
$lang['ask_uninstall'] = '¿Está seguro de que desea desinstalar el módulo LogWatch?';
$lang['custom_error_handler'] = 'Gestor de errores personalizado';
$lang['custom_error_handler_desc'] = 'Habilitar el controlador de errores PHP incorporado';
$lang['custom_error_handler_help'] = 'Captura los errores de PHP directamente y los escribe en un archivo de registro local. Resulta útil cuando no se puede acceder a los registros de errores del servidor (alojamiento compartido, permisos restringidos).';
$lang['date'] = 'Fecha';
$lang['description'] = 'LogWatch ayuda a los desarrolladores a solucionar errores de PHP al proporcionar una vista limpia y organizada de los registros de errores del servidor. Supervise los errores fatales, las advertencias, los avisos y las funciones obsoletas directamente desde el panel de administración de su CMS.';
$lang['error_hidden'] = 'Error marcado como corregido y oculto en los registros';
$lang['error_hide_failed'] = 'No se pudo ocultar el error';
$lang['error_log_file_not_found'] = '¡Error en el archivo de registro!';
$lang['error_log_file_manual'] = 'No se pudo cargar el archivo de registro manual: %s';
$lang['error_log_file_selected'] = 'No se pudo cargar el archivo de registro seleccionado: %s';
$lang['error_unhidden'] = 'Error restaurado en la vista de registros';
$lang['error_unhide_failed'] = 'Error al mostrar el error';
$lang['export_csv'] = 'Exportar CSV';
$lang['file'] = 'Expediente';
$lang['filter_error_types_desc'] = 'Seleccione los tipos de error que desea mostrar en la vista de registros:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Línea';
$lang['log_source'] = 'Fuente de registro';
$lang['logwatch_pro_status'] = 'Estatus LogWatch Pro';
$lang['manual_log_path'] = 'Ruta del archivo de registro manual';
$lang['manual_log_path_desc'] = 'Introduzca la ruta completa del servidor a su archivo de registro de errores';
$lang['message'] = 'Mensaje';
$lang['pro_disabled'] = 'Discapacitado';
$lang['pro_disabled_desc'] = 'Las funciones premium están actualmente deshabilitadas';
$lang['pro_enabled'] = 'Habilitado';
$lang['pro_enabled_desc'] = 'Las funciones premium están activas (notificaciones, análisis, integraciones)';
$lang['prompt_go'] = 'Ve';
$lang['prompt_page'] = 'Página';
$lang['settings_saved'] = 'La configuración se guardó correctamente';
$lang['tab_filters'] = 'Filtros';
$lang['tab_hidden'] = 'Errores ocultos';
$lang['tab_logs'] = 'Registros';
$lang['tab_premium'] = 'Premium';
$lang['tab_settings'] = 'Ajustes';
$lang['type'] = 'Tipo';
$lang['help_general'] = 'General';
$lang['help_features'] = 'Características';
$lang['help_configuration'] = 'Configuración';
$lang['help_pro_features'] = 'Características profesionales';
$lang['help_upgrade'] = 'Actualiza a Pro';
$lang['help_troubleshooting'] = 'Solución de problemas';
$lang['error_log_file_reasons'] = <<<EOT
Esto puede deberse a:
<ul>
 <li>El archivo no existe en la ubicación especificada</li>
 <li>Los permisos de lectura del servidor web son insuficientes</li>
 <li>La ruta del archivo es incorrecta o ha cambiado</li></ul>
Seleccione una fuente de registro diferente o compruebe los permisos del archivo.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>¡No se detectaron fuentes de registro!</strong> <br/>
LogWatch no pudo detectar automáticamente ningún archivo de registro de errores legible en su servidor. Esto puede ocurrir en un alojamiento compartido o cuando los archivos de registro se encuentran en ubicaciones no estándar. <br/>
Utilice la opción de ruta de registro manual que aparece a continuación para especificar la ruta completa al archivo de registro de errores.
EOT;
?>