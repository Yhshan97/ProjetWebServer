<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour la liste des documents";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

session_start();

if(!isset($_SESSION["NomComplet"])) {
    header('location: gestion-documents-administrateur.php');
}

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
$msgResultatAction = "";
$msgResultatAction2 = "";
$binSelect = post("coursSession") != "";
$infosCoursSession = [];

$tabNomsColonnes = ["Session","Sigle du cours", "Date remise", "No. de séquence",
    "Date début", "Date fin", "Titre","Description", "Nb. de pages",
    "Catégorie", "No. version", "Date dernière version", "Hyper lien", "Ajouté par"];

if(isset($_POST["coursSession"])){
    if(post("coursSession") == ""){
        $msgCoursSessionSelect = "<a class=\"sRouge sGras\"> Choisissez un cours-session! </a>";
    }
    else {
        $binSelect = true;
        $mySqli->selectionneEnregistrements("coursSession","C=coursSession='".post("coursSession")."'");
        $infosCoursSession["coursSession"] = $mySqli->contenuChamp(0, "coursSession");
        $infosCoursSession["Sigle"] = $mySqli->contenuChamp(0, "Sigle");
        $infosCoursSession["Session"] = $mySqli->contenuChamp(0, "Session");
        $mySqli->selectionneEnregistrements("cours","C=Sigle='". $infosCoursSession["Sigle"] ."'");
        $infosCoursSession["titreCours"] = $mySqli->contenuChamp(0, "Titre");
    }
}
if(isset($_POST["DocumentAction"])){
    if(post("DocumentAction") == "Ajouter"){        // IL MANQUE && !empty(post("hyperLien"))
        if(!empty(post("session")) && !empty(post("sigle")) && !empty(post("dateCours")) &&
            !empty(post("noSequence")) && !empty(post("dateAccessDebut")) && !empty(post("dateAccessFin")) &&
            !empty(post("titre")) && !empty(post("description")) && !empty(post("nbPages")) &&
            !empty(post("categorie")) && !empty(post("noVersion")) && !empty(post("dateVersion")) && 
            !empty($_SESSION["nomFichierAjout"]) && !empty(post("ajoutePar")))
        {
            $mySqli->insereEnregistrement("Document",post("session"),post("sigle"),post("dateCours"),post("noSequence"),post("dateAccessDebut"),
                post("dateAccessFin"),post("titre"),post("description"),post("nbPages"),post("categorie"),post("noVersion"),post("dateVersion"),
                $_SESSION["nomFichierAjout"],post("ajoutePar"));
                
            $_SESSION["nomFichierAjout"] = "";

            $msgResultatAction = $mySqli->OK ? "<span class='sVert sBlancFond'> La commande à été effectuée</span>" :
                "<span class='sBlanc sRougeFond'> Ajout pas possible. Même titre de document existe!'</span>";
        }
        else {
            $msgResultatAction = "<span class='sBlanc sRougeFond'> Ajout pas possible. Données manquantes !</span>";
        }
    }


    if(post("DocumentAction") == "Retirer"){
        $intRetir = 0;
        foreach($_POST as $item => $value){
            if(preg_match("/cbDocument.*/", $item) == 1){
                $mySqli->supprimeEnregistrements("document","Sigle='" . $infosCoursSession["Sigle"] .
                    "' AND Session='".$infosCoursSession["Session"] . "' AND Titre='". $_POST["$item"] ."'");
                $intRetir ++;
            }
        }
        for($i=0; $i<count($_POST) - 2; $i++){

        }
        $msgResultatAction2 = "<span class='sVert sBlancFond'> $intRetir document(s) ont été retirés. </span>";
    }


    if(post("DocumentAction") == "Modifier"){
        if(!empty(post("RdateCours")) && !empty(post("RnoSequence")) && !empty(post("RdateDebut")) &&
            !empty(post("RdateFin")) && !empty(post("Rtitre")) && !empty(post("Rdescription")) &&
            !empty(post("RnbPages")) && post("Rcategorie") != " -------- " && !empty(post("RnoVersion")) && !empty(post("RdateVersion")))
        {
            $mySqli->metAJourEnregistrements("document",
                "Session='"         . $infosCoursSession["Session"] . "'".
                ", Sigle='"         . $infosCoursSession["Sigle"] . "'".
                ", DateCours='"     . post("RdateCours") . "'".
                ", NoSequence='"    . post("RnoSequence") ."'".
                ", DateAccesDebut='". post("RdateDebut") ."'".
                ", DateAccesFin='"  . post("RdateFin") ."'".
                ", Titre='"         . post("Rtitre") . "'".
                ", Description='"   . post("Rdescription")."'".
                ", NbPages="        . post("RnbPages") .
                ", Categorie='"     . post("Rcategorie"). "'".
                ", NoVersion="      . post("RnoVersion").
                ",DateVersion='"    . post("RdateVersion")      . "'".
                ",HyperLien="       . "''".
                ",AjoutePar='"      . $_SESSION["NomComplet"]."'"
                ,
                "Titre='" . post("titreAvantModif"). "' AND Sigle='" . $infosCoursSession["Sigle"] .
                "' AND Session='".$infosCoursSession["Session"] . "'");


            $msgResultatAction2 = $mySqli->OK ? "<span class='sVert sBlancFond'> La commande à été effectuée</span>" :
                "<span class='sBlanc sRougeFond'> Modification pas possible. Même titre de document existe!'</span>";
        }
        else {
            $msgResultatAction2 = "<span class='sBlanc sRougeFond'> Modification pas possible. Données manquantes !</span>";
        }
    }
}

