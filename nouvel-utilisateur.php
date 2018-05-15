<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Creation d'un nouvel utilisateur (Administrateur)";
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

detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

require_once("en-tete.php");
?>
<form id="creerUtil" method="post" style="font-family: Poppins-Regular;">
    <table class="sTableau">
        <tr>
            <td>
                Nom d'utilisateur :
            </td><td>
                <?php input("nomUtilisateur", "", "text", 25, "", true); ?>
            </td>
            <td>
                <?php
                if (empty(post("nomUtilisateur")) && isset($_POST["nomUtilisateur"]))
                    echo "<div class=\"sRouge\"> Entrez un nom d'utilisateur! </div>"
                    ?>
            </td>
        </tr>
        <tr>
            <td>
                Mot de passe :
            </td><td>
                <?php input("motDePasse", "", "password", 15, "", true); ?>
            <td>
                <input type="checkbox" class="sButton" onchange="document.getElementById('motDePasse').type = this.checked ? 'text' : 'password'" style="height:10px; width:10px; left: 100">Montrer le mot de passe
            </td>
            </td>
            <td>
                <?php
                if (empty(post("motDePasse")) && isset($_POST["motDePasse"]))
                    echo "<div class=\"sRouge\"> Entrez un mot de passe! </div>"
                    ?>
            </td>
            <td>  </td>
        </tr>
        <tr>
            <td>
                Statut admin : 
            </td><td>
                <select name="StatutAdmin">
                    <option value="0" <?php if (isset($_POST["formTableRef"])) echo "selected"; ?>>Utilisateur</option>
                    <option value="1" <?php if (!isset($_POST["formTableRef"])) echo "selected"; ?>>Administrateur</option>
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
            <td>
                <?php
                if (empty(post("NomComplet")) && isset($_POST["NomComplet"]))
                    echo "<div class=\"sRouge\"> Entrez un nom complet! </div>";
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Courriel :
            </td><td>
                <?php input("Courriel", "", "text", 30, "", true); ?>
            </td>
            <td>
                <?php
                if (empty(post("Courriel")) && isset($_POST["Courriel"]))
                    echo "<div class=\"sRouge\"> Entrez un courriel! </div>"
                    ?>
            </td>
        </tr>
        <tr>
            <td align="right">

            </td>
            <td align="right">
                <input type="button" class="sButton" value="Retour" onclick="<?php
                echo isset($_POST["formTableRef"]) ?
                        "window.location.href='gestion-tables-references.php'" :
                        "window.location.href='gestion-documents-administrateur.php'"
                ?>">
                <input id="btnCreer" class="sButton" name="btnCreer" type="submit" value="Créer Utilisateur">
            </td>
        </tr>
    </table>
</form>

<?php
if (post("nomUtilisateur") && post("motDePasse") && post("NomComplet") && post("Courriel")) {
    $mySqli = new mysql("", $strInfosSensibles);

    $nbAdmins = mysqli_query($mySqli->cBD, "SELECT count(statutAdmin) FROM Utilisateur where StatutAdmin = 1");
    $count = $nbAdmins->fetch_row();

    if ((intval($count[0]) < 9 && intval($count[0]) > 0) || (intval($count[0]) == 0 && post("StatutAdmin") == "1")) {
        if (preg_match("/\w{1,2}\.\w{2,25}/", post("nomUtilisateur")) && preg_match("/.{3,25}/", post("motDePasse")) &&
                preg_match("/\D+, \D+/", post("NomComplet")) && valideCourriel(post("Courriel"))) {
            $mySqli->insereEnregistrement("utilisateur", post("nomUtilisateur"), post("motDePasse"), post("StatutAdmin"), post("NomComplet"), post("Courriel"));

            $mySqli->OK ? ecrit("<p class=\"sVert\"> Nouvel utilisateur cr&eacute&eacute</p>") :
                            ecrit("<p class=\"sRouge\"> Cr&eacuteation nouvel utilisateur &eacutechou&eacute </p>");
        } else {
            ecrit("<p class=\"sRouge\"> Données non valides !</p>");
        }

        if ($mySqli->OK && intval($count[0]) == 0) {
            header("location: gestion-documents-administrateur.php");
        }
    } else if (intval($count[0]) > 9) {
        ecrit("<p class=\"sRouge\"> Cr&eacuteation nouvel utilisateur &eacutechou&eacute, plus que 9 administrateur. </p>");
    } else {
        ecrit("<p class=\"sRouge\"> Cr&eacuteation nouvel utilisateur &eacutechou&eacute, il doit avoir au moin 1 administrateur. </p>");
    }
    $mySqli->deconnexion();
}

require_once("pied-page.php");
?>
<script>
    function switchVisible(objChkBox) {
        if (objChkBox.checked) {
            document.getElementById("motDePasse").type = "text";
        }
        else {
            document.getElementById("motDePasse").type = "password";
        }
    }
</script>

