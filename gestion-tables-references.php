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
<form id="action" method="post" action="">
    <br> <input type="radio" id="radBtn" name="action" value="ajout"
        <?php
    if(post("action") == "ajout" || !isset($_POST["action"]))
        echo "checked";
        ?>
    > Ajouter
    <br> <input type="radio" id="radBtn" name="action" value="modif" <?php if(post("action") == "modif") echo "checked"; ?>> Modifier
    <br> <input type="radio" id="radBtn" name="action" value="retir" <?php if(post("action") == "retir") echo "checked"; ?>> Retirer
    <br> <br>
    <select name="option2" id="option" onchange="" >
    <option value="1">1. Gestion des sessions d'étude
    <option value="2">2. Gestion des cours 
    <option value="3">3. Gestion des cours-sessions
    <option value="4">4. Gestion des catégories de documents
    <option value="5">5. Gestion des utilisateurs </option>
    </select>
    <br><br> 
    <input class="" id="btnRetour" type="button" onclick="window.location.href='gestion-documents-administrateur.php'" value="Retour">
    <input class="" id="InpValider" type="submit" value="Valider choix">
</form>

<?php
switch (post("option2")) {
    case 1:
        ?>
        <div>
        <form id="GestionSession" method="post" action="">
            <table>
                <tr>
                    <td>
                        Session d'étude :
                    </td>
                    <td>
                        <?php
                        if (post("action") == "ajout") {
                            input("Session", "", "text", 6, "", true);
                        } else {
                            $mySqli->selectionneEnregistrements("Session");
                            $resultat = mysqli_query($mySqli->cBD, $mySqli->requete);

                            while ($val = mysqli_fetch_array($resultat, MYSQLI_NUM)) {
                                $description[] = $val[0];
                            }
                            if (!isset($description))
                                $description[0] = " -------- ";

                            echo creerSelectHTML("Sessions", "Session", "", "", $description);
                        }
                        ?>
                    </td>
                </tr>
                <?php
                if (post("action") != "retir") { ?>
                    <tr>
                        <td>
                            Date de début de la session :
                        </td>
                        <td>
                            <?php input("dateDebut", "", "date", 10, "", true); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Date de fin de la session :
                        </td>
                        <td>
                            <?php input("dateFin", "", "date", 10, "", true); ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td align="right">
                        <input type="hidden" name="action" value="<?php echo post("action") ?>">
                        <input type="hidden" name="option2" value="<?php echo post("option2") ?>">
                        <input id="btnSoumettre" type="submit" value="Confirmer"/>
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST["action"]) && isset($_POST["Session"])) {
        if (post("action") == "ajout" || post("action") == "modif" && (isset($_POST["dateDebut"]) && isset($_POST["dateFin"]))) {
            if (post("Session") && post("dateDebut") && post("dateFin")) {
                if (GestionSession(post("action"), post("Session"), post("dateDebut"), post("dateFin"), $mySqli)) {
                    echo "La commande a ete effectu&eacutee";
                } else {
                    echo "La commande a echou&eacutee";
                }
            } else {
                echo "Impossible d'effectuer la commande, donn&eacutees manquantes";
            }
        } else if (post("action") == "retir") {
            if (post("Session")) {
                if(GestionSession(post("action"), post("Session"), "", "", $mySqli)){
                    echo "La commande a ete effectu&eacutee";
                } else {
                    echo "La commande a echou&eacutee";
                }
            } else {
                echo "Impossible d'effectuer la commande, donn&eacutees manquantes";
            }
        }
        }
            ?>
        </div>
        <?php
        break;
    case 2:
        ?>
<div style='border: black;border-collapse: separate;border-bottom-width: 2px;'>
    <table>
        <tr>
            <td>
                Sigle du cours :
            </td>
            <td>
                <?php
        if (post("action") == "ajout") {
            input("Cours", "", "text", 6, "", true);
        }
        else{
            $mySqli->selectionneEnregistrements("Cours");

            $resultat = mysqli_query($mySqli->cBD, $mySqli->requete);
            $tab = mysqli_fetch_array($resultat, MYSQLI_NUM);
            //var_dump($tab);
            $tab = mysqli_fetch_array($resultat, MYSQLI_NUM);
           // var_dump($tab);
            $tab = mysqli_fetch_array($resultat, MYSQLI_NUM);
           // var_dump($tab);
        }
        ?>
            </td>
        </tr>
                        <tr>
                    <td>
                        Titre du Cours : 
                    </td>
                    <td>
        <?php input("TitreCours", "", "text", 10, "", true); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Nom du Professeur : 
                    </td>
                    <td>
        <?php input("NomProf", "", "text", 10, "", true); ?>
                    </td>
                </tr>
    </table>
    
</div>
<?php
        break;
    case 3:
        break;
    case 4:
        break;
    case 5:
        break;
    case 6:
        break;
}

?>
<button name="btnConfirmer" title="Confirmer" type="submit">Confirmer</button>
<?php

require_once("pied-page.php");
$mySqli->deconnexion();
?>
