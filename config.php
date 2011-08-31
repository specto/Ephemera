<?php 
    
    define('STORAGE_PATH', realpath('../../store/ephemera-storage/'));
    
    if (!is_writable(STORAGE_PATH)) {
        setError('Unable to use storage path.'); 
    }
