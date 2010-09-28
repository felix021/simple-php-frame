<?php

class mysql {
    public $db_config   = '';
    public $db          = null;
    public $result      = null;

    public $insert_id;
    public $affected_rows;

    public $errno   = 0;
    public $error   = '';

    public function __construct($db_host, $db_user, $db_pass, $db_name) {
        $this->db_config = func_get_args();
        $this->db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $this->errno = 0;
        if ($this->db->errno > 0) {
            $this->errno = 1;
            $this->error = sprintf("[%d]%s", $this->db->errno, $this->db->error);
            $this->db = null;
        }
        /* 为啥加了这个会出乱码呢？想不通啊想不通……
        if (!$this->db->set_charset("utf8"))
        {
            $this->errno = 1;
            $this->error = sprintf("[%d]%s", $this->db->errno, $this->db->error);
            $this->db = null;
        }
         */
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

    public function query($query, $type = MYSQLI_BOTH) {
        if (!$this->check_connection())
        {
            return false;
        }

        $errno = 0;
        $this->result = $this->db->query($query);
        if ($this->db->errno > 0) {
            $this->errno = 3;
            $this->error = sprintf("[%d]%s", $this->db->errno, $this->db->error);
            $this->db = null;
        }

        $rows = array();
        while ($row = $this->result->fetch_array($type))
            $rows[] = $row;
        return $rows;
    }

    public function exec($query) {
        if (!$this->check_connection())
        {
            return false;
        }

        $errno = 0;

        $this->result = $this->db->query($query);
        if ($this->db->errno > 0) {
            $this->errno = 4;
            $this->error = sprintf("[%d]%s", $this->db->errno, $this->db->error);
            $this->db = null;
        }
        $this->affected_rows = $this->db->affected_rows;
        $this->insert_id     = $this->db->insert_id;

        return true;
    }

    public function close() {
        if ($this->db !== null) {
            $this->db->close();
            $this->db = null;
        }
    }

    public function __destruct() {
        $this->close();
    }

    public function escape($arg) {
        if (is_array($arg))
        {
            foreach ($arg as &$str)
                $str = $this->db->real_escape_string($str);
        }
        else {
            $str = $this->db->real_escape_string($str);
        }
        return $arg;
    }
}

?>
