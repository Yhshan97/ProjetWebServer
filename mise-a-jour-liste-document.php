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

if(isset($_POST["coursSession"])){
    if(post("coursSession") == "--------"){
        $msgCoursSessionSelect = "<a class=\"sRouge sGras\"> Choisissez un cours-session! </a>";
    }
    else {
        
    }
}


var_dump($_POST);
?>

<script>
function coursSession(){
    alert("allo");
}
</script>
    
<form id="formListeDoc" method="post" action="">
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
        
            $Tableau[0] = " -------- ";
            
            while ($val = mysqli_fetch_array($mySqli->listeEnregistrements, MYSQLI_ASSOC)) {
                $mySqli2->requete = "SELECT * FROM Document WHERE Sigle='" . $val["Sigle"] . "' AND Session='" . $val["Session"]."'";
                $mySqli2->listeEnregistrements = mysqli_query($mySqli2->cBD, $mySqli2->requete);
                $mySqli2->nbEnregistrements = mysqli_num_rows($mySqli2->listeEnregistrements);
                
                $str = $val["coursSession"] . " (". $mySqli2->nbEnregistrements . " document" . ($mySqli2->nbEnregistrements != 1 ? "s" : "") . ")";
                
                $Tableau[] = $str;
            }
            
            echo creerSelectHTML("selectCoursSession", "coursSession", "sList", "", $Tableau);
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


</form>

<?php
require_once 'pied-page.php';
?>
