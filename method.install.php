<?php
if( !defined('CMS_VERSION') ) exit;
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');

$db = $this->GetDb();
$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'TYPE=MyISAM');
$flds = "
 id I KEY AUTO,
 name C(255),
 type C(255),
 description X,
 stacktrace X,
 file C(255),
 line I,
 created ".CMS_ADODB_DT."
";
$sqlarray = $dict->CreateTableSQL(CMS_DB_PREFIX.'module_logwatch',$flds,$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$default_logsettings = 'E_ERROR,E_NOTICE,E_WARNING';
$this->SetPreference('logsettings', $default_logsettings);

?>