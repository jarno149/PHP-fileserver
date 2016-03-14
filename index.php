<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        $_SESSION["location"] = $_SERVER["DOCUMENT_ROOT"] . "/fileserver/files";
        header("Location: files.php");
        ?>
    </body>
</html>
