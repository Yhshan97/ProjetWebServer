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

$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);
$strCoursSession = post("coursSession");
$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);


$mySqli->requete = "Select Session,Sigle from courssession where coursSession =" ."'". $strCoursSession ."'";
$mySqli->listeEnregistrements = mysqli_query($mySqli->cBD, $mySqli->requete);


?>
<form id="document" method="post" action="">
    
    <table>
       Veuillez saisir les données du nouveau document à ajouter dans <?php echo post("coursSession"); ?> 
        <tr>
            <td>
                Session : <input class="" type="text" value="<?php echo $mySqli->contenuChamp(0,"Session"); ?>"
            </td>
        </tr>
        <tr>
            <td>
                Sigle : <input class="" type="text" value="<?php echo $mySqli->contenuChamp(0,"Sigle"); ?>"
            </td>
        </tr>
         <tr>
            <td>
                DateCours :   <select id="ddlNoSequence" name="ddlNoSequence" class="">
				<option value=""></option>
				<script type="text/javascript">
				    for (var i = 1; i <= 20; i++) {
				        document.write('<option value="' + i + '">' + i + '</option>')
				    }
				</script>
                             </select>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateCours :  <?php input("dateCours", "", "date", 10, "", true); ?>
            </td>
        </tr>
        
    </table>
    <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
</form>
