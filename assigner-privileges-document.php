<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Assigner un groupe d'utilisateurs à un cours-session";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");
require_once("background.php");
require_once("en-tete.php");

session_start();
detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
$objetUtil = new mysql("", $strInfosSensibles);
$objetPrivilege = new mysql("",$strInfosSensibles);
var_dump($_POST);

/* Ajout des privileges qui sont enregistré */
for($x= 0; $x<count($_POST); $x++){
    //$mySqli->insereEnregistrement("privilege");
}

$tabPrivileges = [];




?>
<form id="assignerPrivileges" method="post" action="">
    <br>
    <p> Il y'a <?php
        $mySqli->selectionneEnregistrements("courssession");
        echo $mySqli->nbEnregistrements
        ?> cours-session </p>
    <br> <br>
    <p> Il y'a <?php
        $mySqli->selectionneEnregistrements("documents");
        echo $mySqli->nbEnregistrements
        ?> document(s) </p>
    <br> <br>
    
    <?php
    $mySqli->selectionneEnregistrements('courssession');
    $intNombreCoursSession = $mySqli->nbEnregistrements;

    $objetUtil->selectionneEnregistrements('utilisateur');
    $intNombreUtilisateur = $objetUtil->nbEnregistrements;
    
    echo "<table> \n";
    echo "<tr class='sEntete'><td> Nom d'utilisateur / Cours-Session </td>\n";
    for ($j = 0; $j < $intNombreCoursSession; $j++) {
                echo "<td>" . $mySqli->contenuChamp($j, 'Sigle') . "</br>" . $mySqli->contenuChamp($j, 'Session') . "</td>\n";
    }
    echo "</tr>\n";
    
    for ($i = 0; $i < $intNombreUtilisateur; $i++) {
        echo "<tr>\n<td>" . $objetUtil->contenuChamp($i, "NomComplet"). "</td>\n";
        for ($j2 = 0; $j2 < $intNombreCoursSession; $j2++) {
            
            echo "<td> <input type=\"checkbox\" name=\"".$objetUtil->contenuChamp($i, "NomUtilisateur")."-". $mySqli->contenuChamp($j2, "coursSession") . 
                    "\" value=\"" .$objetUtil->contenuChamp($i, "NomUtilisateur")."-". $mySqli->contenuChamp($j2, "coursSession") . "\"/></td>\n" ;
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
    ?>

    <button id="btnSelection" class="sButton" onclick="window.location.href = 'gestion-document.php'">Selection</button>
    <br> <br>
    <input class="sButton" id="btnRetour" type="button" onclick="window.location.href = 'gestion-documents-administrateur.php'"
           value="Retour">
</form>



<?php
$objetUtil->deconnexion();
$mySqli->deconnexion();
$objetPrivilege->deconnexion();
require_once("pied-page.php");
