<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Gestion des documents";
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
$booEnregistrements = false;

if (post("nomUtilisateur") && post("motDePasse")) {
    if (connexion(post("nomUtilisateur"), post("motDePasse"), 0, $mySqli)) {
        $booConnexion = true;
        $_SESSION["connecteeUtil"] = true;
        $_SESSION["nomUtilisateur"] = post("nomUtilisateur");
    }
}
?>
<form id="frmSaisie" method="POST" action="" style="font-family: Poppins-Regular;">

    <div id="divSaisie" <?php echo session("connectee") == false ? "" : "style=\"display:none\"" ?>>
        <span class="login100-form-logo"></span>
        <table class="sTableau sMilieu" style="top: 25%; text-align: center;">
            <td class="sBlanc" style="left:910; position: fixed;top:26.5%"> Identifiant </td>
            <td style="position: fixed;left:880;top: 30%">
                <?php input("nomUtilisateur", "sButton sCentrer", "text", 15, post("nomUtilisateur"), true); ?>
            </td>
        </table>
        <label style="top:50%;left:47%;position: fixed">
            <?php
            if (empty(post("nomUtilisateur")) && isset($_POST["nomUtilisateur"]))
                echo "<span class=\"sRouge\"> Entrez un nom d'utilisateur! </span>"
                ?>
        </label>

        <table class="sTableau sMilieu" style="top: 37%; left:1095">
            <td class="sBlanc" style="position:fixed;top: 36.5%; left:900"> Mot de passe </td>
            <tr>
                <td style="position:fixed; left:880; top:40%;">    
                    <?php input("motDePasse", "sButton sCentrer", "password", 15, "", true); ?>
                </td>
                <td  style="position:fixed; left:880; top:46%; font-size: 14px">
                    <input type="checkbox" class="sButton" onchange="document.getElementById('motDePasse').type = this.checked ? 'text' : 'password'" style="height:10px; width:10px;">Montrer le mot de passe
                </td>
            <label style="top:52%;left:47%;position: fixed">
                <?php
                if (empty(post("motDePasse")) && isset($_POST["motDePasse"]))
                    echo "<div class=\"sRouge\"> Entrez un mot de passe! </div>"
                ?>
            </label>
            </tr>
        </table>
        <table class="sTableau sMilieu" style="top: 330; left:1300;">
            <tr>
                <td class=\"sBorder\"></td>
                <td class=\"sBorder\">
                    <input id="btnConnexion" name="btnConnexion" type="submit" value="Connexion" onclick="" class="sButton sGrand" style=" font-family: Poppins-Regular;">
                </td>
            </tr>
        </table>
    </div>
</form>

<?php if (post("cours") && post("cours") != " -------- ") { ?>
    <div>
        <?php
        $strCoursSelectionne = post("cours");
        $mySqli->selectionneEnregistrements("document", "C=Sigle='" . $strCoursSelectionne . "'T=DateCours, Categorie, Titre");
        echo $mySqli->requete;
        if ($mySqli->nbEnregistrements > 0) {
            $booEnregistrements = true;
            echo "<table>";
            echo "<tr class='sEntete sBorder'>";
            echo "<td class=\"sBorder\">Numéro du document</td>";
            echo "<td class=\"sBorder\">Date du cours</td>";
            echo "<td class=\"sBorder\">Numéro de séquence du document </td>";
            echo "<td class=\"sBorder\">Catégorie du document</td>";
            echo "<td class=\"sBorder\">Titre du document</td>";
            echo "<td class=\"sBorder\">Description du document</td>";
            echo "<td class=\"sBorder\">Nombre de pages du document</td>";
            echo "<td class=\"sBorder\">Date de derniere mise à jour du document</td>";
            echo "<td class=\"sBorder\">Nombre de jour restants du document</td>";
            echo "</tr>";

            for ($i = 0; $i < $mySqli->nbEnregistrements; $i++) {
                echo "<tr style=\"background-color: whitesmoke;\">";
                echo "<td class=\"sBorder\">" . ($i + 1) . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "DateCours") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "NoSequence") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "Categorie") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "Titre") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "Description") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "NbPages") . "</td>";
                echo "<td class=\"sBorder\">" . $mySqli->contenuChamp($i, "DateVersion") . "</td>";
                echo "<td class=\"sBorder\">" . ((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now") / (60 * 60 * 24)) % 1 == 0 ? intval((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now")) / (60 * 60 * 24)) + 1 : intval((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now")) / (60 * 60 * 24)) ) . " jour(s) restant(s)" . "</td>";
            }

            echo "</table>";
        } else {
            echo "<label class='sBlanc'> Aucun document enregistré dans le cours $strCoursSelectionne </label>";
        }
        ?>
    </div>
    <?php
    }
    if (!$booEnregistrements) {
        ?>

    <div <?php echo session("connecteeUtil") != true ? "style=\"display:none\"" : "" ?> style="font-family: Poppins-Regular; position:fixed; top:300; left:700px">
        <label for="Jour">Bonjour <b><?php echo $_SESSION["NomComplet"] ?></b>, vous désirez ...</label>
        <br/>
        <span id="spanDescription" class="sGras"></span>
        <br/><br/>

        <form id="saisieChoix" name="choix" method="POST" action="">

            <?php echo creerSelectHTMLAvecRequete("cours", "Sigle", "", "cours", "cours", "sList", "", $mySqli); ?>
            <br><br>
            <input type="submit" value="Valider le choix" class="sButton">
        </form>
    </div>
    <?php
}
?>
<?php
$mySqli->deconnexion();
require_once("pied-page.php");
?>
<script type="text/javascript">
    function password() {
        var x = document.getElementById("motDePasse");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

</script>
