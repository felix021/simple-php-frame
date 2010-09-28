<?php

class sqlite {
    public $db_config   = '';
    public $db          = null;
    public $result      = null;

    public $insert_id;
    public $affected_rows;

    public $errno   = 0;
    public $error   = '';

    public function __construct($filepath) {
        $this->db_config = func_get_args();
        $this->db = new PDO('sqlite:' . $filepath);
        $this->errno = 0;
        if ($this->db->errorCode() > 0) {
            $this->errno = 1;
            $this->error = print_r($this->db->errorInfo(), true);
            $this->db = null;
        }
    }

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

    public function query($query, $type = PDO::FETCH_BOTH) {
        if (!$this->check_connection())
        {
            return false;
        }

        $errno = 0;
        $this->result = $this->db->query($query);
        if ($this->result === false) {
            $this->errno = 3;
            $this->error = print_r($this->db->errorInfo(), true);
            return false;
        }

        $rows = $this->result->fetchAll($type);
        return $rows;
    }

    public function exec($query) {
        if (!$this->check_connection())
        {
            return false;
        }

        $errno = 0;

        $this->affected_rows = $this->db->exec($query);
        if ($this->affected_rows === false) {
            $this->errno = 3;
            $this->error = print_r($this->db->errorInfo(), true);
            return false;
        }

        $this->insert_id     = $this->db->lastInsertId();

        return true;
    }

    public function close() {
        $this->db = null;
    }

    public function __destruct() {
        $this->db = null;
    }

    public function escape($arg) {
        if (is_array($arg))
        {
            foreach ($arg as &$str)
                $str = sqlite_escape_string($str);
        }
        else {
            $arg = sqlite_escape_string($arg);
        }
        return $arg;
    }
}

?>
