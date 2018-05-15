<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour la liste des documents";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

session_start();

if (!isset($_SESSION["NomComplet"])) {
    header('location: gestion-documents-administrateur.php');
}

/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");
require_once("background.php");
require_once("en-tete.php");


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);
$strCoursSession = post("coursSession");
$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);


$mySqli->requete = "Select Session,Sigle from courssession where coursSession =" . "'" . $strCoursSession . "'";
$mySqli->listeEnregistrements = mysqli_query($mySqli->cBD, $mySqli->requete);
?>
<form id="document" method="post" action="" style="font-family: Poppins-Regular;">


    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
</form>

<?php
require_once ("pied-page.php");
?>