?>

<form id="ajoutDocument" method="post" action="" enctype="multipart/form-data">
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

    <?php
    if($binSelect) {
        ?>

    <div>
        </br>
        </br>

        <table style="width: 1280px;">
            <td colspan="6" align="center" class="sGras" style="font-size: 32px;">
                <?php
                echo $infosCoursSession["coursSession"] . "<br/>" .
                    "<span style='font-size: 24px;'>".$infosCoursSession["titreCours"] . "</span>";
                ?>
            </td>
            <tr><td></td></tr>
            <tr class="sEntete">
                <?php for($i1=2;$i1<8;$i1++){
                    echo "<th>$tabNomsColonnes[$i1]</th>";
                } ?>
            </tr>
            <tr>
                <!-- Session HIDDEN -->
                <input type="hidden" name="session" value="<?php echo $infosCoursSession["Session"] ?>">
                <!-- Sigle HIDDEN -->
                <input type="hidden" name="sigle" value="<?php echo $infosCoursSession["Sigle"] ?>">

                <!-- DateCours  -->
                <th>
                    <input type="date" id="idDateCours" name="dateCours" value="<?php echo post("dateCours") ?>">
                </th>
                <!-- NoSequence  -->
                <th>
                    <input type="number" id="idNoSequence" name="noSequence" min="0" max="20" value="<?php echo post("noSequence") ?>">
                </th>
                <!-- DateDebut  -->
                <th>
                    <input type="date" id="idDateDebut" name="dateAccessDebut" value="<?php echo post("dateAccessDebut") ?>">
                </th>
                <!-- DateFin  -->
                <th>
                    <input type="date" id="idDateFin" name="dateAccessFin" value="<?php echo post("dateAccessFin") ?>">
                </th>
                <!-- Titre  -->
                <th>
                    <input type="text" id="idTitre" name="titre" value="<?php echo post("titre") ?>">
                </th>
                <!-- Description  -->
                <th>
                    <input type="text" id="idDescription" name="description" value="<?php echo post("description") ?>">
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
                <?php for($i2=8;$i2<count($tabNomsColonnes);$i2++){
                    echo "<th>$tabNomsColonnes[$i2]</th>";
                } ?>
            </tr>
            <tr>
                <!-- NbPages  -->
                <th>
                    <input type="number" id="idNbPages" name="nbPages" min="1" max="999" value="<?php echo post("nbPages") ?>">
                </th>
                <!-- Categorie  -->
                <th>
                    <?php echo creerSelectAvecValeur("categorie","Description","","selectCategorie","categorie","",post("categorie"),"",$mySqli); ?>
                </th>
                <!-- NoVersion  -->
                <th>
                    <input type="number" id="idNoVersion" name="noVersion" min="1" max="99" value="<?php echo post("noVersion") ?>">
                </th>
                <!-- DateVersion  -->
                <th>
                    <input type="date" id="idDateVersion" name="dateVersion" value="<?php echo post("dateVersion") ?>">
                </th>
                <!-- HyperLien  -->
                <th>
                    <button id="btnHyperLien" name="btnHyperLien" onclick="window.open('televersement.php','window','width=400,height=150')"> 
                        Choisir un fichier</button><br/>
                        <?php echo $_SESSION["nomFichierAjout"]; ?>
                </th>
                <!-- AjoutePar  -->
                <th>
                    <?php echo $_SESSION["NomComplet"];?>
                    <input type="hidden" name="ajoutePar" value="<?php echo $_SESSION["NomComplet"]; ?>">
                </th>
            </tr>
        </table>

        </br>
        <input type="submit" id="idbtAjout" class="sButton" name="DocumentAction" value="Ajouter">
        </br>
        </br>
        <?php echo $msgResultatAction; ?>
        </br>
    </div>
</form>


    </br>
    </br>
    </br>
    </br>
    <input type="hidden" name="coursSession" value="<?php echo $infosCoursSession["coursSession"]; ?>">
    <?php if($binSelect) {?>
    <div style="overflow:auto; width: 1350px; height:800px;" >
        <table>
            <td colspan="7" align="center" class="sGras" style="font-size: 32px;">
                <?php
                echo $infosCoursSession["coursSession"] . "<br/>" .
                    "<span style='font-size: 24px;'>".$infosCoursSession["titreCours"] . "</span>";
                ?>
            </td>
            <td colspan="6" align="center" class="sGras" style="font-size: 32px;">
                <?php
                echo $infosCoursSession["coursSession"] . "<br/>" .
                    "<span style='font-size: 24px;'>".$infosCoursSession["titreCours"] . "</span>";
                ?>
            </td>
        <?php
            // Boucle pour la ligne d'entete
            echo "<tr class=\"sEntete\">";
            echo "<th></th>";
            for($i1=2;$i1<count($tabNomsColonnes);$i1++){
                echo "<th>$tabNomsColonnes[$i1]</th>";
            }
            echo "</tr>";

            // Boucle pour le nombre de documents
        $mySqli->requete = "SELECT * FROM document WHERE Session='". $infosCoursSession["Session"] . "' AND Sigle='". $infosCoursSession["Sigle"]."'";
        $mySqli->listeEnregistrements = mysqli_query($mySqli->cBD, $mySqli->requete);
        
       
        if ($mySqli->listeEnregistrements)
            $mySqli->nbEnregistrements = mysqli_num_rows($mySqli->listeEnregistrements);

            for($i=0; $i < $mySqli->nbEnregistrements; $i++){
                echo "<tr>";
                echo "<form id=\"retraitDocument\" method=\"post\">";
                /* Check box */
                echo "<th>". "<input type='checkbox' id='cbDocument$i' name='cbDocument$i' value='".$mySqli->contenuChamp($i,"Titre") .
                    "' form='formRetrait'>" ."</th>";
                ?>

                <!-- coursSession HIDDEN -->
                <input type="hidden" name="coursSession" value="<?php echo $infosCoursSession["coursSession"] ?>">
                <input type="hidden" name="titreAvantModif" value="<?php echo $mySqli->contenuChamp($i,"Titre"); ?>">
                <?php
                /* date remise */
                echo "<th><input type='date' name='RdateCours' value='". $mySqli->contenuChamp($i,"DateCours") ."' ></th>";

                /* no sequence */
                echo "<th><input type='number' name='RnoSequence' min='1' max='20' value='". $mySqli->contenuChamp($i,"NoSequence") ."' ></th>";

                /* date acces debut */
                echo "<th><input type='date' name='RdateDebut' value='". $mySqli->contenuChamp($i,"DateAccesDebut") ."' ></th>";

                /* date acces fin */
                echo "<th><input type='date' name='RdateFin' value='". $mySqli->contenuChamp($i,"DateAccesFin") ."' ></th>";

                /* titre */
                echo "<th><input type='text' name='Rtitre' minlength='5' maxlength='100' value='". $mySqli->contenuChamp($i,"Titre") ."' ></th>";

                /* description */
                echo "<th><input type='text' name='Rdescription' minlength='5' maxlength='255' value='". $mySqli->contenuChamp($i,"Description") ."' ></th>";

                /* nb pages */
                echo "<th><input type='number' name='RnbPages' min='1' max='999' value='". $mySqli->contenuChamp($i,"NbPages") ."' ></th>";

                /* categorie */
                echo "<th>".creerSelectAvecValeur("categorie","Description","","RselectCategorie","Rcategorie","",$mySqli->contenuChamp($i,"Categorie"),"",$mySqli2)."</th>";

                /* no version */
                echo "<th><input type='number' name='RnoVersion' min='1' max='99' value='". $mySqli->contenuChamp($i,"NoVersion") ."' ></th>";

                /* date version */
                echo "<th><input type='date' name='RdateVersion' value='". $mySqli->contenuChamp($i,"DateVersion") ."' ></th>";

                /* hyper lien */
                echo "<th><input type='text' name='RhyperLien' minlength='5' maxlength='255' value='". $mySqli->contenuChamp($i,"HyperLien") ."' disabled></th>";
                echo "<button id=\"btnHyperLien\" name=\"RhyperLien\" onclick=\"window.open('televersement.php','window','width=400,height=150')\"> ".
                        "Choisir un fichier</button><br/>"
                        . $_SESSION["nomFichierModif"]; 
                
                /* ajoute Par */
                echo "<th><input type='text' name='RajoutePar' value='". $mySqli->contenuChamp($i,"AjoutePar") ."' disabled></th>";

                /* bouton enregistrer modifications */
                echo "<td><input type='submit' class='sButton' name='DocumentAction' value='Modifier'></td>";
                echo "</form>";
                echo "</tr>";
            }
            if($mySqli->nbEnregistrements == 0){
                echo "<td colspan='3'> Aucun document enregistré pour ce cours-session !</td>";
            }
        ?>
        </table>
        
    </div>
    <?php } ?>
    <br/>

<form id="formRetrait" method="post">
    <input type="hidden" name="coursSession" value="<?php echo $infosCoursSession["coursSession"]; ?>">
    <?php echo $msgResultatAction2; ?>
    <br/>
    <input type='submit' class='sButton' name='DocumentAction' value='Retirer'>
</form>
    
<input class="sButton" id="btnRetour" type="button" onclick="window.location.href='mise-a-jour-liste-document'" value="Retour">
<?php
}
?>

<br/>
<br/>
<script>
    document.getElementById("selectCoursSession").value = "<?php echo post("coursSession"); ?>";
</script>


<?php
$mySqli->deconnexion();
$mySqli2->deconnexion();
require_once 'pied-page.php';
?>
