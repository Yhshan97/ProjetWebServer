<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Assigner les privilèges d'accès aux documents";
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

$mySqli = new mysql("", $strInfosSensibles);
$objetUtil = new mysql("", $strInfosSensibles);
$objetPrivilege = new mysql("",$strInfosSensibles);


/* Ajout des privileges qui sont enregistré */
    $compteur = 0;
    foreach($_POST as $NoPrivilege){
        // Delete tous les privileges quand il clique sur le bouton enregistrer
        if($compteur == 0){
            $mySqli->supprimeEnregistrements("privilege");
            $compteur ++;
        }

        $nomUtilFOR = substr($NoPrivilege,0,strlen($NoPrivilege)-17);   // Separe "NomUtil-420-4W5 (H-2018)" à "NomUtil"
        $coursSessionFOR = substr($NoPrivilege,-16);                    // Separe l'autre côté donc "420-4W5 (H-2018)"

        // Ajoute ligne par ligne tous les inputs qui ont ete envoyes par $_POST
        $mySqli->insereEnregistrement("privilege",$NoPrivilege,$nomUtilFOR,$coursSessionFOR);
    }

?>
<form id="assignerPrivileges" method="post" action="">
    <br>
    <p> Il y a <?php
        $mySqli->selectionneEnregistrements("courssession");
        echo $mySqli->nbEnregistrements?> cours-session(s). </p>
    <p> Il y a <?php
        $mySqli->selectionneEnregistrements("utilisateur");
        echo $mySqli->nbEnregistrements ?> utilisateur(s). </p>
    <br>
    
    <?php
    $mySqli->selectionneEnregistrements('courssession');
    $intNombreCoursSession = $mySqli->nbEnregistrements;

    $objetUtil->selectionneEnregistrements('utilisateur');
    $intNombreUtilisateur = $objetUtil->nbEnregistrements;
    
    echo "<table> \n";
    echo "<tr><td style=\"border:none;\"></td>\n";

    // Affichage des checkbox qui checkent tous les checkbox de la colonne  * onclick='checkAll(this)'; *
    for ($j = 0; $j < $intNombreCoursSession; $j++) {
        echo "<td align='center' style=\"border:none;\"> <input type=\"checkbox\" id=\"$j\" value=\"". $mySqli->contenuChamp($j,"coursSession") .
            "\" onclick='checkAll(this);' /></td>\n";
    }

    // Premiere ligne du tableau avec les noms de cours-sessions
    echo "</tr>\n";
    echo "<tr class='sEntete'><th> Nom d'utilisateur / Cours-Session </th>\n";
    for ($j = 0; $j < $intNombreCoursSession; $j++) {
                echo "<th>" . $mySqli->contenuChamp($j, 'Sigle') . "</br>" . $mySqli->contenuChamp($j, 'Session') . "</th>\n";
    }
    echo "</tr>\n";

    // Deroulement ligne par ligne donc for basé sur le nombre d'utilisateurs
    for ($i = 0; $i < $intNombreUtilisateur; $i++) {
        echo "<tr style='background-color: whitesmoke;'>\n<td class='sBorder'>" . $objetUtil->contenuChamp($i, "NomUtilisateur"). "</td>\n";

        // Deuxieme for pour afficher les checkbox dépendant du nombre de cours-sessions
        for ($j2 = 0; $j2 < $intNombreCoursSession; $j2++) {
            // Crée le nom unique du checkbox    ex: "NomUtil1-420-4W5 (H-2018)"
            $nom = $objetUtil->contenuChamp($i, "NomUtilisateur")."-". $mySqli->contenuChamp($j2, "coursSession");
            // Verifier si ce privilège est dans la base de données. Si oui $strChecked="checked"
            $objetPrivilege->selectionneEnregistrements("privilege","C=IDPrivilege='$nom'");
            $StrChecked = $objetPrivilege->nbEnregistrements == 1 ? "checked" : "";

            echo "<td align='center' class='sBorder'> <input type=\"checkbox\" name=\"$nom\" value=\"$nom\" $StrChecked /> </td>\n" ;
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
    ?>
    <br>
    <input id="btnSelection" type="submit" class="sButton" value="Enregistrement" />

    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
</form>

<script>
    function checkAll(checkBox){
        // Get dans un array tous les objets inputs
        var inputs = document.getElementsByTagName("input");
        // Creer une expression reguliere   ex: /.*420-4W5 \(H-2018\)/
        var regex = new RegExp(".*" + checkBox.value.replace("(","\\(").replace(")","\\)"));
        
        // Cocher ou decocher tous les checkbox ou leur valeur match le regex
        for(var i = 0; i < inputs.length; i++) {
            if(inputs[i].type == "checkbox" && regex.test(inputs[i].value)) {
                inputs[i].checked = checkBox.checked;
            }
        }
    }
    document.body.style.overflow = "auto";
</script>

<?php
$objetUtil->deconnexion();
$mySqli->deconnexion();
$objetPrivilege->deconnexion();
require_once("pied-page.php");
