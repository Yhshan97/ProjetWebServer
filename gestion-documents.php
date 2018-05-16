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

    <div id="divSaisie" <?php echo session("connecteeUtil") == false ? "style=\"height: 500px;\"" : "style=\"display:none\"" ?>>
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

<div <?php echo session("connecteeUtil") != true ? "style=\"display:none\"" : "" ?>
        style="font-family: Poppins-Regular; margin-left: 40%; margin-top: 150px; margin-bottom: 100px;">
    <label for="Jour">Bonjour <b><?php echo $_SESSION["NomComplet"] ?></b>, vous désirez ...</label>
    <br/>
    <span id="spanDescription" class="sGras"></span>
    <br/><br/>

    <form id="saisieChoix" name="choix" method="POST" action="">
        <?php echo creerSelectAvecValeur("courssession", "coursSession","","cours","cours","sList",post("cours"),"",$mySqli); ?>
        <br>
        <br>
        <input type="submit" value="Valider le choix" class="sButton">
    </form>
</div>


<?php if (post("cours") && post("cours") != " -------- ") { ?>
    <div style="overflow:auto; width: 1600px; margin-left: 30px;">
        <?php
        $mySqli2 = new mysql("",$strInfosSensibles);
        $mySqli->selectionneEnregistrements("courssession","C=coursSession='". post("cours") ."'");

        $strSessionSelectionne = $mySqli->contenuChamp(0,"Session");
        $strCoursSelectionne = $mySqli->contenuChamp(0,"Sigle");
        $strProfCoursSelectionne = $mySqli->contenuChamp(0,"NomProf");
        $mySqli2->selectionneEnregistrements("cours","C=Sigle='$strCoursSelectionne'");
        $strNomCoursSelectionne = $mySqli2->contenuChamp(0,"Titre");

        $mySqli->requete = "SELECT * FROM Document WHERE Sigle='$strCoursSelectionne' AND Session='$strSessionSelectionne' ORDER BY DateCours, Categorie, Titre";
        $mySqli->listeEnregistrements = mysqli_query($mySqli->cBD,$mySqli->requete);
        $mySqli->nbEnregistrements = mysqli_num_rows($mySqli->listeEnregistrements);


        $mySqli2->requete = "SELECT * FROM privilege WHERE NomUtilisateur='".$_SESSION["nomUtilisateur"]."' AND coursSession='". post("cours")."'";
        $mySqli2->listeEnregistrements = mysqli_query($mySqli2->cBD,$mySqli2->requete);
        $mySqli2->nbEnregistrements = mysqli_num_rows($mySqli2->listeEnregistrements);

        //$mySqli->selectionneEnregistrements("document", "C=Sigle='" . $strCoursSelectionne . "'", "T=DateCours, Categorie, Titre");
        if($mySqli2->nbEnregistrements == 1) {
            if ($mySqli->nbEnregistrements > 0) {
                $booEnregistrements = true;
                echo "<table style='overflow: hidden' class=\"tableBordure\">";
                echo "<tr><td align='center' class='sGras' colspan='9' rowspan='2' style='font-size: 25px;'>". strtoupper(post("cours")) ." ".strtoupper($strNomCoursSelectionne)." de ". strtoupper($strProfCoursSelectionne)."</td></tr>";
                echo "<tr class='sEntete tableBordure'>";
                echo "<tr class='sEntete tableBordure'>";
                echo "<td class=\"tableBordure \">Numéro du document</td>";
                echo "<td class=\"tableBordure\" style='width: 50px;'>Date du cours</td>";
                echo "<td class=\"tableBordure\">Numéro de séquence du document </td>";
                echo "<td class=\"tableBordure\">Catégorie du document</td>";
                echo "<td class=\"tableBordure\">Titre du document</td>";
                echo "<td class=\"tableBordure\">Description du document</td>";
                echo "<td class=\"tableBordure\">Pages</td>";
                echo "<td class=\"tableBordure\">Date de derniere mise à jour du document</td>";
                echo "<td class=\"tableBordure\">Nombre de jour restants du document</td>";
                echo "</tr>";

                for ($i = 0; $i < $mySqli->nbEnregistrements; $i++) {
                    echo "<tr style=\"background-color: whitesmoke;\" >";
                    echo "<td class=\"tableBordure\">" . ($i + 1) . "</td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "DateCours") . "</td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "NoSequence") . "</td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "Categorie") . "</td>";
                    echo "<td class=\"tableBordure\"><a target=\"_blank\" href='televersements/" . $mySqli->contenuChamp($i, "HyperLien") . "'>" . $mySqli->contenuChamp($i, "Titre") . "</a></td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "Description") . "</td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "NbPages") . "</td>";
                    echo "<td class=\"tableBordure\">" . $mySqli->contenuChamp($i, "DateVersion") . "</td>";
                    echo "<td class=\"tableBordure\">" . ((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now") / (60 * 60 * 24)) % 1 == 0 ? intval((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now")) / (60 * 60 * 24)) + 1 : intval((strtotime($mySqli->contenuChamp($i, "DateAccesFin")) - strtotime("now")) / (60 * 60 * 24))) . " jour(s) restant(s)" . "</td>";
                }

                echo "</table>";
            }
        }else {
            echo "<label class='sBlanc'> Aucun document visible dans le cours $strCoursSelectionne </label>";
        }
        ?>

    </div>
    <?php
    }
        ?>

<?php
$mySqli->deconnexion();
$mySqli2->deconnexion();
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
    document.body.style.overflow = 'auto';
</script>
