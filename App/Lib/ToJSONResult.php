<?php

namespace App\Lib;
class ToJSONResult {
    private $result;
    function __construct() {
        $this->result = array('code' => 200, 'msg' => 0);
    } 
    public function set($key, $value) {
            $this->result[$key] = $value;
            return true;
         
    } 
    public function del($key) {
        if (isset($this->result[$key])) {
            unset($this->result[$key]);
            return true;
        } else {
            return false;
        } 
    } 
    public function get($key) {
        if (isset($this->result[$key])) {
            return $this->result[$key];
        } else {
            return null;
        } 
    } 
    public function toJSON() {
        return json_encode($this->result);
    } 
} 
