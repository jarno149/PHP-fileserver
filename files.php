<?php

require "FileFunctions.php";
require "Settings.php";

session_start();

if(isset($_POST["gotofolder"]))
{
    $_SESSION["location"] = $_POST["gotofolder"];
    $_POST["gotofolder"] = "";
}

// Mennään yksi askel tiedostopolussa taaksepäin ja estetään palvelimen tiedostojärjestelmään käsiksi pääseminen
if(isset($_POST["go"]) && $_POST["go"] == "up")
{
    $splitted = explode("/", $_SESSION["location"]);
    $newFolderPath = $splitted[0];
    for ($i = 1; $i < count($splitted)-1; $i++)
    {
        $newFolderPath = $newFolderPath . "/" . $splitted[$i];
    }
    if(strlen($newFolderPath) >= strlen($SharedFilePath))
    {
        $_SESSION["location"] = $newFolderPath;
    }
}

if(isset($_POST["filename"]) && isset($_POST["actionType"]))
{
    // Suoritetaan käyttäjän haluamat tapahtumat
    if($_POST["actionType"] == "delete")
    {
        // Poistetaan tiedostot
        DeleteFileOrFolder($_POST["filename"], $_SESSION["location"]);
    }
    else if($_POST["actionType"] == "download")
    {
        // Latadaan tiedostot ja ehkä pakataan jos tiedostoja on useampia
        Download($_POST["filename"], $_SESSION["location"]);
    }
}

if(isset($_FILES["uploadfile"]))
{
    if(count($_FILES["uploadfile"]) > 0 && $_FILES["uploadfile"] != NULL)
    {
        $filess = reArrayFiles($_FILES["uploadfile"]);
        
        // Upataan tiedosto palvelimelle
        Upload($_SESSION["location"], $filess);
    }
}
?>


<html>
	<head>
		<meta charset="utf-8"></meta>
		<title></title>
                <link rel="stylesheet" type="text/css" href="layoutStyles.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type="text/javascript" src="Scripts.js"></script>
	</head>
	<body>
            
	<header>
		<div>
                    <input id="selectall" type="checkbox" onclick="CheckAllFiles()"></input>
		</div>
            
            <form id="fileupload" method="POST" enctype="multipart/form-data">
                <input type="file" multiple name="uploadfile[]" id="uploadfile"/>
            </form>
            
                <div id="delete" class="functionBtn" data-toggle="tooltip" title="Poista tiedosto/tiedostot" onclick="SubmitSelections('delete')"></div>
		<div id="upload" class="functionBtn" data-toggle="tooltip" title="Lähetä tiedosto/tiedostoja" onclick="UploadFile()"></div>
		<div id="download" class="functionBtn" data-toggle="tooltip" title="Lataa tiedosto/tiedostot" onclick="SubmitSelections('download')"></div>
                <div id="back" class="functionBtn" data-toggle="tooltip" title="Edelliseen hakemistoon" onclick="GoUpperDir()"></div>
	</header>
		<table>
                    <form id="filelist" method="POST">
                        <input id="actionType" multiple="multiple" type="hidden" name="actionType" value="" />
                    <?php
                    // HAETAAN TIEDOSTOT TAULUKKOON!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    
                    if(isset($_SESSION["location"]))
                    {
                        $Files = GetFiles($_SESSION["location"]);
                    
                    foreach ($Files as $File)
                    {
                        if($File["extension"] != "DIR")
                        {
                            echo '<tr>
                                    <td class="fileselect">
					<input class="checkBox" name="filename[]" value="' . $File["fullpath"] . '" type="checkbox" onclick="CheckIfAllIsSelected()">
                                    </td>
                                    <td class="filename">
					<p>' . $File["name"] . '</p>
                                    </td>
                                    <td class="filetype">
                                        <p>' . $File["extension"] . '</p>
                                    </td>
                                    <td class="filesize">
                                        <p>' . $File["size"] . '</p>
                                    </td>
                                </tr>';
                        }
                        else
                        {
                            echo '<tr>
                                    <td class="fileselect">
					<input class="checkBox" name="filename[]" value="' . $File["fullpath"] . '" type="checkbox" onclick="CheckIfAllIsSelected()">
                                    </td>
                                    <td class="filename" style="background-color: #0077b3;" onclick="GoToDir(\'' . $_SESSION["location"] . "/" . $File["name"] . '\')">
					<p>' . $File["name"] . '</p>
                                    </td>
                                    <td class="filetype">
                                        <p>' . $File["extension"] . '</p>
                                    </td>
                                    <td class="filesize">
                                        <p>' . $File["size"] . '</p>
                                    </td>
                                </tr>';
                        }
                        }
                    }
                    ?>
                    </form>
		</table>
            <form id="gotofolder" method="POST">
                <input id="folder" type="hidden" name="gotofolder" value="">
            </form>
            <form id="goup" method="POST">
                <input id="up" type="hidden" name="go" value="up">
            </form>
	</body>
</html>