<?php

header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour la liste des documents";
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
session_start();
$mySqli = new mysql("", $strInfosSensibles);
?>
<form id="arborescence">
    <table>
        <tr class="sEntete">
            <td>
           Session 
           </td>
           <td>
           Sigle 
           </td>
           <td>
           Professeur 
           </td>
           <td>
           Date du cours 
           </td>
           <td>
           Titre du document 
           </td>
           <td>
           Case a cocher 
           </td>
        </tr>
        <tr>
            Il n'y a aucun document présent dans la base de donnée
        </tr>
        <tr>
            <td>
                <br> <br>
                <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
            </td>
        </tr>
    </table>
</form>

<?php
require_once ("pied-page.php");
?>
