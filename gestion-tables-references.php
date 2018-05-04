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

session_start();
detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
?>
<form id="action" method="post" action="" style="font-family: Poppins-Regular;height:680px">
    <ul> <li> <input type="radio" id="a-option" name="action" value="ajout"
    <?php
    if (post("action") == "ajout" || !isset($_POST["action"]))
        echo "checked";
    ?> 
                     >  <label for="a-option">Ajouter</label><div class="check"></div></li><li>
    <input type="radio" id="m-option" name="action"
           value="modif" <?php if (post("action") == "modif") echo "checked"; ?>> <label for="m-option">Modifier</label><div class="check"><div class="inside"></div></div></li><li>
    <input type="radio" id="r-option" name="action"
           value="retir" <?php if (post("action") == "retir") echo "checked"; ?>>  <label for="r-option">Retirer</label><div class="check"><div class="inside"></div></div></li>

    </ul>
    <select name="option2" id="option" onchange="" class="sList" style="position:fixed; top:200px; left:100px">
        <option value="1" <?php if (post("option2") == "1") echo "selected" ?>>1. Gestion des sessions d'étude
        <option value="2" <?php if (post("option2") == "2") echo "selected" ?>>2. Gestion des cours
        <option value="3" <?php if (post("option2") == "3") echo "selected" ?>>3. Gestion des cours-sessions
        <option value="4" <?php if (post("option2") == "4") echo "selected" ?>>4. Gestion des catégories de documents
        <option value="5" <?php if (post("option2") == "5") echo "selected" ?>>5. Gestion des utilisateurs</option>
    </select>
    <br><br>
    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour" style="position:fixed; top:280px; left:600px">
    <input class="sButton" id="InpValider" type="submit" value="Valider choix" style="position:fixed; top:200px; left:600px">
</form>

