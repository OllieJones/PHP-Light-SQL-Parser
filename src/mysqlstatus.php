<?php

namespace olliejones\mysqlstatus;

class MySQLStatus
{
    private $status;
    private $vars;
    private $timestamp;
    public function __construct($status, $vars, $timestamp) {
        $this->status = $status;
        $this->vars = $vars;
        $this->timestamp = $timestamp;
    }

    public function toString() {
        return json_encode( array (
            'timestamp' => $this->timestamp,
            'status' => $this->status,
            'vars' => $this->vars
        ));
    }
}