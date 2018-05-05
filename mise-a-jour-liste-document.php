<?php
header("Access-Control-Allow-Origin: *");
session_start();
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


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("pjf_immigrants", $strInfosSensibles);
$msgCoursSessionSelect = "";
$binSelect = post("coursSession") != "";
$infosCoursSession = [];

if(isset($_POST["coursSession"])){
    if(post("coursSession") == ""){
        $msgCoursSessionSelect = "<a class=\"sRouge sGras\"> Choisissez un cours-session! </a>";
    }
    else {
        $binSelect = true;
        $mySqli->selectionneEnregistrements("coursSession");
        $infosCoursSession["coursSession"] = $mySqli->contenuChamp(0, "coursSession");
        $infosCoursSession["Sigle"] = $mySqli->contenuChamp(0, "Sigle");
        $infosCoursSession["Session"] = $mySqli->contenuChamp(0, "Session");
       // var_dump($infosCoursSession);
    }
}

?>

<form id="formListeDoc" method="post" action="">
    <div <?php echo $binSelect ? "style='display: none'" : "" ?>>
    <br>
    <label id="lbl"> Il y a <?php $mySqli->selectionneEnregistrements("courssession"); echo $mySqli->nbEnregistrements; ?> 
        cours-session<?php echo $mySqli->nbEnregistrements != 1 ? "s" : "" ?> </label>
    <br> 
    <br>

    <label> Il y a <?php $mySqli->selectionneEnregistrements("document");echo $mySqli->nbEnregistrements; ?> 
        document<?php echo $mySqli->nbEnregistrements != 1 ? "s" : "" ?> </label>
    <br> 
    <br>
    
    Cours-session : 
    <span>
        <?php 
        
        $mySqli->selectionneEnregistrements("CoursSession");
        $mySqli2 = new mysql("", $strInfosSensibles);
        
            $Tableau[0] = "--------";
            $TableauValue[0] = "";
            while ($val = mysqli_fetch_array($mySqli->listeEnregistrements, MYSQLI_ASSOC)) {
                $mySqli2->requete = "SELECT * FROM Document WHERE Sigle='" . $val["Sigle"] . "' AND Session='" . $val["Session"]."'";
                $mySqli2->listeEnregistrements = mysqli_query($mySqli2->cBD, $mySqli2->requete);
                $mySqli2->nbEnregistrements = mysqli_num_rows($mySqli2->listeEnregistrements);
                
                $str = $val["coursSession"] . " (". $mySqli2->nbEnregistrements . " document" . ($mySqli2->nbEnregistrements != 1 ? "s" : "") . ")";
                
                $Tableau[] = $str;
                $TableauValue[] = $val["coursSession"];
            }
            $strSelectHTML = "<select id='selectCoursSession' name='coursSession' class='sList'>";
            for($i=0; $i<count($Tableau); $i++) {
                $strSelectHTML .= "<option value=\"$TableauValue[$i]\">" . $Tableau[$i];
            }
            $strSelectHTML .= "</select>";
            
            echo $strSelectHTML;
        ?>
        <?php echo $msgCoursSessionSelect; ?>
    </span>
    
    <br>
    <br>
    
    <input type="submit" class='sButton' id="BtnCoursSession" value="Selection">
    
    <br>
    <br>
    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'" value="Retour">
    </div>
    
    <div <?php echo !$binSelect ? "style='display: none'" : "" ?>>
        
        <table>
            <tr class="sEntete">
                <th>Session</th>
                <th>Sigle cours</th>
                <th>Date Cours</th>
                <th>No séquence</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Titre</th>
            </tr>
            <tr>
                <!-- Session  -->
                <th>
                    <?php echo $infosCoursSession["Session"]; ?>
                </th>
                <!-- SigleCours  -->
                <th>
                    <?php echo $infosCoursSession["Sigle"]; ?>
                </th>
                <!-- DateCours  -->
                <th>
                    <input type="date" id="idDateCours" name="dateCours" >
                </th>
                <!-- NoSequence  -->
                <th>
                    <input type="number" id="idNoSequence" name="noSequence" min="0" max="20">
                </th>
                <!-- DateDebut  -->
                <th>
                    <input type="date" id="idDateDebut" name="dateAccessDebut" >
                </th>
                <!-- DateFin  -->
                <th>
                    <input type="date" id="idDateFin" name="dateAccessFin" >
                </th>
                <!-- Titre  -->
                <th>
                    <input type="text" id="idTitre" name="titre" >
                </th>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr class="sEntete">
                <th>Description</th>
                <th>Nombre de pages</th>
                <th>Catégorie</th>
                <th>Numéro version</th>
                <th>Date version</th>
                <th>Hyper lien</th>
                <th>Ajouté par</th>
            </tr>
            <tr>
                <!-- Description  -->
                <th>
                    <input type="text" id="idDescription" name="description" >
                </th>
                <!-- NbPages  -->
                <th>
                    <input type="text" id="idNbPages" name="nbPages" >
                </th>
                <!-- Categorie  -->
                <th>
                    <?php echo creerSelectHTMLAvecRequete("Categorie", "Description", "", "idCategorie", "categorie", "", "", $mySqli);  ?>
                </th>
                <!-- NoVersion  -->
                <th>
                    <input type="number" id="idNoVersion" name="noVersion" min="1" max="99" >
                </th>
                <!-- DateVersion  -->
                <th>
                    <input type="date" id="idDateVersion" name="dateVersion" >
                </th>
                <!-- HyperLien  -->
                <th>
                    <a>MODULE DE TELEVERSEMENT</a>
                </th>
                <!-- AjoutePar  -->
                <th>
                    <?php echo $_SESSION["NomComplet"];?>
                </th>
            </tr>
        </table>
        
        <input type="button" id="idbtAjout" value="Ajouter">
        </br>
        </br>
        <input class="sButton" id="btnRetour" type="button" onclick="window.location.href='mise-a-jour-liste-document'" value="Retour">
    </div>
</form>



<script>
    document.getElementById("selectCoursSession").value = "<?php echo post("coursSession"); ?>";
</script>


<?php
require_once 'pied-page.php';
?>
