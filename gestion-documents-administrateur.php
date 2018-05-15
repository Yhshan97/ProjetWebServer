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

if (post("nomUtilisateur") && post("motDePasse")) {
    if (connexion(post("nomUtilisateur"), post("motDePasse"), $mySqli)) {
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
        case "3": header("location: assigner-privileges-document.php");
            break;
        case "4": header("location: assigner-GroupeUtilisateur-courssession.php");
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
<form id="frmSaisie" method="POST" action="" style="font-family: Poppins-Regular;">
    
    <div id="divSaisie" <?php echo session("connectee") == false ? "" : "style=\"display:none\"" ?>>
        <span class="login100-form-logo"></span>
        <table class="sTableau sMilieu" style="top: 25%; text-align: center;">
            <td class="sBlanc" style="left:910; position: fixed;top:26.5%"> Identifiant </td>
            <tr>  
                <td style="position: fixed;left:880;top: 30%">
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
        <table class="sTableau sMilieu" style="top: 37%; left:1095">
            <td class="sBlanc" style="position:fixed;top: 36.5%; left:900"> Mot de passe </td>
            <tr>
                <td style="position:fixed; left:880; top:40%;">    
                    <?php input("motDePasse", "sButton sCentrer", "password", 15, "", true); ?>
                </td>
                <td  style="position:fixed; left:880; top:46%; font-size: 14px">
                    <input type="checkbox" class="sButton" onchange="document.getElementById('motDePasse').type = this.checked ? 'text' : 'password'" style="height:10px; width:10px;">Montrer le mot de passe
                </td>
                <td>
                    <?php
                    if (empty(post("motDePasse")) && isset($_POST["motDePasse"]))
                        echo "<div class=\"sErreur sMotDePasse\"> Entrez un mot de passe! </div>"
                        ?>
                </td>
            </tr>
        </table>
        <table class="sTableau sMilieu" style="top: 330; left:1300;">
            <tr>
                <td></td>
                <td>
                    <input id="btnConnexion" name="btnConnexion" type="submit" value="Connexion" onclick="" class="sButton sGrand" style=" font-family: Poppins-Regular;">
                </td>
            </tr>
        </table>
    </div>
</form>

<div <?php echo session("connectee") != true ? "style=\"display:none\"" : "" ?> style="font-family: Poppins-Regular; position:fixed; top:300; left:700px">
    <label for="Jour">Bonjour <b><?php echo $_SESSION["NomComplet"] ?></b>, vous désirez ...</label>
    <br/>
    <span id="spanDescription" class="sGras"></span>
    <br/><br/>
    <form id="saisieChoix" name="choix" method="POST" action="">

        <select name="option" id="option" class="sList" onchange="description(this)">
            <option value="0"></option>
            <option value="1">1. Mettre à jour la liste des documents</option>
            <option value="2">2. Mettre à jour les tables de référence </option>
            <option value="3">3. Assigner les privilèges d'accès aux documents </option>
            <option value="4">4. Assigner un groupe d'utilisateurs à un cours-session </option>
            <option value="5">5. Reconstruire l'arborescence des documents </option>
            <option value="6">6. Terminer l'application </option>
        </select>
        <br><br>
        <input type="submit" value="Valider le choix" class="sButton">
    </form>
</div>
<?php
$mySqli->deconnexion();
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
    function description(obj){
        switch(obj.value){
            case '0':
                document.getElementById('spanDescription').innerHTML = '';
            break;
            case '1':
                document.getElementById('spanDescription').innerHTML = ' ajouter/modifier/retirer un ou plusieurs documents.';
            break;
            case '2':
                document.getElementById('spanDescription').innerHTML = ' ajouter/modifier/retirer une ou plusieurs sessions, </br>cours, catégories de documents et/ou utilisateurs.';
                break;
            case '3':
                document.getElementById('spanDescription').innerHTML = ' assigner les privilèges d\'accès aux documents pour <br/> un ou plusieurs utilisateurs.';
                break;
            case '4':
                document.getElementById('spanDescription').innerHTML = ' ajouter une série d\'utilisateurs et les assigner à un cours-session existant.';
                break;
            case '5':
                document.getElementById('spanDescription').innerHTML = ' effectuer du ménage dans la liste de documents enregistrés.';
                break;
            case '6':
                document.getElementById('spanDescription').innerHTML = ' quitter l\'application.';
                break;
        }
    }
</script>
