<?php
   /* Réf.: http://fr.openclassrooms.com/informatique/cours/ajax-et-l-echange-de-donnees-en-javascript/l-access-control-1 */
   header("Access-Control-Allow-Origin: *");

   /* Pour masquer les messages d'erreur pour cette page uniquement
      À IMPLANTER SEULEMENT EN PRODUCTION ! */
   //error_reporting(0);

   /* Variables nécessaires pour les fichiers d'inclusion */
   $strTitreApplication = "Conception et utilisation de la classe 'mysql'";
   $strNomFichierCSS = "index.css";
   $strNomAuteur = "Yao Hua Shan";
   /* Variables contenant des styles */
   $sBleu = "\"sBleu\"";
   $sGras = "\"sGras\"";
   $sRouge = "\"sRouge\"";
   $sGrasRouge = "\"sGras sRouge\"";
   
   /* Liste des fichiers d'inclusion */
   require_once("classe-fichier-2018-03-16.php");
   require_once("classe-mysql-2018-03-17.php");
   require_once("librairies-communes-2018-03-17.php");

   require_once("en-tete.php");
?>
<?php
   /*
   |-------------------------------------------------------------------------------------|
   | poursuiteTraitement
   |-------------------------------------------------------------------------------------|
   */
   function poursuiteTraitement($binOK) {
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
   /*
   |-------------------------------------------------------------------------------------|
   | requeteExecutee
   |-------------------------------------------------------------------------------------|
   */
   function requeteExecutee($strMessage, $strRequeteExecutee, $strVerdict, $binLigne=false) {
      GLOBAL $sBleu, $sGras, $sRouge;
      echo "<p><span class=$sGras><span class=$sRouge>$strMessage</span><br />$strRequeteExecutee</span><br />=> <span class=$sBleu>$strVerdict</span></p>";
      echo $binLigne ? "<hr />" : "";
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
   
   /* --- Initialisation des variables de travail --- */
   $strNomBD="bdh18_shan";
   $strLocalHost = "localhost";
   $strNomTable1 = "TableExercice_4_1";
   $strNomTable2 = "TableExercice_4_2";

   requeteExecutee("0. Récupération des informations sur le serveur", "$strMonIP => $strIPServeur ($strNomServeur)", "Nom du fichier 'infos-sensibles.php' : <span class=\"sGras\">$strInfosSensibles</span>");

   /* --- Création de l'instance, connexion avec mySQL et sélection de la base de données (RÉUSSITE) --- */
   $BDExercice4 = new mysql($strNomBD, $strInfosSensibles);
   $strVerdict = $BDExercice4->cBD->stat;
   requeteExecutee("1. instanciation, connexion() et selectionneBD()", "mysqli_connect(\"localhost\", \$strNomAdmin, \$strMotPasseAdmin)", $strVerdict);
   
   /* --- Sélection de la base de données (RÉUSSITE) --- */
   $BDExercice4->selectionneBD();
   $strVerdict = "Sélection de la base de données <span class=\"sGras\">'$BDExercice4->nomBD'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("2. selectionneBD()", "mysqli_select_db(\$cBD, $BDExercice4->nomBD)", $strVerdict);
   poursuiteTraitement($BDExercice4->OK);
   
   /* --- Aux fins de la démonstration seulement, suppression des tables existantes */
   mysqli_query($BDExercice4->cBD, "DROP TABLE $strNomTable1");
   mysqli_query($BDExercice4->cBD, "DROP TABLE $strNomTable2");
   
   /* --- Création de la structure de la 1re table (RÉUSSITE) --- */
   $BDExercice4->creeTable($strNomTable1,
                    "NoApp", "INT NOT NULL",
                    "NbPieces", "DECIMAL(3,1)",
                    "Signature", "DATE",
                    "TypeBail", "CHAR(1)",
                    "Loyer", "DECIMAL(7,2)",
                    "Meuble", "BOOL",
                    "NoStationnement", "INT",
                    "NomLocataire", "VARCHAR(40)",
                    "PRIMARY KEY(NoApp)");
   $strVerdict = "Création de la table <span class=\"sGras\">'$strNomTable1'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("3.1 creeTable()", $BDExercice4->requete, $strVerdict);

   /* --- Affichage de la structure de la 1re table --- */
   $BDExercice4->afficheInformationsSurBD();    
   
   /* --- Tentative de création de la structure de la 1re table (ÉCHEC) --- */
   $BDExercice4->creeTable($strNomTable1,
                    "NoApp", "INT NOT NULL", 
                    "PRIMARY KEY(NoApp)");
   $strVerdict = "Création de la table <span class=\"sGras\">'$strNomTable1'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("3.2 creeTable()", $BDExercice4->requete, $strVerdict);

   /* --- Suppression de la structure de la 1re table (RÉUSSITE) --- */
   $BDExercice4->supprimeTable($strNomTable1);
   $strVerdict = "Suppression de la table <span class=\"sGras\">'$strNomTable1'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("4.1 supprimeTable()", $BDExercice4->requete, $strVerdict);

   /* --- Tentative d'affichage de la structure de la 1re table --- */
   $BDExercice4->afficheInformationsSurBD();    

   /* --- Tentative de suppression de la structure de la 1re table (ÉCHEC) --- */
   $strVerdict = "Suppression de la table <span class=\"sGras\">'$strNomTable1'</span> " . ($BDExercice4->supprimeTable($strNomTable1) ? "confirmée" : "impossible");
   requeteExecutee("4.2 supprimeTable()", $BDExercice4->requete, $strVerdict);
   
   /* --- Création de la structure de la 1re table (RÉUSSITE) --- */
   $BDExercice4->creeTable($strNomTable1,
                    "NoEmploye", "INT NOT NULL",
                    "Homme", "BOOL",
                    "TauxChange", "DECIMAL(7,4)",
                    "DateTransaction", "DATE",
                    "NbJours", "INT",
                    "NAS", "CHAR(11)",
                    "Prix", "DECIMAL(10,2)",
                    "Seuil", "INT NOT NULL",
                    "NomComplet", "VARCHAR(40)",     
                    "PRIMARY KEY(NoEmploye)");
   $strVerdict = "Création de la table <span class=\"sGras\">'$strNomTable1'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("5. creeTable()", $BDExercice4->requete, $strVerdict);

   /* --- Création de la structure de la 2e table (RÉUSSITE) --- */
   $strDefinitions = "N,NoEmploye;".
                     "B,Homme;" .
                     "C7.4,TauxChange;" .
                     "D,DateTransaction;" .
                     "E,NbJours;" .
                     "F11,NAS;" . 
                     "M,Prix;" . 
                     "N,Seuil;" .
                     "V40,NomComplet";
   $strCles = "NoEmploye";
   $BDExercice4->creeTableGenerique($strNomTable2, $strDefinitions, $strCles);
   $strVerdict = "Création de la table <span class=\"sGras\">'$strNomTable2'</span> " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("6. creeTableGenerique()", $BDExercice4->requete, $strVerdict);
   
   /* --- Affichage de la structure des deux tables --- */
   $BDExercice4->afficheInformationsSurBD();    

   /* Insertion d'un enregistrement (RÉUSSITE) */
   $intNo = 0;
   $BDExercice4->insereEnregistrement($strNomTable1,
                    1000,
                    true,
                    1.16,
                    "2018-03-22",
                    45,
                    "246-921-011",
                    49.99,
                    20,
                    "Lebel, Jean-Claude");
   $strVerdict = "Ajout de l'enregistrement " . ($BDExercice4->OK ? "confirmé" : "impossible");
   requeteExecutee("7.$intNo insereEnregistrement()", $BDExercice4->requete, $strVerdict);
   
   /* À chaque ligne du fichier 'liste-enregistrements-a-inserer.txt' correspond une ligne à insérer (RÉUSSITE et ÉCHEC) */
   $strNomFichierEnregistrements = "liste-enregistrements-a-inserer.txt";
   $tValeurs = array();
   
   $fichierEmploye = new fichier($strNomFichierEnregistrements);
   $fichierEmploye->ouvre();
   while (!$fichierEmploye->detecteFin()) {
      $intNo++;
      $fichierEmploye->litDonneesLigne($tValeurs, ";", "NoEmploye", "Homme", "TauxChange", "DateTransaction", "NbJours", "NAS", "Prix", "Seuil", "NomComplet");
      $tValeurs["Homme"] = $tValeurs["Homme"] == "oui" || $tValeurs["Homme"] == "true" ? true : ($tValeurs["Homme"] == "non" || $tValeurs["Homme"] == "false" ? false : null);
      $tValeurs["TauxChange"] = empty($tValeurs["TauxChange"]) ? null : $tValeurs["TauxChange"];
      $tValeurs["DateTransaction"] = empty($tValeurs["DateTransaction"]) ? null : $tValeurs["DateTransaction"];
      $tValeurs["NbJours"] = empty($tValeurs["NbJours"]) ? null : $tValeurs["NbJours"];
      $tValeurs["NAS"] = empty($tValeurs["NAS"]) ? null : $tValeurs["NAS"];
      $tValeurs["Prix"] = empty($tValeurs["Prix"]) ? null : $tValeurs["Prix"];
      $tValeurs["Seuil"] = empty($tValeurs["Seuil"]) ? 0 : $tValeurs["Seuil"]; /* Ne peut pas être NULL */
      $tValeurs["NomComplet"] = empty($tValeurs["NomComplet"]) ? null : $tValeurs["NomComplet"]; /* Attention aux apostrophes et aux guillemets */
      $BDExercice4->insereEnregistrement($strNomTable1,
                       $tValeurs["NoEmploye"],
                       $tValeurs["Homme"],
                       $tValeurs["TauxChange"],
                       $tValeurs["DateTransaction"],
                       $tValeurs["NbJours"],
                       $tValeurs["NAS"],
                       $tValeurs["Prix"],
                       $tValeurs["Seuil"],
                       $tValeurs["NomComplet"]);
      $strVerdict = "Ajout de l'enregistrement " . ($BDExercice4->OK ? "confirmé" : "impossible");
      requeteExecutee("7.$intNo insertionEnregistrement()", $BDExercice4->requete, $strVerdict);
   }
   $fichierEmploye->ferme();
   
   /* --- Affichage de la structure de la 1re table --- */
   $BDExercice4->afficheInformationsSurBD($strNomTable1);    

   /* --- Copie de tous les enregistrements de la table 1 dans la table 2 (RÉUSSITE) */
   $strListeChampsTable1 = "NoEmploye,Homme,TauxChange,DateTransaction,NbJours,NAS,Prix,Seuil,NomComplet";
   $strListeChampsTable2 = "";
   $BDExercice4->copieEnregistrements($strNomTable1, $strListeChampsTable1, $strNomTable2, $strListeChampsTable2);
   $strVerdict = "Copie de tous les enregistrements de la table '$strNomTable1' dans la table '$strNomTable2' " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("8. copieEnregistrements()", $BDExercice4->requete, $strVerdict);
   
   /* --- Affichage de la structure des deux tables --- */
   $BDExercice4->afficheInformationsSurBD();    
   
   /* --- Suppression des enregistrements de la table 2 dont le numéro est égal ou supérieur à 1005 (RÉUSSITE) */
   $strListeConditions = "NoEmploye >= 1005";
   $BDExercice4->supprimeEnregistrements($strNomTable2, $strListeConditions);
   $strVerdict = "Suppression des enregistrements de la table '$strNomTable2' dont le numéro >= 1005 " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("9. supprimeEnregistrements()", $BDExercice4->requete, $strVerdict);
   
   /* --- Affichage de la structure des deux tables --- */
   $BDExercice4->afficheInformationsSurBD();    
   
   /* Changement du nom et de la taille du dernier champ de la table 2 (RÉUSSITE) */
   $BDExercice4->modifieChamp($strNomTable2, "NomComplet", "NomCourtier VARCHAR(45)");
   $strVerdict = "Changement du nom du champ 'NomComplet' pour 'NomCourtier' de la table '$strNomTable2' " . ($BDExercice4->OK ? "confirmé" : "impossible");
   requeteExecutee("10. modifieChamp()", $BDExercice4->requete, $strVerdict);
   
   /* --- Affichage de la structure de la 2e table --- */
   $BDExercice4->afficheInformationsSurBD($strNomTable2);    
   
   /* --- Copie des enregistrements de la table 1 dont le numéro >= 1006 dans la table 2  (RÉUSSITE) */
   $strListeChampsTable1 = "NoEmploye,Homme,TauxChange,DateTransaction,NbJours,NAS,Prix,Seuil,NomComplet";
   $strListeChampsTable2 = "NoEmploye,Homme,TauxChange,DateTransaction,NbJours,NAS,Prix,Seuil,NomCourtier";
   $strListeConditions = "NoEmploye>=1006";
   $BDExercice4->copieEnregistrements($strNomTable1, $strListeChampsTable1, $strNomTable2, $strListeChampsTable2, $strListeConditions);
   $strVerdict = "Copie des enregistrements de la table '$strNomTable1' dont le numéro >= 1006 dans la table '$strNomTable2' " . ($BDExercice4->OK ? "confirmée" : "impossible") . " (1005 n'est pas copié dans '$strNomTable2')";
   requeteExecutee("11. copieEnregistrements()", $BDExercice4->requete, $strVerdict);
   
   /* --- Affichage de la structure des deux tables --- */
   $BDExercice4->afficheInformationsSurBD();    
   
   /* --- Suppression de tous les enregistrements de la 1re table (RÉUSSITE) */
   $BDExercice4->supprimeEnregistrements($strNomTable1);
   $strVerdict = "Suppression de tous les enregistrements de la table '$strNomTable1' " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("12.1 suppressionEnregistrements()", $BDExercice4->requete, $strVerdict);

   /* --- Suppression des enregistrements de la 2e table dont le numéro d'employé est impair (RÉUSSITE) */
   $strListeConditions = "MOD(NoEmploye,2) = 1";
   $BDExercice4->supprimeEnregistrements($strNomTable2, $strListeConditions);
   $strVerdict = "Suppression des enregistrements de la table '$strNomTable2' dont le numéro d'employé est impair " . ($BDExercice4->OK ? "confirmée" : "impossible");
   requeteExecutee("12.2 suppressionEnregistrements()", $BDExercice4->requete, $strVerdict);

   /* --- Affichage de la structure des deux tables --- */
   $BDExercice4->afficheInformationsSurBD();    
   
   /* --- Déconnexion de mySQL (RÉUSSITE) --- */
   $BDExercice4->deconnexion();
   requeteExecutee("13. deconnexion()", "mysql_close(\$cBD)", "Déconnexion de mySQL. En tout cas, souhaitons-le !");
?>
      </div>
<?php
   require_once("pied-page.php");
?>