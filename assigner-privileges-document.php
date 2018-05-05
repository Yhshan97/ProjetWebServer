<?php
header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Assigner les privilèges d'accès aux documents";
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


/* Ajout des privileges qui sont enregistré */
    $compteur = 0;
    foreach($_POST as $NoPrivilege){
        if($compteur == 0){
            $mySqli->supprimeEnregistrements("privilege");
            $compteur ++;
        }

        $nomUtilFOR = substr($NoPrivilege,0,strlen($NoPrivilege)-17);
        $coursSessionFOR = substr($NoPrivilege,-16);
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
    
    echo "<table border='1'> \n";
    echo "<tr class='sEntete'><th> Nom d'utilisateur / Cours-Session </th>\n";
    for ($j = 0; $j < $intNombreCoursSession; $j++) {
                echo "<th>" . $mySqli->contenuChamp($j, 'Sigle') . "</br>" . $mySqli->contenuChamp($j, 'Session') . "</th>\n";
    }
    echo "</tr>\n";
    
    for ($i = 0; $i < $intNombreUtilisateur; $i++) {
        echo "<tr style='background-color: whitesmoke;'>\n<td>" . $objetUtil->contenuChamp($i, "NomUtilisateur"). "</td>\n";
        for ($j2 = 0; $j2 < $intNombreCoursSession; $j2++) {
            $nom = $objetUtil->contenuChamp($i, "NomUtilisateur")."-". $mySqli->contenuChamp($j2, "coursSession");
            $objetPrivilege->selectionneEnregistrements("privilege","C=IDPrivilege='$nom'");
            $StrChecked = $objetPrivilege->nbEnregistrements == 1 ? "checked" : "";

            echo "<td align='center'> <input type=\"checkbox\" name=\"". $nom ."\" value=\"" . $nom . "\" ". $StrChecked . "/></td>\n" ;
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



<?php
$objetUtil->deconnexion();
$mySqli->deconnexion();
$objetPrivilege->deconnexion();
require_once("pied-page.php");
