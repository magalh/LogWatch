<?php
class LogQuery extends CmsDbQueryBase
{
    
    protected $_filter;
    public $_totalmatchingrows;

    public function __construct($filter = null)
    {
        $this->_filter = $filter;
        $this->_limit = 1000;
        $this->_offset = 0;
    }

    public function set_limit(int $limit)
    {
        $this->_limit = max(1,$limit);
    }

    public function set_offset(int $offset)
    {
        $this->_offset = max(0,$offset);
    }

    public function execute(){
        if( !is_null($this->_rs) ) return;
        $sql = 'SELECT SQL_CALC_FOUND_ROWS H.* FROM '.CMS_DB_PREFIX.'module_logwatch H ORDER BY created DESC';
        $db = \cms_utils::get_db();
        $this->_rs = $db->SelectLimit($sql,$this->_limit,$this->_offset);
        if( $db->ErrorMsg() ) throw new \CmsSQLErrorException($db->sql.' -- '.$db->ErrorMsg());
        $this->_totalmatchingrows = $db->GetOne('SELECT FOUND_ROWS()');
    }

    public function &GetObject(){
        $obj = new LogItem;
        $obj->fill_from_array($this->fields);
        return $obj;
    }

    public function GetMatches()
    {
        $this->execute();
        $matches = [];
        while (!$this->_rs->EOF) {
            $obj = $this->GetObject();
            $matches[] = $obj;
            $this->_rs->MoveNext();
        }
        return $matches;
    }

}
?>