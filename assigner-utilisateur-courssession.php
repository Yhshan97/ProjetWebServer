<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour les tables de r&eacutef&eacuterences";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

session_start();

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
<form id="assignerUser" method="post" action="">
    <table>
        <tr>
            <td>
                Veuillez svp entrer le nom du fichier CSV
            </td>
            <td>
                <?php echo input("nomFichier", "", "text", 50, "") ?>
            </td>
        </tr>
        <tr>
            <td>
                <button id="btnConfirmer" class="">Confirmer</button>
            </td>
            <td>        <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
                           value="Retour">
            </td>
        </tr>
    </table>
</form>
