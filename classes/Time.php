<?php

class Time{

    private $start;

    public function begin(){
        $this->start = microtime(true);
    }

    public function end(){
        $output = date("Y-m-d H:i:s") . ' || ' . COUNT . ' || ' . round(microtime(true) - $this->start, 1) . "\r\n";
        $f = fopen(LOG_FILE, 'a');
        fwrite($f, $output);
        fclose($f);

    }
}