<?php

require_once("../librairies-communes-2018-03-17.php");
require_once("../classe-mysql-2018-03-17.php");
function requeteExecutee($strMessage, $strRequeteExecutee, $strVerdict, $binLigne = false)
{
    GLOBAL $sBleu, $sGras, $sRouge;
    echo "<p><span class=$sGras><span class=$sRouge>$strMessage</span><br />$strRequeteExecutee</span><br />=> <span class=$sBleu>$strVerdict</span></p>";
    echo $binLigne ? "<hr />" : "";


    function poursuiteTraitement($binOK)
    {
        GLOBAL $sGrasRouge;
        if (!$binOK) {
            echo "<p class=$sGrasRouge>Le traitement ne peut se poursuivre...</p>";
            ?>
            </div>
            <div id="divPiedPage">
                <p class="sDroits">
                    &copy; Département d'informatique G.-G.
                </p>
            </div>
            </body>
            </html>
            <?php
            die();
        }
    }

}

/*
|-------------------------------------------------------------------------------------|
| Module directeur
|-------------------------------------------------------------------------------------|
*/
/* Détermination du fichier "InfosSensibles" à utiliser */
$strMonIP = "";
$strIPServeur = "";
$strNomServeur = "";
$strInfosSensibles = "";
detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$nomBD = "prj_immigrants";
$objMySqli = new mysql("prj_immigrants", $strInfosSensibles);

//Verifie que la connexion a bien ete effectuee
ecrit("Connexion au BD '$nomBD'");
ecrit((empty($objMySqli->cBD->error) ? " effectu&eacute correctement !" : die(" &eacutechou&eacute")),2);


/*
Ajout des données de base
 */

// Sessions
echo "ajout sessions<br />";
$objMySqli->insereEnregistrement("session","H-2018","2018-01-22","2018-05-29");
$objMySqli->insereEnregistrement("session","A-2018","2018-08-20","2018-12-19");
$objMySqli->insereEnregistrement("session","H-2019","2019-01-21","2019-05-31");

echo "ajout cours<br />";
//Cours
$arrayCours = csv_to_array('../Donnees/cours.csv',";");
for ($index = 0; $index < count($arrayCours); $index++) {
        $objMySqli->insereEnregistrement("cours",$arrayCours[$index]["Sigle"],$arrayCours[$index]["Titre"]);
}

//Utilisateurs
echo "ajout utilisateurs<br />";
$arrayUtilisateur = csv_to_array('../Donnees/utilisateurs.csv',";");
for ($index = 0; $index < count($arrayUtilisateur); $index++) {
        $objMySqli->insereEnregistrement("utilisateur",
                $arrayUtilisateur[$index]["NoUtilisateur"],
                $arrayUtilisateur[$index]["MotDePasse"],
                ($arrayUtilisateur[$index]["Statut"] == "U" ? 0:1),
                $arrayUtilisateur[$index]["NomComplet"],
                $arrayUtilisateur[$index]["Courriel"]);
}

//Cours-Sessions
echo "ajout cours-sessions<br />";
$array = csv_to_array('../Donnees/coursSessions.csv',";");
for ($index = 0; $index < count($array); $index++) {
    $objMySqli->insereEnregistrement("courssession",
        ($array[$index]["Cours"] . " (" . $array[$index]["Session"]) . ")",
        $array[$index]["Session"],
        $array[$index]["Cours"],
        $array[$index]["NomProf"]);
}

//Categories
echo "ajout categories<br />";
$array = csv_to_array('../Donnees/categories.csv',";");
for ($index = 0; $index < count($array); $index++) {
    $objMySqli->insereEnregistrement("categorie",$array[$index]["Categorie"]);
}

//Documents
echo "ajout documents<br />";
$array = csv_to_array('../Donnees/documents.csv',",");
for ($index = 0; $index < count($array); $index++) {

    $objMySqli->insereEnregistrement("document",
        $array[$index]["Session"],
        $array[$index]["SigleCours"],
        $array[$index]["DateCours"],
        intval($array[$index]["NoSequence"]),
        $array[$index]["DateAccesDebut"],
        $array[$index]["DateAccesFin"],
        $array[$index]["Titre"],
        $array[$index]["Description"],
        intval($array[$index]["NbPages"]),
        $array[$index]["Categorie"],
        intval($array[$index]["NoVersion"]),
        $array[$index]["DateVersion"],
        $array[$index]["HyperLien"],
        $array[$index]["AjoutePar"]
        );
}

$objMySqli->afficheInformationsSurBD();

?>
