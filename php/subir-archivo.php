<?php
    
    if (isset($_POST["txtOrigen"])) {
        $origen = $_POST["txtOrigen"];
        $cveDoc = (int) $_POST["txtDoc"];
        
        if ($origen === "transparencia") {
            header("Location: transparencia.php");
            die();
            return;
        }
        if ($origen === "anexos") {
            header("Location: anexos.php");
            die();
            return;
        }

    }
?>