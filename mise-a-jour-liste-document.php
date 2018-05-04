<?php
header("Access-Control-Allow-Origin: *");

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
        var_dump($infosCoursSession);
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
    
    <input type="submit" class='sButton' id="BtnCoursSession" value="Selection" style="font-family: Poppins-Regular;font-size:16px;top:275;left:250" >
    
    <br>
    <br>
    <input class="sButton" id="btnRetour" style="font-family: Poppins-Regular;font-size:16px;top:300;left:250" 
           type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'" value="Retour">
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
                <th>Description</th>
                <th>Nombre de pages</th>
                <th>Catégorie</th>
                <th>Numéro version</th>
                <th>Date version</th>
                <th>Hyper lien</th>
                <th>Ajouté par</th>
                <th>Action</th>
            </tr>
            <tr>
                <!-- Session  -->
                <td>
                    <?php echo $infosCoursSession["Session"]; ?>
                </td>
                <!-- SigleCours  -->
                <td>
                    <?php echo $infosCoursSession["Sigle"]; ?>
                </td>
                <!-- DateCours  -->
                <!-- NoSequence  -->
                <!-- DateDebut  -->
                <!-- DateFin  -->
                <!-- Titre  -->
                <!-- Description  -->
                <!-- NbPages  -->
                <!-- Categorie  -->
                <!-- NoVersion  -->
                <!-- DateVersion  -->
                <!-- HyperLien  -->
                <!-- AjoutePar  -->
                <!-- Action  -->
                
            </tr>
            
        </table>
        
        
        
        
    </div>
</form>





<script>
    document.getElementById("selectCoursSession").value = "<?php echo post("coursSession"); ?>";
</script>


<?php
require_once 'pied-page.php';
?>
