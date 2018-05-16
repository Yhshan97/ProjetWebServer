<?php
session_start();
$strLocation = "location: gestion-documents.php";

if(isset($_SESSION["connecteeUtil"]) && $_SESSION["connecteeUtil"]){
    $strLocation = "location: gestion-documents.php";
}else if(isset($_SESSION["connectee"]) && $_SESSION["connectee"]){
    $strLocation = "location: gestion-documents-administrateur.php";
}

session_unset();
session_destroy();

header($strLocation);