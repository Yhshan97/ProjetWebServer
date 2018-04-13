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

$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);
$strCoursSession = post("coursSession");
$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);


$mySqli->requete = "Select Session,Sigle from courssession where coursSession =" . "'" . $strCoursSession . "'";
$mySqli->listeEnregistrements = mysqli_query($mySqli->cBD, $mySqli->requete);

session_start();
?>
<form id="document" method="post" action="">

    <table>
        Veuillez saisir les données du nouveau document à ajouter dans <?php echo post("coursSession"); ?> 
        <tr>
            <td>
                Session : 
            </td>
            <td> <input class="" type="text" value="<?php echo $mySqli->contenuChamp(0, "Session"); ?>"</td>
        </tr>
        <tr>
            <td>
                Sigle : 
            </td>
            <td>
                <input class="" type="text" value="<?php echo $mySqli->contenuChamp(0, "Sigle"); ?>"
            </td>
        </tr>
        <tr>
            <td>
                DateCours :   
            </td>
            <td>
                <select id="ddlNoSequence" name="ddlNoSequence" class="">
                    <option value=""></option>
                    <script type="text/javascript">
                        for (var i = 1; i <= 20; i++) {
                            document.write('<option value="' + i + '">' + i + '</option>')
                        }
                    </script>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                DateAccesDebut : 
            </td>
            <td>
                <?php input("dateAccesDebut", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                DateAccesFin : 
            </td>
            <td>
                 <?php input("dateAccesFin", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                Titre :  
            </td>
            <td>
                <?php input("titre", "", "text", 50, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                Description :  
            </td>
            <td>
                <?php input("description", "", "text", 50, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                NbPages :  
            </td>
            <td>
                <?php input("nbPages", "", "text", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                Catégorie :  
            </td>
            <td>
                <?php echo creerSelectHTMLAvecRequete("categorie", "Description", "", "selectCategorie", "Description", "", "", $mySqli); ?>
            </td>
        </tr>
        <tr>
            <td>
                NoVersion :  
            </td>
            <td>
                <select id="noVersion" name="noVersion" class="">
                    <option value=""></option>
                    <script type="text/javascript">
                        for (var i = 1; i <= 5; i++) {
                            document.write('<option value="' + i + '">' + i + '</option>')
                        }
                    </script>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                DateVersion :  
            </td>
            <td>
                <?php input("dateVersion", "", "date", 10, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                HyperLien :  
            </td>
            <td>
                <?php input("hyperLien", "", "text", 50, "", true); ?>
            </td>
        </tr>
        <tr>
            <td>
                AjoutePar :  
            </td>
            <td>
                <input class="" type="text" value="<?php echo $_SESSION["NomComplet"] ?>"
            </td>
        </tr>
        <tr>
            <td>
                Action :      
            </td>
            <td>
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
            </td>
        </tr>

    </table>
    <input class="" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
</form>
