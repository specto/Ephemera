<?php
    // This is a controller
    
    require_once('base.php');
    require_once('config.php');
    require_once('model.php');

    setView('index');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['gofirst'])) {
        if (!$file = receiveUpload()) {
            setError('You drop an uncursed scroll. It disintegrates.');
        } else {
            setView('upload');
        }
    }
    if (!empty($_GET)) {
        $target = array_shift(array_keys($_GET));
        if (!$folder = getFolder($target)) {
            setError('You don\'t find anything here to loot.');
        } else {
            if (!empty($_POST) && isset($_POST['id'])) {
                if (!startDownloadAndDelete(intval($_POST['id']), $folder)) {
                    setError('You have a sad feeling for a moment, then it passes.');
                } else {
                    // Must not polute the download with output
                    die();
                }
            } else {
                $list = loadList($folder);
                $realList = getList($folder);
                if (empty($realList)) {
                    setView('missing');
                } else {
                    setView('download');
                }
            }
        }
    }
    
    // Render
    require_once('layout.php');
