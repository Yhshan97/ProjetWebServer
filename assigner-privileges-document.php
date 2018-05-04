<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Assigner un groupe d'utilisateurs à un cours-session";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");
require_once("background.php");
require_once("en-tete.php");

session_start();
detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
?>
<form id="assignerPrivileges" method="post" action="">
    <br>
    <label> Il y'a <?php $mySqli->selectionneEnregistrements("courssession");
echo $mySqli->nbEnregistrements ?> cours-session </label>
    <br> <br>
    <label> Il y'a <?php $mySqli->selectionneEnregistrements("utilisateur");
echo $mySqli->nbEnregistrements ?> utilisateur(s) </label>
      <br> <br>
      Liste des cours-session
      <?php echo creerSelectHTMLAvecRequete("CoursSession", "coursSession", "", "selectCoursSession", "coursSession", "sList", "", $mySqli);?>
      <br> <br>
      Liste des Utilisateurs
      <?php echo creerSelectHTMLAvecRequete("utilisateur", "NomUtilisateur", "", "selectUtilisateur", "NomUtilisateur", "sList", "", $mySqli);?>
       <br> <br>
       <table>
           
       </table>
       <button id="btnSelection" class="sButton" onclick="window.location.href= 'gestion-document.php'">Selection</button>
      <br> <br>
    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
    
    
</form>



<?php
require_once("pied-page.php");