<?php

/*
  |----------------------------------------------------------------------------------------|
  | class mysql
  |----------------------------------------------------------------------------------------|
 */

class mysql {
    /*
      |----------------------------------------------------------------------------------|
      | Attributs
      |----------------------------------------------------------------------------------|
     */

    public $cBD = null;                       /* Identifiant de connexion */
    public $listeEnregistrements = null;      /* Liste des enregistrements retournés */
    public $nomFichierInfosSensibles = "";    /* Nom du fichier 'InfosSensibles' */
    public $nomBD = "";                       /* Nom de la base de données */
    public $OK = false;                       /* Opération réussie ou non */
    public $requete = "";
    public $nbEnregistrements = 0;

    /* Requête exécutée */
    /*
      */
      
      /*
      |----------------------------------------------------------------------------------|
      | __construct
      |----------------------------------------------------------------------------------|
     */

    function __construct($strNomBD, $strNomFichierInfosSensibles) {
        $this->nomBD = "pjf_immigrants";
        $this->nomFichierInfosSensibles = $strNomFichierInfosSensibles;

        $this->connexion();
        $this->selectionneBD();
    }

    /*
      |----------------------------------------------------------------------------------|
      | connexion()
      |----------------------------------------------------------------------------------|
     */

    function connexion() {
        $servername = "localhost";
        $username = "immigrants";
        $password = "Secret14738";

        $this->cBD = new mysqli($servername, $username, $password) or die("Problème de connexion... Message d'erreur retourné par PHP");

        return $this->cBD;
    }

    /*
      |----------------------------------------------------------------------------------|
      | copieEnregistrements
      |----------------------------------------------------------------------------------|
     */

