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
    if (post("action") == "ajout" || !isset($_POST["action"]))
        echo "checked";
    ?>
                > Ajouter
    <br> <input type="radio" id="radBtn" name="action"
                value="modif" <?php if (post("action") == "modif") echo "checked"; ?>> Modifier
    <br> <input type="radio" id="radBtn" name="action"
                value="retir" <?php if (post("action") == "retir") echo "checked"; ?>> Retirer
    <br> <br>
    <select name="option2" id="option" onchange="">
        <option value="1" <?php if (post("option2") == "1") echo "selected" ?>>1. Gestion des sessions d'étude
        <option value="2" <?php if (post("option2") == "2") echo "selected" ?>>2. Gestion des cours
        <option value="3" <?php if (post("option2") == "3") echo "selected" ?>>3. Gestion des cours-sessions
        <option value="4" <?php if (post("option2") == "4") echo "selected" ?>>4. Gestion des catégories de documents
        <option value="5" <?php if (post("option2") == "5") echo "selected" ?>>5. Gestion des utilisateurs</option>
    </select>
    <br><br>
    <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
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
                                echo creerSelectHTMLAvecRequete("Session", "Description", "", "selectSession", "Session", "", "", $mySqli);
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if (post("action") != "retir") { ?>
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
                            echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                        } else {
                            echo "<div class='sErreur'>La commande a echou&eacutee</div>";
                        }
                    } else {
                        echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                    }
                } else if (post("action") == "retir") {
                    if (post("Session")) {
                        if (GestionSession(post("action"), post("Session"), "", "", $mySqli)) {
                            echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                        } else {
                            echo "<div class='sErreur'>La commande a echou&eacutee</div>";
                        }
                    } else {
                        echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                    }
                }
            }
            ?>
        </div>
        <?php
        break;
    case 2:
        ?>
        <div>
            <form id="GestionCours" method="post" action="">
                <table>
                    <tr>
                        <td>
                            Sigle du cours :
                        </td>
                        <td>
                            <?php
                            if (post("action") == "ajout") {
                                input("Sigle", "", "text", 7, "", true);
                            } else {
                                echo creerSelectHTMLAvecRequete("Cours", "Sigle", "", "selectCours", "Sigle", "", "", $mySqli);
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if (post("action") != "retir") { ?>
                        <tr>
                            <td>
                                Titre du cours :
                            </td>
                            <td>
            <?php input("Titre", "", "text", 50, "", true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nom du professeur :
                            </td>
                            <td>
            <?php input("NomProf", "", "text", 50, "", true); ?>
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
        if (isset($_POST["action"]) && isset($_POST["Sigle"])) {
            if (post("action") == "ajout" || post("action") == "modif" && (isset($_POST["Titre"]) && isset($_POST["NomProf"]))) {
                if (post("Sigle") && post("Titre") && post("NomProf")) {
                    if (GestionCours(post("action"), post("Sigle"), post("Titre"), post("NomProf"), $mySqli)) {
                        echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                    } else {
                        echo "<div class='sErreur'>La commande a echou&eacutee car un champ est vide</div>";
                    }
                } else {
                    echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                }
            } else if (post("action") == "retir") {
                if (post("Sigle")) {

                    if (GestionCours(post("action"), post("Sigle"), "", "", $mySqli)) {
                        echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                    } else {
                        echo "<div class='sErreur'>La commande a echou&eacutee car les valeurs rentrées ne respecte pas les regles admises</div>";
                    }
                } else {
                    echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                }
            }
        }
        ?>
        </div>
            <?php
            break;
        case 3:
            ?>
        <div>
            <form id="GestionSession" method="post" action="">
                <table>
        <?php if (post("action") == "ajout") { ?>
                        <tr>
                            <td>
                                Session :
                            </td>
                            <td>
            <?php
            echo creerSelectHTMLAvecRequete("Session", "Description", "", "selectSession", "session", "", "", $mySqli);
            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Cours :
                            </td>
                            <td>
            <?php
            echo creerSelectHTMLAvecRequete("Cours", "Sigle", "", "selectCours", "cours", "", "", $mySqli);
            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nom prof :
                            </td>
                            <td>
            <?php
            echo creerSelectHTMLAvecRequete("Utilisateur", "NomComplet", "C=StatutAdmin=1", "selectProf", "prof", "", "", $mySqli);
            ?>
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
        </div>
        <?php
        if (post("action") == "ajout") {
            
        }
        break;
    case 4:
        break;
    case 5:
        break;
    case 6:
        break;
}

require_once("pied-page.php");
$mySqli->deconnexion();
?>
