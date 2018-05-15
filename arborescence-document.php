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
session_start();

//var_dump($_SESSION);


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
$myRequete = new mysql("", $strInfosSensibles);
$strAction = post("btnAction");
//var_dump($strAction);
$tabDocumentSupp = array();
$intNombreDocuments = 0;
$intNombreDocuments = $mySqli->selectionneEnregistrements("document");
$strFlux1 = "";
$strFlux2 = "";
$booRapport = false;
$compteurSup = 0;
$compteurPasSup = 0;
//$_SESSION["Rapport"] = "";
?>


<form id="arborescence" method="post">
    <label style="position:fixed;left:960px;top:120;font-family: Poppins-Regular" class="sBlanc">Triage par :</label>
    <select name="sort" id="sortID" class="sList" style="position:fixed;left:1070px;top:110;width: 160px;" onchange="document.getElementById('arborescence').submit()">
        <option value="1"> Session</option>
        <option value="2"> Professeur </option>
        <option value="3"> Titre du document</option>
    </select>
    <table class="sBorder">
        <?php
        if ($strAction == "") {
            ?>
            <tr class="sEntete sBorder">
                <td class="sBorder">   
                    #
                </td>
                <td class="sBorder">   
                    Session
                </td>
                <td class="sBorder">
                    Sigle 
                </td>
                <td class="sBorder">
                    Professeur 
                </td>
                <td class="sBorder">
                    Date du cours 
                </td>
                <td class="sBorder">
                    Titre du document 
                </td>
                <td class="sBorder">
                    Case a cocher 
                </td>
            </tr>
            <?php
            if ($intNombreDocuments == 0) {
                ?>
                <tr>
                    Il n'y a aucun document présent dans la base de donnée
                </tr> 
                <?php
            }
            $myRequete->requete = "SELECT d.Session, d.Sigle, cs.NomProf, d.DateCours, d.Titre from document d INNER JOIN courssession cs on d.Sigle = cs.Sigle WHERE d.Session = cs.Session ";
            if (post("sort") == '1') {
                $myRequete->requete .= "ORDER BY d.Session ASC, d.Sigle ASC, d.Titre ASC";
            }
            if (post("sort") == '2') {
                $myRequete->requete .= "ORDER BY cs.NomProf, d.Titre ASC";
            }
            if (post("sort") == '3') {
                $myRequete->requete .= "ORDER BY d.Titre ASC";
            }

            $myRequete->listeEnregistrements = mysqli_query($myRequete->cBD, $myRequete->requete);


            for ($i = 0; $i < $intNombreDocuments; $i++) {
                echo "<tr style=\"background-color: whitesmoke\">";
                echo "<td class=\"sBorder\">" . ($i + 1) . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($i, "Session") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($i, "Sigle") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($i, "NomProf") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($i, "DateCours") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($i, "d.Titre") . "</td>";
                echo "<td class=\"sBorder\">" . "<input type=checkbox name=Document$i />" . "</td>";
            }
        } else if ($strAction == "Supprimer") {
            $booRapport = true;
            ?>
            <tr class="sEntete sBorder">
                <td class="sBorder">   
                    #
                </td>
                <td class="sBorder">   
                    Session
                </td>
                <td class="sBorder">
                    Sigle 
                </td>
                <td class="sBorder">
                    Professeur 
                </td>
                <td class="sBorder">
                    Date du cours 
                </td>
                <td class="sBorder">
                    Titre du document 
                </td>
                <td class="sBorder">
                    Verdict 
                </td>
            </tr>
            <?php
            $myRequete->requete = "SELECT d.Session, d.Sigle, cs.NomProf, d.DateCours, d.Titre from document d INNER JOIN courssession cs on d.Sigle = cs.Sigle WHERE d.Session = cs.Session ORDER BY d.Session ASC, d.Sigle ASC, d.Titre ASC";
            $myRequete->listeEnregistrements = mysqli_query($myRequete->cBD, $myRequete->requete);

            for ($j = 0; $j < $intNombreDocuments; $j++) {
                echo "<tr style=\"background-color: whitesmoke\">";
                echo "<td class=\"sBorder\">" . ($j + 1) . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "d.Session") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "d.Sigle") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "cs.NomProf") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "d.DateCours") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "d.Titre") . "</td>";
                if (post('Document' . $j) != "") {
                    echo "<td class=\"sBorder\">" . "Supprimé" . "</td>";
                    $mySqli->supprimeEnregistrements("document", "Sigle='" . $myRequete->contenuChamp($j, "d.Sigle") . "' AND Session='" . $myRequete->contenuChamp($j, "Session") . "' AND Titre='" . str_replace("'", "\'", $myRequete->contenuChamp($j, "Titre")) . "'");
                } else {
                    echo "<td class=\"sBorder\">" . "</td>";
                }
            }

            $repertoire = opendir("PDF"); // On définit le répertoire dans lequel on souhaite travailler.
            $intCompteur = 0;
            $myRequete->nbEnregistrements = $mySqli->selectionneEnregistrements("document");
            $strFlux1 = "<table>";
            $strFlux1 .= "<tr class='sEntete sBorder'>
                <td class='sBorder'>   
                    #
                </td>
                <td class='sBorder'>   
                    Nom du fichier
                </td>
                <td class='sBorder'>
                    Verdict 
                </td>
            </tr>";
            while (false !== ($fichier = readdir($repertoire))) {
                // On lit chaque fichier du répertoire dans la boucle.
                $booEfface = true;
                for ($k = 0; $k < $myRequete->nbEnregistrements && $booEfface; $k++) {
                    if ($fichier == $mySqli->contenuChamp($k, "HyperLien") || $fichier == "." || $fichier == "..") {
                        $booEfface = false;
                    }
                }
                if ($booEfface && $fichier != "." && $fichier != "..") {
                    $intCompteur++;
                    $strFlux1 .= "<tr style=\"background-color: whitesmoke\"><td class='sBorder'>$intCompteur</td><td  class='sBorder'>$fichier</td><td  class='sBorder'>Supprimé</td></tr>";
                    unlink("PDF/$fichier"); // On efface.
                    $compteurSup++;
                } else {
                    if ($fichier != "." && $fichier != "..") {
                        $intCompteur++;
                        $strFlux2 .= "<tr style=\"background-color: whitesmoke\"><td class='sBorder'>$intCompteur</td><td class='sBorder'> $fichier</td><td class='sBorder'></td></tr>";
                        $compteurPasSup++;
                    }
                }
            }
            $strFlux2 .= "</table>";
            $_SESSION["Rapport"] = $strFlux1 . $strFlux2;
            $_SESSION["CompteurSupp"] = $compteurSup;
            $_SESSION["CompteurPasSup"] = $compteurPasSup;
            // echo  $_SESSION["Rapport"];
        } else if ($strAction == "Rapport") {
            echo $_SESSION["Rapport"];
            echo "<br />";
            echo "<span class=sBlanc style='font-family: Poppins-Regular;'>";
            echo $_SESSION["CompteurSupp"];
            echo " fichiers supprimés, ";
            echo $_SESSION["CompteurPasSup"];
            echo " fichiers conservés";
            echo "</span>";
            echo "<br />";
        }
        ?>
    </table>
    <td>
        <br>
        <input class="sButton" id="btnSupprimer" name="btnAction" type="submit" value="Supprimer">
        <?php if ($booRapport){
            ?>
        <input class="sButton" id="btnRapport" name="btnAction" type="submit" value="Rapport">
        <?php
        }
        ?>
                
        <br> <br>
        <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
               value="Retour">
    </td>
</form>

<?php
require_once ("pied-page.php");
?>
