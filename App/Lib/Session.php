<?php
namespace App\Lib;
class Session {
    function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        } 
    } 
    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        } 
    } 
    public function set($key, $value) {
        $_SESSION[$key] = $value;
        return true;
    } 
    public function del($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        } 
    } 
    public function destroy() {
        session_destroy();
    } 
} 
