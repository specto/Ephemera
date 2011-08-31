<?php
    function startDownloadAndDelete($id, $folder) {
        if (strpos($folder, STORAGE_PATH) === FALSE || $folder == STORAGE_PATH) {
            return FALSE;
        }
        $list = loadList($folder);
        $name = $list[$id];
        if (!serveFile($name, $folder . $name)) {
            return FALSE;
        }
        @unlink($folder . $name);
        $remainingFiles = getList($folder);
        if (count($remainingFiles) == 0) {
            @unlink($folder . 'list');
            @rmdir($folder);
        }
        return TRUE;
    }

    function receiveUpload($reuse = NULL) {
        $num_files = count($_FILES['upload']['name']);
        for ($i=0; $i < $num_files; $i++) {
            if (empty($newFolder)) {
                if (!$newFolder = makeNewFolder()) {
                    return FALSE;
                }
            }
            $inFile = $_FILES['upload']['tmp_name'][$i];
            $outFile = getFolder($newFolder) . basename($_FILES['upload']['name'][$i]);
            if (FALSE === is_uploaded_file($inFile)) {
                return FALSE;
            }
            if (FALSE === move_uploaded_file($inFile, $outFile)) {
                return FALSE;
            }
        }
        rebuildList($newFolder);
        return $newFolder;        
    }
    
    function loadList($folder) {
        $list = @file_get_contents($folder . 'list');
        if (FALSE === $list) {
            return array();
        }
        return explode(PHP_EOL, $list);
    }
    
    function getList($folder) {
        $list = array();
        if (!is_readable($folder)) {
            return $list;
        }
        foreach (scandir($folder) as $item ) {
            if (in_array($item, array('.', '..', 'list'))) {
                continue;
            }
            $list []= $item;
        }
        return $list;
    }
    function rebuildList($name) {
        $folder = getFolder($name);
        $list = getList($folder);
        if (!$fp = fopen($folder . 'list', 'w')) {
            return FALSE;
        }
        fwrite($fp, implode(PHP_EOL, $list));
        fclose($fp);
        return TRUE;
    }
    
    function makeNewFolder() {
        $name = hash('crc32b', 'epHem=r@' . microtime(true) . mt_rand(10000,90000));
        $newDir = STORAGE_PATH . '/' . $name;
        if (file_exists($newDir)) {
            return FALSE;
        }
        if (FALSE == mkdir($newDir)) {
            return FALSE;
        }
        return $name;
    }
    
    function getFolder($name) {
        return STORAGE_PATH . '/' . $name . '/';
    }
    
    function serveFile ($filename, $fullpath) {
	
		$fname = $filename;
		$fpath = $fullpath;
		$fsize = filesize($fpath);
		$bufsize = 20000;
		
		if(isset($_SERVER['HTTP_RANGE']))  //Partial download
		{
			if(preg_match("/^bytes=(\\d+)-(\\d*)$/", $_SERVER['HTTP_RANGE'], $matches)) { //parsing Range header
			   $from = $matches[1];
			   $to = $matches[2];
			   if(empty($to))
			   {
				   $to = $fsize - 1;  // -1  because end byte is included
									   //(From HTTP protocol:
			// 'The last-byte-pos value gives the byte-offset of the last byte in the range; that is, the byte positions specified are inclusive')
			   }
			   $content_size = $to - $from + 1;
			
			   header("HTTP/1.1 206 Partial Content");
			   header("Content-Range: $from-$to/$fsize");
			   header("Content-Length: $content_size");
			   header("Content-Type: application/force-download");
			   header("Content-Disposition: attachment; filename=$fname");
			   header("Content-Transfer-Encoding: binary");
			
			   if(file_exists($fpath) && $fh = fopen($fpath, "rb"))
			   {
				   fseek($fh, $from);
				   $cur_pos = ftell($fh);
				   while($cur_pos !== FALSE && ftell($fh) + $bufsize < $to+1)
				   {
					   $buffer = fread($fh, $bufsize);
					   print $buffer;
					   $cur_pos = ftell($fh);
				   }
			
				   $buffer = fread($fh, $to+1 - $cur_pos);
				   print $buffer;
			
				   fclose($fh);
			   }
			   else
			   {
				   return FALSE;
			   }
			}
			else
			{
			   return FALSE;
			}
		}
		else // Usual download
		{
			header("HTTP/1.1 200 OK");
			header("Content-Length: $fsize");
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=$fname");
			header("Content-Transfer-Encoding: binary");
		
			if(file_exists($fpath) && $fh = fopen($fpath, "rb")){
			   while($buf = fread($fh, $bufsize))
				   print $buf;
			   fclose($fh);
			}
			else
			{
			   return FALSE;
			}
		}
		return TRUE;
	}
