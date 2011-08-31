<?php 

    function setView($page) {
        global $view, $error;
        if (isset($error)) {
            return false;
        }
        $view = $page;
        return true;
    }
    function setError($message) {
        global $error, $view;
        $error = $message;
        $view = 'error';
    }
