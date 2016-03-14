<?php

function GetFiles($FolderPath)
{
    $DataArray = array();
    $FileArray = scandir($FolderPath);
    $count = 0;
    foreach ($FileArray as $File)
    {
        if($File != "." && $File != "..")
        {
            $DataArray[$count]["name"] = basename($File, "." . pathinfo($File, PATHINFO_EXTENSION));
            $DataArray[$count]["fullpath"] = $FolderPath . "/" . $File;
            if(is_dir($FolderPath . "/" . $File))
            {
                $DataArray[$count]["extension"] = "DIR";
                $DataArray[$count]["size"] = "";
            }
            else
            {
                $DataArray[$count]["extension"] = pathinfo($File, PATHINFO_EXTENSION);
                $DataArray[$count]["size"] = GetFileSize($FolderPath . "/" . $File);
            }
            $count++;
        }
    }
    return $DataArray;
}

function GetFileSize($FilePath)
{
    $size = filesize($FilePath);
    if($size > 1073741824)
    {
        $size = round(($size / 1073741824), 2) . "Gb";
    }
    else if($size > 1048576)
    {
        $size = round(($size / 1048576),2) . "Mb";
    }
    else if($size > 1024)
    {
        $size = round(($size / 1024),2) . "Kb";
    }
    else
    {
        $size = $size . "b";
    }
    return $size;
}

function DeleteDirectory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
	 if (!$dir_handle)
	      return false;
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != "..") {
	            if (!is_dir($dirname."/".$file))
	                 unlink($dirname."/".$file);
	            else
	                 DeleteDirectory($dirname.'/'.$file);
	       }
	 }
	 closedir($dir_handle);
	 rmdir($dirname);
}

function DeleteFileOrFolder($Files, $CurrentDir)
{
    if(count($Files) > 1)
    {
        foreach ($Files as $file)
        {
            if(is_dir($file))
            {
                DeleteDirectory($file);
            }
            else
            {
                unlink($file);
            }
        }
    }
    else if(count($Files) == 1)
    {
        if(is_dir($Files[0]))
        {
            DeleteDirectory($Files[0]);
        }
        else
        {
            unlink($Files[0]);
        }
    }
    else
    {
        
    }
}

function Test($file)
{
    echo count($file);
    for($i = 0; $i < count($file); $i++)
    {
        echo $file[$i]["name"];
    }
}

function reArrayFiles($file_post)
{

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function Upload($targetdir, $file)
{
    if(count($file) > 1)
    {
        foreach ($file as $fil)
        {
            $target_file = $targetdir . "/" . basename($fil["name"]);
            if(move_uploaded_file($fil["tmp_name"], $target_file))
            {
                // Tiedosto lähetetty
            }
        }
    }
    elseif (count($file) == 1)
    {
        $target_file = $targetdir . "/" . basename($file[0]["name"]);
    
        if(move_uploaded_file($file[0]["tmp_name"], $target_file))
        {
            // Tiedosto lähetetty
        }
    }
}

function Download($Files, $Path)
{
    if(count($Files) > 1)
    {
        $zip = new ZipArchive();
        $zip_name = time().".zip";
        $zip->open($zip_name, ZipArchive::CREATE);
        foreach ($Files as $File)
        {
            if(file_exists($File))
            {
                $zip->addFile($File, basename($File));
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-disposition: attachment; filename=" . $zip_name);
        header("Content-Lenght: " . filesize($zip_name));
        while (ob_get_level())
        {
            ob_end_clean();
        }
        readfile($zip_name);
        unlink($zip_name);
    }
    else if(count($Files) == 1)
    {
        if(is_dir($Files[0]))
        {
            if(file_exists($Files[0]))
            {
                $files = scandir($Files[0]);
                if(count($files) > 0)
                {
                    $zip = new ZipArchive();
                    $zip_name = time().".zip";
                    $zip->open($zip_name, ZipArchive::CREATE);
                    foreach ($files as $file)
                    {
                        if($file != "." && $file != "..")
                        {
                            echo $Files[0] . "/" . $file;
                            $zip->addFile($Files[0] . "/" . $file, $file);
                        }
                    }
                    $zip->close();
                    header("Content-Type: application/zip");
                    header("Content-disposition: attachment; filename=" . $zip_name);
                    header("Content-Lenght: " . filesize($zip_name));
                    while (ob_get_level())
                    {
                        ob_end_clean();
                    }
                    readfile($zip_name);
                    unlink($zip_name);
                }
            }
        }
        else
        {
            header("Content-Type: application/octet-stream");
            header("Content-disposition: attachment; filename=". basename($Files[0]));
            header("Content-Lenght: " . filesize($Files[0]));
            while (ob_get_level())
            {
                ob_end_clean();
            }   
            readfile($Files[0]);
            }
        }
    else
    {
        
    }
}

