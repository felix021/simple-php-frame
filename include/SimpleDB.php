<?php

class sqlite {

    public $db_config   = '';
    public $db          = null;
    public $result      = null;

    public $insert_id;
    public $affected_rows;

    public $errno   = 0;
    public $error   = '';

    public function check_connection() {
        if (!is_null($this->db)) {
            $errno = 0;
            return true;
        }
        else {
            $errno = 2;
            $error = 'not connected';
            return false;
        }
    }

    abstract public function query($query, $type);
    abstract public function exec($query);

    public function close() {
        $this->db = null;
    }

    public function __destruct() {
        $this->close();
    }

    abstract public function escape($arg);
}

?>
