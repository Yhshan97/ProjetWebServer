<?php
require_once("librairies-communes-2018-03-17.php");
require_once("classe-mysql-2018-03-17.php");
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


//Drop les tables s'ils existent deja

$objMySqli->supprimeTable("Privilege");
ecrit("Suppression table 'Privilege' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("document");
ecrit("Suppression table 'Document' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("CoursSession");
ecrit("Suppression table 'cours-session' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("categorie");
ecrit("Suppression table 'Categorie' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("Utilisateur");
ecrit("Suppression table 'Utilisateur' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("cours");
ecrit("Suppression table 'Cours' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->supprimeTable("session");
ecrit("Suppression table 'Session' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 4);




//Creation des tables Categorie,Cours,Document
$objMySqli->creeTableGenerique("Categorie", "V15,Description", "Description");
ecrit("Creation table 'Categorie' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);


$objMySqli->creeTableGenerique("Cours", "V7,Sigle;" .
                                        "V50,Titre;" .
                                        "V30,NomProf", "Sigle");
ecrit("Creation table 'Cours' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);


$objMySqli->creeTableGenerique("Document",  "V6,Session;" .
                                            "V7,Sigle;" .
                                            "D,DateCours;" .
                                            "E,NoSequence;" .
                                            "D,DateAccesDebut;" .
                                            "D,DateAccesFin;" .
                                            "V100,Titre;" .
                                            "V255,Description;" .
                                            "E,NbPages;" .
                                            "V15,Categorie;" .
                                            "E,NoVersion;" .
                                            "D,DateVersion;" .
                                            "V255,HyperLien;" .
                                            "V25,AjoutePar;", "Titre,Session,Sigle");
ecrit("Creation table 'Document' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);


$objMySqli->creeTableGenerique("Session", "V6,Description;".
                                           "D,DateDebut;".
                                           "D,DateFin", "Description");
ecrit("Creation table 'Session' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);


$objMySqli->creeTableGenerique("Utilisateur", "V25,NomUtilisateur;".
                                           "V15,MotDePasse;".
                                           "B,StatutAdmin;".
                                           "V30,NomComplet;".
                                           "V50,Courriel;", "NomUtilisateur");
ecrit("Creation table 'Utilisateur' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->creeTableGenerique("CoursSession", "V16,coursSession;".
                                            "V6,Session;".
                                            "V7,Sigle;".
                                            "V30,NomProf;", "coursSession");
ecrit("Creation table 'CoursSession' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->creeTableGenerique("Privilege", "V50,IDPrivilege;".
                                            "V25,NomUtilisateur;".
                                            "V16,coursSession;", "IDPrivilege");
ecrit("Creation table 'Privilege' :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);





$objMySqli->etablitRelation("Categorie", "Description", "Document", "Categorie","FK_Categorie_Document");
ecrit("Etablit relation entre categorie et document :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("Session", "Description", "Document", "Session","FK_Session_Document");
ecrit("Etablit relation entre session et document :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("Cours", "Sigle", "Document", "Sigle","FK_Cours_Document");
ecrit("Etablit relation entre cours et document :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("Session", "Description", "courssession", "Session","FK_Session_coursSession");
ecrit("Etablit relation entre courssession et session :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("Cours", "Sigle", "courssession", "Sigle","FK_Cours_coursSession");
ecrit("Etablit relation entre courssession et Cours :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("CoursSession", "coursSession", "Privilege", "coursSession","FK_Privilege_CoursSession");
ecrit("Etablit relation entre courssession et privilege :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->etablitRelation("utilisateur", "NomUtilisateur", "Privilege", "NomUtilisateur","FK_Privilege_nomUtilisateur");
ecrit("Etablit relation entre utilisateur et privilege :" . ($objMySqli->OK ? " Succ&egraves" : " &Eacutechec"), 2);

$objMySqli->deconnexion();
?>