<?php
switch (post("option2")) {
    case 1:
        ?>
        <div>
            <form id="GestionSession" method="post" action="" style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
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
            <form id="GestionCours" method="post" action="" style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
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
            <form id="GestionSession" method="post" action="" style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
                <table>
                    <?php if (post("action") == "modif" || post("action") == "retir") { ?>
                        <tr>
                            <td>
                                Cours-session :
                            </td>
                            <td>
                                <?php
                                echo creerSelectHTMLAvecRequete("CoursSession", "coursSession", "", "selectCoursSession", "coursSession", "", "", $mySqli);
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    if (post("action") == "ajout" || post("action") == "modif") {
                        ?>
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
        if (post("action") == "ajout" && (isset($_POST["session"]) || isset($_POST["coursSession"]))) {
            if (post("session") && post("cours") && post("prof")) {
                if (gestionCoursSession(post("action"), "", post("session"), post("cours"), post("prof"), $mySqli)) {
                    echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                } else {
                    echo "<div class='sErreur'>La commande a echou&eacutee donn&eacutees non valides</div>";
                }
            } else {
                echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
            }
        } else if (post("action") == "modif") {
            if (isset($_POST["coursSession"]) && isset($_POST["session"]) && post("cours") && post("prof")) {
                if (gestionCoursSession(post("action"), post("coursSession"), post("session"), post("cours"), post("prof"), $mySqli)) {
                    echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                } else {
                    echo "<div class='sErreur'>La commande a echou&eacutee donn&eacutees non valides</div>";
                }
            } else {
                echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
            }
        } else if (post("action") == "retir" && isset($_POST["coursSession"])) {
            if (post("coursSession")) {
                if (gestionCoursSession(post("action"), post("coursSession"), "", "", "", $mySqli)) {
                    echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                } else {
                    echo "<div class='sErreur'>La commande a echou&eacutee donn&eacutees non valides</div>";
                }
            } else {
                echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
            }
        }
        break;
    case 4:
        ?>
        <div>
            <form id="GestionCategorie" method="post" action="" style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
                <table>
                    <tr>
                        <?php
                        if (post("action") != "ajout") {
                            ?>
                            <td>
                                Catégorie :
                            </td>
                            <td>
                                <?php
                                echo creerSelectHTMLAvecRequete("Categorie", "Description", "", "selectCategorie", "Description", "", "", $mySqli);
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if (post("action") != "retir") { ?>
                        <tr>
                            <td>
                                Description :
                            </td>
                            <td>
                                <?php input("DescriptionModif", "", "text", 50, "", true); ?>
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
            if (isset($_POST["action"]) && (isset($_POST["DescriptionModif"]) || isset($_POST["Description"]))) {
                if (post("action") == "ajout") {
                    if (post("DescriptionModif") && !empty(post("DescriptionModif"))) {
                        if (GestionCategorieDocument(post("action"), post("DescriptionModif"), $mySqli)) {
                            echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                        } else {
                            echo "<div class='sErreur'>La commande a echou&eacutee</div>";
                        }
                    } else {
                        echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                    }
                } else if (post("action") == "modif") {
                    if (post("Description") && post("DescriptionModif")) {
                        if (GestionCategorieDocument(post("action"), post("Description"), $mySqli, post("DescriptionModif"))) {
                            echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                        } else {
                            echo "<div class='sErreur'>La commande a echou&eacutee car un champ est vide</div>";
                        }
                    } else {
                        echo "<div class='sErreur'>Impossible d'effectuer la commande, donn&eacutees manquantes</div>";
                    }
                } else if (post("action") == "retir") {
                    if (post("Description")) {

                        if (GestionCategorieDocument(post("action"), post("Description"), $mySqli)) {
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
    case 5:
        if (post("action") == "ajout") {
            ?>
            <form id='gestionUtilisateur' method="post" value="1" action='nouvel-utilisateur.php' style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
                <input name="formTableRef" type="hidden" value="1">
            </form>
            <script type="text/javascript">
                document.getElementById('gestionUtilisateur').submit();
            </script>
            <?php
        } else if (post("action") == "modif") {
            ?>
            <div>
                <form id='frmModifUtil' method="post" action='' style="font-family: Poppins-Regular">
                    <table>
                        <tr>
                            <td>
                                Nom d'utilisateur : 
                            </td>
                            <td>
                                <?php echo creerSelectHTMLAvecRequete("utilisateur", "NomUtilisateur", "", "selectNomUtil", "selectNomUtil", "", "", $mySqli) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nom d'utilisateur :
                            </td><td>
                                <?php input("nomUtil", "", "text", 25, "", true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Mot de passe :
                            </td><td>
                                <?php input("motDePasse", "", "password", 15, "", true); ?>
                            <td>
                               <input type="checkbox" class="sButton" onchange="document.getElementById('motDePasse').type = this.checked ? 'text' : 'password'" style="height:10px; width:10px;">Montrer le mot de passe
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Statut admin :
                            </td><td>
                                <select name="StatutAdmin">
                                    <option value="0" selected>Utilisateur</option>
                                    <option value="1" >Administrateur</option>
                                </select>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nom complet :
                            </td><td>
                                <?php input("NomComplet", "", "text", 30, "", true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Courriel :
                            </td><td>
                                <?php input("Courriel", "", "text", 30, "", true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="right">
                                <input type="hidden" name="action" value="<?php echo post("action") ?>"/>
                                <input type="hidden" name="option2" value="<?php echo post("option2") ?>"/>
                                <input id='soumettre' type='submit' value='Choisir utilisateur' >
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php
            if (isset($_POST["selectNomUtil"]) && isset($_POST["nomUtil"]) && isset($_POST["motDePasse"]) &&
                    isset($_POST["NomComplet"]) && isset($_POST["Courriel"])) {
                if (post("selectNomUtil") && post("nomUtil") && post("motDePasse") && post("NomComplet") && post("Courriel")) {
                    if (gestionUtilisateur(post("action"), post("selectNomUtil"), post("nomUtil"), post("motDePasse"), post("StatutAdmin"), post("NomComplet"), post("Courriel"), $mySqli)) {
                        echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                    } else {
                        echo "<div class='sErreur'>La commande a echou&eacutee donn&eacutees non valides</div>";
                    }
                } else {
                    echo "<div class='sErreur'>Les donn&eacutees ne sont pas compl&egravestes</div>";
                }
            }
        } else if (post("action") == "retir") {
            ?>
            <div>
                <form id='frmModifUtil' method="post" action='' style="font-family: Poppins-Regular; position:fixed; top:280px; left:100px">
                    <table>
                        <tr>
                            <td>
                                Nom d'utilisateur : 
                            </td>
                            <td>
                                <?php echo creerSelectHTMLAvecRequete("utilisateur", "NomUtilisateur", "", "selectNomUtil", "selectNomUtil", "", "", $mySqli) ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="right">
                                <input type="hidden" name="action" value="<?php echo post("action") ?>"/>
                                <input type="hidden" name="option2" value="<?php echo post("option2") ?>"/>
                                <input id='soumettre' type='submit' value="Supprimer l'utilisateur" >
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php
            if (post("selectNomUtil")) {
                $nbAdmins = mysqli_query($mySqli->cBD, "SELECT count(statutAdmin) FROM Utilisateur where StatutAdmin = 1");
                $count = $nbAdmins->fetch_row();
                
                
                if ($count[0] > 1) {
                    if (gestionUtilisateur(post("action"), post("selectNomUtil"), "", "", "", "", "", $mySqli)) {
                        echo "<div class='sVert'>La commande a &eacutet&eacute effectu&eacutee</div>";
                    } else {
                        echo "<div class='sErreur'>La commande a echou&eacutee donn&eacutees non valides</div>";
                    }
                } else {
                    echo "<div class='sErreur'>Il doit avoir au moins 1 administrateur</div>";
                }
            }
            break;
        }
}

require_once("pied-page.php");
$mySqli->deconnexion();
?>
