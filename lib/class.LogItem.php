<?php
class LogItem
{
    private $_data = array('id'=>null, 'name'=>null, 'type'=>null, 'description'=>null, 'stacktrace'=>null, 'file'=>null, 'line'=>null, 'created'=>null);

    public function __get($key)
    {
        switch ($key) {
            case 'id':
            case 'name':
            case 'type':
            case 'line':
            case 'description':
            case 'stacktrace':
            case 'created':
            case 'file':
                return $this->_data[$key];
        }
    }

    public function __set($key, $val)
    {
        switch ($key) {
            case 'name':
            case 'type':
            case 'file':
            case 'description':
            case 'stacktrace':
                $this->_data[$key] = trim($val);
                break;
            case 'created':
                if ($val instanceof \DateTime || $val instanceof \DateTimeImmutable) {
                    $this->_data[$key] = $val->format('Y-m-d H:i:s');
                } else {
                    $this->_data[$key] = date('Y-m-d H:i:s', strtotime($val));
                }
                break;
            case 'line':
                $this->_data[$key] = (int) $val;
                break;
        }
    }

    public function save()
    {
        if (!$this->is_valid()) return FALSE;

        $existingLogItem = $this->load_by_file_description_line($this->file, $this->description, $this->line);
        if ($existingLogItem && $existingLogItem->id > 0) {
            $this->id = $existingLogItem->id;
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function is_valid()
    {
        if (!$this->name) return false;
        if (!$this->description) return false;
        return TRUE;
    }

    protected function insert()
    {
        $db = \cms_utils::get_db();
        $sql = 'INSERT INTO '.CMS_DB_PREFIX.'module_logwatch (name, type, file, line, description, stacktrace, created) VALUES (?,?,?,?,?,?,?)';
        $dbr = $db->Execute($sql, array($this->name, $this->type, $this->file, $this->line, $this->description, $this->stacktrace, date('Y-m-d H:i:s')));
        if (!$dbr) return FALSE;
        $this->_data['id'] = $db->Insert_ID();
        return TRUE;
    }

    protected function update()
    {
        $this->created = date('Y-m-d H:i:s');
        $db = \cms_utils::get_db();
        $sql = 'UPDATE '.CMS_DB_PREFIX.'module_logwatch SET created = ? WHERE id = ?';
        $dbr = $db->Execute($sql, array($this->created, $this->id));
        if (!$dbr) return FALSE;
        return TRUE;
    }

    public function delete()
    {
        if (!$this->id) return FALSE;
        $db = \cms_utils::get_db();
        $sql = 'DELETE FROM '.CMS_DB_PREFIX.'module_logwatch WHERE id = ?';
        $dbr = $db->Execute($sql, array($this->id));
        if (!$dbr) return FALSE;
        $this->_data['id'] = null;
        return TRUE;
    }

    /** internal */
    public function fill_from_array($row)
    {
        foreach ($row as $key => $val) {
            if (array_key_exists($key, $this->_data)) {
                $this->_data[$key] = $val;
            }
        }
    }

    public static function &load_by_id($id)
    {
        $id = (int) $id;
        $db = \cms_utils::get_db();
        $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_logwatch WHERE id = ?';
        $row = $db->GetRow($sql, array($id));
        if (is_array($row)) {
            $obj = new self();
            $obj->fill_from_array($row);
            return $obj;
        }
    }

    public static function load_by_file_and_line($file, $line)
    {
        $db = \cms_utils::get_db();
        $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_logwatch WHERE file = ? AND line = ?';
        $row = $db->GetRow($sql, array($file, $line));
        if (is_array($row)) {
            $obj = new self();
            $obj->fill_from_array($row);
            return $obj;
        }
        return null;
    }

    public function load_by_file_description($file, $description)
    {
        $db = \cms_utils::get_db();
        $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_logwatch WHERE file = ? AND description = ?';
        $row = $db->GetRow($sql, array($file, $description));
        $this->fill_from_array($row);
        return null;
    }

    public function load_by_file_description_line($file, $description, $line)
    {
        $db = \cms_utils::get_db();
        $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_logwatch WHERE file = ? AND description = ? AND line = ?';
        $row = $db->GetRow($sql, array($file, $description, $line));
        $this->fill_from_array($row);
        return null;
    }

    /**
     * Debug function to output debug information about a variable in a formatted matter
     * to a debug file.
     *
     * @param mixed $var    data to display
     * @param string $title optional title.
     * @param string $filename optional output filename
     */
    private function debug_to_log($var, $title='',$filename = '')
    {
        if( $filename == '' ) {
            $filename = TMP_CACHE_LOCATION . '/logwatch.log';
            $x = (is_file($filename)) ? @filemtime($filename) : time();
            if( $x !== FALSE && $x < (time() - 24 * 3600) ) unlink($filename);
        }
        $errlines = explode("\n",debug_display($var, $title, false, false, true));
        foreach ($errlines as $txt) {
            error_log($txt . "\n", 3, $filename);
        }
        
    }

    
}
?>
