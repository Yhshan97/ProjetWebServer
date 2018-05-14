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

var_dump($_POST);


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);
session_start();
$mySqli = new mysql("", $strInfosSensibles);
$myRequete = new mysql("", $strInfosSensibles);
$strAction = post("btnSupprimer");
$tabDocumentSupp = array();
$intNombreDocuments = $mySqli->selectionneEnregistrements("document");
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
        } else {
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
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "Session") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "Sigle") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "NomProf") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "DateCours") . "</td>";
                echo "<td class=\"sBorder\">" . $myRequete->contenuChamp($j, "Titre") . "</td>";
                if (post('Document' . $j) != "") {
                    echo "<td class=\"sBorder\">" . "Supprimé" . "</td>";
                    $mySqli->supprimeEnregistrements("document", "Sigle='" . $myRequete->contenuChamp($j, "Sigle") . "' AND Session='" . $myRequete->contenuChamp($j, "Session") . "' AND Titre='" . str_replace("'", "\'", $myRequete->contenuChamp($j, "Titre")) . "'");
                    echo $mySqli->requete;
                } else {
                    echo "<td class=\"sBorder\">" . "</td>";
                }
            }

            $repertoire = opendir("PDF"); // On définit le répertoire dans lequel on souhaite travailler.
            
            $myRequete->nbEnregistrements = $mySqli->selectionneEnregistrements("document");
            while (false !== ($fichier = readdir($repertoire))) {
                // On lit chaque fichier du répertoire dans la boucle.
                $booEfface = true;
                for ($k = 0; $k < $myRequete->nbEnregistrements && $booEfface; $k++) { 
                    echo $fichier;
                    echo "=";
                    echo $mySqli->contenuChamp($k, "HyperLien");
                    echo "<br />";
                    if ($fichier == $myRequete->contenuChamp($k, "HyperLien") || $fichier == "." || $fichier == "..") {
                        $booEfface = false;  
                        echo "bob";
                    }
                }
                if($booEfface && $fichier != "." && $fichier != ".."){
                    unlink("PDF/$fichier"); // On efface.
                    
                }
            }
        }
        ?>
    </table>
    <td>
        <br>
        <input class="sButton" id="btnSupprimer" name="btnSupprimer" type="submit" value="Supprimer">
        <br> <br>
        <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
               value="Retour">
    </td>
</form>

<?php
require_once ("pied-page.php");
?>
