<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour les tables de r&eacutef&eacuterences";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");
require_once("background.php");
require_once("en-tete.php");


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
?>
<form id="action2" method="post" action="gestion-document.php">
    <br>
    <label> Il y'a <?php $mySqli->selectionneEnregistrements("courssession");
echo $mySqli->nbEnregistrements ?> cours-session </label>
    <br> <br>
    <label> Il y'a <?php $mySqli->selectionneEnregistrements("document");
echo $mySqli->nbEnregistrements ?> document(s) </label>
      <br> <br>
      Liste des cours-session
      <?php echo creerSelectHTMLAvecRequete("CoursSession", "coursSession", "", "selectCoursSession", "coursSession", "", "", $mySqli);?>
       <br> <br>
       <button id="btnSelection" onclick="window.location.href= 'gestion-document.php'">Selection</button>
      <br> <br>
    <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
    
    
</form>

