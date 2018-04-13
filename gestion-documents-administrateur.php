<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Gestion des documents (Administrateur)";
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

if (post("nomUtilisateur") && post("motDePasse")){
    if (connexion(post("nomUtilisateur"), post("motDePasse"), $mySqli)){
        $booConnexion = true;
        $_SESSION["connectee"] = true;
        $_SESSION["nomUtilisateur"] = post("nomUtilisateur");
    }
}
if(isset($_POST["option"])){
    switch(post("option")){
        case "1": header("location: mise-a-jour-liste-document");
            break;
        case "2": header("location: gestion-tables-references.php");
            break;
        case "3":
            break;
        case "4": header("location: assigner-utilisateur-courssession.php");
            break;
        case "5": header("location: arborescence-document.php");
            break;
        case "6":
            session_unset();
            header("location: gestion-documents-administrateur.php");
            break;

    }
}

?>
<form id="frmSaisie" method="POST" action="">

    <div <?php echo (!isset($_SESSION["connectee"]) || session("connectee") == false) ? "" : "style=\"display:none\"" ?> >
        <table class="sTableau sMilieu" style="top: 30%">
            <tr>
                <td>
                    Nom d'utilisateur :
                </td><td>
                    <?php input("nomUtilisateur", "sButton sCentrer", "text", 15, post("nomUtilisateur"), true); ?>
                </td>
                <td>
                    <?php
                    if (empty(post("nomUtilisateur")) && isset($_POST["nomUtilisateur"]))
                        echo "<span class=\"sErreur sUtilisateur\"> Entrez un nom d'utilisateur! </span>"
                        ?>
                </td>
            </tr>
        </table>
        <table class="sTableau sMilieu" style="top: 37%; left: 51.1%">
            <tr>
                <td>
                    Mot de passe :
                </td><td>
                    <?php input("motDePasse", "sButton sCentrer", "password", 15, "", true); ?>
                </td>
                <td>
                    <?php
                    if (empty(post("motDePasse")) && isset($_POST["motDePasse"]))
                        echo "<div class=\"sErreur sMotDePasse\" style=\"margin-right: 150px;\"> Entrez un mot de passe! </div>"
                        ?>
                </td>
            </tr>
        </table>
        <table class="sTableau sMilieu" style="top: 44%; left: 54.2%">
            <tr>
                <td></td>
                <td>
                    <input id="btnConnexion" name="btnConnexion" type="submit" value="Connexion" onclick="" class="sButton">
                </td>
            </tr>
        </table>
        <table  class="sTableau sMilieu" style="top: 49.5%; left: 54.2%">
            <tr>
                <td></td>
                <td>
                    <input id="btnActualiser" name="btnActualiser" type="button" value="Actualiser" class="sButton"onclick="window.location = document.location.href;" />
                </td>
            </tr>
        </table>
    </div>
</form>

<div <?php echo session("connectee") != true ? "style=\"display:none\"" : "" ?> >
    <label for="Jour">Bienvenu(e) <?php echo session("NomComplet") ?> :) Vous désirez ...</label><br/><br>
    <form id="saisieChoix" name="choix" method="POST" action="">
        <select name="option" id="option">
            <option value="1">1. Mettre à jour la liste des documents</option>
            <option value="2">2. Mettre à jour les tables de référence </option>
            <option value="3">3. Assigner les privilèges d'accès aux documents </option>
            <option value="4">4. Assigner un groupe d'utilisateurs à un cours-session </option>
            <option value="5">5. Reconstruire l'arborescence des documents </option>
            <option value="6">6. Terminer l'application </option>
        </select>
        <br><br>
        <input type="submit" value="Valider le choix">
    </form>
</div>

<?php
$mySqli->deconnexion();
require_once("pied-page.php");
?>