    function copieEnregistrements($strNomTableSource, $strListeChampsSource, $strNomTableCible, $strListeChampsCible, $strListeConditions = "") {
        /* Réf.: www.lecoindunet.com/dupliquer-ou-copier-des-lignes-d-une-table-vers-une-autre-avec-mysql-175 */
        if (empty($strListeChampsCible))
            $strListeChampsCible = $strListeChampsSource;

        $this->requete = "INSERT INTO $strNomTableCible (";

        $this->requete .= "$strListeChampsCible) SELECT $strListeChampsSource FROM $strNomTableSource";

        if (!empty($strListeConditions))
            $this->requete .= " WHERE $strListeConditions";

        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | creeTable
      |----------------------------------------------------------------------------------|
     */

    function creeTable($strNomTable) {
        $this->requete = "CREATE TABLE " . $strNomTable . " (";

        for ($i = 1; $i < func_num_args() - 1; $i+=2) {
            $this->requete .= func_get_arg($i) . " " . func_get_arg($i + 1) . ", ";
        }

        $this->requete .= func_get_arg(func_num_args() - 1) . ") ENGINE=InnoDB";
        $this->OK = mysqli_query($this->cBD, $this->requete);

        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | creeTableGenerique()
      |----------------------------------------------------------------------------------|
     */

    function creeTableGenerique($strNomTable, $strDefinitions, $strCles) {
        $this->requete = "CREATE TABLE " . $strNomTable . " (";
        $tabDefinitions = explode(';', $strDefinitions);

        for ($i = 0; $i < count($tabDefinitions); $i++) {
            $tabCleEtNom = explode(',', $tabDefinitions[$i]);
            switch (gauche($tabCleEtNom[0], 1)) {
                case "B":
                    $this->requete .= $tabCleEtNom[1] . " BOOLEAN, ";
                    break;
                case "C":
                    $this->requete .= $tabCleEtNom[1] . " DECIMAL(" .
                            str_replace(".", ",", droite($tabCleEtNom[0], strlen($tabCleEtNom[0]) - 1)) . "), ";
                    break;
                case "D":
                    $this->requete .= $tabCleEtNom[1] . " DATE, ";
                    break;
                case "E":
                    $this->requete .= $tabCleEtNom[1] . " INT, ";
                    break;
                case "F":
                    $this->requete .= $tabCleEtNom[1] . " CHAR(" .
                            droite($tabCleEtNom[0], strlen($tabCleEtNom[0]) - 1) . "), ";
                    break;
                case "M":
                    $this->requete .= $tabCleEtNom[1] . " DECIMAL(10,2), ";
                    break;
                case "N":
                    $this->requete .= $tabCleEtNom[1] . " INT NOT NULL, ";
                    break;
                case "V":
                    $this->requete .= $tabCleEtNom[1] . " VARCHAR(" .
                            droite($tabCleEtNom[0], strlen($tabCleEtNom[0]) - 1) . "), ";
                    break;
            }
        }
        if (!empty($strCles))
            $this->requete .= "PRIMARY KEY(" . $strCles . ")) ENGINE=InnoDB";
        $this->OK = mysqli_query($this->cBD, $this->requete);

        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | deconnexion
      |----------------------------------------------------------------------------------|
     */

    function deconnexion() {
        mysqli_close($this->cBD);
    }

    /*
      |----------------------------------------------------------------------------------|
      | insereEnregistrement
      |----------------------------------------------------------------------------------|
     */

    function insereEnregistrement($strNomTable) {
        $this->requete = "INSERT INTO $strNomTable VALUES (";
        for ($i = 1; $i < func_num_args(); $i++) {
            if (is_string(func_get_arg($i)))
                $this->requete .= "'" . str_replace("'", "''", func_get_arg($i)) . "'";
            else if (func_get_arg($i) == null)
                $this->requete .= "null";
            else
                $this->requete .= func_get_arg($i);

            $this->requete .= $i == func_num_args() - 1 ? ")" : ",";
        }
        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | modifieChamp
      |----------------------------------------------------------------------------------|
     */

    function modifieChamp($strNomTable, $strNomChamp, $strNouvelleDefinition) {
        $this->requete = "ALTER TABLE $strNomTable CHANGE $strNomChamp $strNouvelleDefinition";
        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | selectionneBD()
      |----------------------------------------------------------------------------------|
     */

    function selectionneBD() {
        $this->OK = mysqli_select_db($this->cBD, $this->nomBD);
        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | supprimeEnregistrements
      |----------------------------------------------------------------------------------|
     */

    function supprimeEnregistrements($strNomTable, $strListeConditions = "") {
        $this->requete = "DELETE FROM $strNomTable";

        if (!empty($strListeConditions))
            $this->requete .= " WHERE $strListeConditions";

        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | supprimeTable()
      |----------------------------------------------------------------------------------|
     */

    function supprimeTable($strNomTable) {
        $this->requete = "DROP TABLE " . $strNomTable;
        $this->OK = mysqli_query($this->cBD, $this->requete);

        return $this->OK;
    }

    /*
      |----------------------------------------------------------------------------------|
      | afficheInformationsSurBD()
      | Affiche la structure et le contenu de chaque table de la base de données recherchée
      |----------------------------------------------------------------------------------|
     */

    function afficheInformationsSurBD() {
        /* Si applicable, récupération du nom de la table recherchée */
        if (func_num_args() > 0) {
            $strNomTableRecherchee = func_get_arg(0);
        } else {
            $strNomTableRecherchee = "";
        }

        /* Variables de base pour les styles */
        $strTable = "border-collapse:collapse;";
        $strCommande = "font-family:verdana; font-size:12pt; font-weight:bold; color:black; border:solid 1px black; padding:3px;";
        $strMessage = "font-family:verdana; font-size:10pt; font-weight:bold; color:red;";
        $strBorduresMessage = "border:solid 1px red; padding:3px;";
        $strContenu = "font-family:verdana; font-size:10pt; color:blue;";
        $strBorduresContenu = "border:solid 1px red; padding:3px;";
        $strTypeADefinir = "color:red;font-weight:bold;";
        $strDetails = "color:magenta;";

        /* Application des styles */
        $sTable = "style=\"$strTable\"";
        $sCommande = "style=\"$strCommande\"";
        $sMessage = "style=\"$strMessage\"";
        $sMessageAvecBordures = "style=\"$strMessage $strBorduresMessage\"";
        $sContenu = "style=\"$strContenu\"";
        $sContenuAvecBordures = "style=\"$strContenu $strBorduresContenu\"";
        $sTypeADefinir = "style=\"$strTypeADefinir\"";
        $sDetails = "style=\"$strDetails\"";

        /* --- Entreposage des noms de table --- */
        $ListeTablesBD = array_column(mysqli_fetch_all(mysqli_query($this->cBD, 'SHOW TABLES')), 0);
        $intNbTables = count($ListeTablesBD);

        /* --- Parcours de chacune des tables --- */
        echo "<span $sCommande>Informations sur " . (!empty($strNomTableRecherchee) ? "la table '$strNomTableRecherchee' de " : "") . "la base de données '$this->nomBD'</span><br />";
        $binTablePresente = false;
        for ($i = 0; $i < $intNbTables; $i++) {
            /* Récupération du nom de la table courante */
            $strNomTable = $ListeTablesBD[$i];

            if (empty($strNomTableRecherchee) || strtolower($strNomTable) == strtolower($strNomTableRecherchee)) {
                $binTablePresente = true;
                echo "<p $sMessage>Table no " . strval($i + 1) . " : " . $strNomTable . "</p>";

                /* Récupération des enregistrements de la table courante */
                $ListeEnregistrements = mysqli_query($this->cBD, "SELECT * FROM $strNomTable");

                /* Décompte du nombre de champs et d'enregistrements de la table courante */
                $NbChamps = mysqli_field_count($this->cBD);
                $NbEnregistrements = mysqli_num_rows($ListeEnregistrements);
                echo "<p $sContenu>$NbChamps champs ont été détectés dans la table.<br />";
                echo "    $NbEnregistrements enregistrements ont été détectés dans la table.</p>";

                /* Affichage de la structure de table courante */
                echo "<p $sContenu>";
                $j = 0;
                $tabNomChamp = array();
                while ($champCourant = $ListeEnregistrements->fetch_field()) {
                    $intDivAjustement = 1;
                    $tabNomChamp[$j] = $champCourant->name;
                    $strType = $champCourant->type;
                    switch ($strType) {
                        case 1 : $strType = "BOOL";
                            break;
                        case 3 : $strType = "INTEGER";
                            break;
                        case 10 : $strType = "DATE";
                            break;
                        case 12 : $strType = "DATETIME";
                            break;
                        case 246 : $strType = "DECIMAL";
                            break;
                        case 253 : $strType = "VARCHAR";
                            break;
                        case 254 : $strType = "CHAR";
                            break;
                        default : $strType = "<span $sTypeADefinir>$strType à définir</span>";
                            break;
                    }
                    $strLongueur = intval($champCourant->length) / $intDivAjustement;
                    $intDetails = $champCourant->flags;
                    $strDetails = "";
                    if ($intDetails & 1)
                        $strDetails .= "[NOT_NULL] ";
                    if ($intDetails & 2)
                        $strDetails .= "<span style=\"font-weight:bold;\">[PRI_KEY]</span> ";
                    if ($intDetails & 4)
                        $strDetails .= "[UNIQUE_KEY] ";
                    if ($intDetails & 16)
                        $strDetails .= "[BLOB] ";
                    if ($intDetails & 32)
                        $strDetails .= "[UNSIGNED] ";
                    if ($intDetails & 64)
                        $strDetails .= "[ZEROFILL] ";
                    if ($intDetails & 128)
                        $strDetails .= "[BINARY] ";
                    if ($intDetails & 256)
                        $strDetails .= "[ENUM] ";
                    if ($intDetails & 512)
                        $strDetails .= "[AUTO_INCREMENT] ";
                    if ($intDetails & 1024)
                        $strDetails .= "[TIMESTAMP] ";
                    if ($intDetails & 2048)
                        $strDetails .= "[SET] ";
                    if ($intDetails & 32768)
                        $strDetails .= "[NUM] ";
                    if ($intDetails & 16384)
                        $strDetails .= "[PART_KEY] ";
                    if ($intDetails & 32768)
                        $strDetails .= "[GROUP] ";
                    if ($intDetails & 65536)
                        $strDetails .= "[UNIQUE] ";
                    echo ($j + 1) . ". $tabNomChamp[$j], $strType($strLongueur) <span $sDetails>$strDetails</span><br />";
                    $j++;
                }
                echo "</p>";

                /* Affichage des enregistrements composant la table courante */
                echo "<table $sTable>";
                echo "<tr>";
                for ($k = 0; $k < $NbChamps; $k++)
                    echo "<td $sMessageAvecBordures>" . $tabNomChamp[$k] . "</td>";
                echo "</tr>";
                if (empty($NbEnregistrements)) {
                    echo "<tr>";
                    echo "<td $sContenuAvecBordures colspan=\"$NbChamps\">";
                    echo " Aucun enregistrement";
                    echo "</td>";
                    echo "</tr>";
                }
                while ($listeChampsEnregistrement = $ListeEnregistrements->fetch_row()) {
                    echo "<tr>";
                    echo "<tr>";
                    for ($j = 0; $j < count($listeChampsEnregistrement); $j++)
                        echo "      <td $sContenuAvecBordures>" . $listeChampsEnregistrement[$j] . "</td>";
                    echo "   </tr>";
                }
                echo "</table>";
                $ListeEnregistrements->free();
            }
        }
        if (!$binTablePresente)
            echo "<p $sMessage>Aucune table !</p>";
    }

    /*
      |-------------------------------------------------------------------------------------|
      | mysqli_result
      | Réf.: http://php.net/manual/fr/class.mysqli-result.php User Contributed Notes (Marc17)
      |
      | Exemple d'appel : echo mysqli_result($ListeEnregistrements, 0, "TotalVentes");
      |                   Affiche le champ "TotalVentes" du 1er enregistrement de la liste
      |                   d'enregistrements.
      |-------------------------------------------------------------------------------------|
     */

    function mysqli_result($result, $row, $field = 0) {
        if ($result === false)
            return false;
        if ($row >= mysqli_num_rows($result))
            return false;
        if (is_string($field) && !(strpos($field, ".") === false)) {
            $t_field = explode(".", $field);
            $field = -1;
            $t_fields = mysqli_fetch_fields($result);
            for ($id = 0; $id < mysqli_num_fields($result); $id++) {
                if ($t_fields[$id]->table == $t_field[0] && $t_fields[$id]->name == $t_field[1]) {
                    $field = $id;
                    break;
                }
            }
            if ($field == -1)
                return false;
        }
        mysqli_data_seek($result, $row);
        $line = mysqli_fetch_array($result);
        return isset($line[$field]) ? $line[$field] : false;
    }

    /*
      |-------------------------------------------------------------------------------------|
      | etablitRelation($strNomTablePrimaire, $strClePrimaire, $strNomTableEtrangere, $strCleEtrangere, $strNomRelation="")
     * 
     * Établit une relation entre deux tables
      |-------------------------------------------------------------------------------------|
     */

    function etablitRelation($strNomTablePrimaire, $strClePrimaire, $strNomTableEtrangere, $strCleEtrangere, $strNomRelation = "") {

        $this->requete = "ALTER TABLE $strNomTableEtrangere";
        if (!empty($strNomRelation))
            $this->requete .= " ADD CONSTRAINT $strNomRelation";

        $this->requete .= " FOREIGN KEY ($strCleEtrangere) REFERENCES $strNomTablePrimaire($strClePrimaire);";

        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |-------------------------------------------------------------------------------------|
      | metAJourEnregistrements($strNomTable,$strListeChangements,$strListeConditions="")
     * 
     * Met à jour les données composant un ou plusieurs enregistrements selon une ou plusieurs conditions
      |-------------------------------------------------------------------------------------|
     */

    function metAJourEnregistrements($strNomTable, $strListeChangements, $strListeConditions = "") {

        $this->requete = "UPDATE $strNomTable SET $strListeChangements";
        if (!empty($strListeConditions))
            $this->requete .= " WHERE $strListeConditions";

                        
        $this->OK = mysqli_query($this->cBD, $this->requete);
        return $this->OK;
    }

    /*
      |-------------------------------------------------------------------------------------|
      | selectionneEnregistrements($strNomTable)
     * 
     * Par défaut, selectionne tous les enregistrements de la table $strNomTable. En fonction
     * des paramètres spécifiés, il est possible de sélectionner certains enregistrements
     * (C=), de les regrouper (D=), et ou de les trier(T=).
      |-------------------------------------------------------------------------------------|
     */

    function selectionneEnregistrements($strNomTable) {
        $this->requete = "SELECT * FROM $strNomTable";

        for ($i = 1; $i < func_num_args(); $i++) {
            $tableau = explode("=", func_get_arg($i));
            switch ($tableau[0]) {
                case "C": $this->requete .= " WHERE $tableau[1]=$tableau[2]";
                    break;
                case "D": $this->requete .= " GROUP BY $tableau[1]";
                    break;
                case "T": $this->requete .= " ORDER BY $tableau[1]";
                    break;
            }
        }

        $this->listeEnregistrements = mysqli_query($this->cBD, $this->requete);

        if ($this->listeEnregistrements)
            $this->nbEnregistrements = mysqli_num_rows($this->listeEnregistrements);

        return $this->listeEnregistrements ? mysqli_num_rows($this->listeEnregistrements) : -1;
    }

    function tableExiste($strNomTable) {
        $result = mysqli_query($this->cBD, "SHOW TABLES LIKE '" . $strNomTable . "'");

        return $result->num_rows == 1;
    }

    function contenuChamp($intNo, $strNomChamp) {
        return $this->mysqli_result($this->listeEnregistrements, $intNo, $strNomChamp);
    }

    function SelectionneChamp($strNomTable, $strChampAfficher, $strChampWhere, $strWhere) {
        $this->requete = "Select $strChampAfficher from $strNomTable where $strChampWhere= $strWhere";
        return $this->OK = mysqli_query($this->cBD, $this->requete);
    }

}

?>
