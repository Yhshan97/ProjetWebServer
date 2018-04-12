<?php

function connexion($strNomUtil, $strMotPasse, $objSQL) {
    $booTrouve = false;
    $resultat = mysqli_query($objSQL->cBD, "SELECT * FROM Utilisateur");
    if ($resultat->num_rows == 0) {
        if (strcasecmp($strNomUtil, "admin") == 0 && $strMotPasse == "admin") {
            header("location: nouvel-utilisateur.php");
        } else {
            ecrit("<p class=\"sErreur\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
        }
    } else {
        while ($ligne = $resultat->fetch_assoc()) {
            if (strcasecmp($ligne["NomUtilisateur"], $strNomUtil) == 0)
                if ($ligne["MotDePasse"] == $strMotPasse) {
                    $booTrouve = true;
                    $_SESSION["NomComplet"] = $ligne["NomComplet"];
                    $_SESSION["courriel"] = $ligne["Courriel"];
                }
        }
        if (!$booTrouve)
            ecrit("<p class=\"sErreur\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
    }
    return $booTrouve;
}

function GestionSession($mode, $session, $dateDebut, $dateFin, $objSQL) {
    $booOK = false;

    if (preg_match("/^[HEAhea]-20((1[89])|(2[01]))/", $session)) {
        if (preg_match("/^20((1[89])|(2[01]))-(0[1-9]|1[0-2])-(([012][0-9])|(3[01]))/", $dateDebut) &&
                preg_match("/^20((1[89])|(2[01]))-(0[1-9]|1[0-2])-(([012][0-9])|(3[01]))/", $dateFin)) {
            $booOK = true;
        }
        if ($mode == "retir") {
            $booOK = true;
        }
    }

    if ($booOK) {
        switch ($mode) {
            case "ajout":
                $objSQL->insereEnregistrement("Session", $session, $dateDebut, $dateFin);
                break;
            case "modif":
                $objSQL->metAJourEnregistrements("Session", "DateDebut='$dateDebut', DateFin='$dateFin'", "Description='$session'");
                break;
            case "retir":
                $objSQL->supprimeEnregistrements("Session", "Description='$session'");
                break;
            default:
                return false;
        }
        return $objSQL->OK;
    }
}

function GestionCours($mode, $strSigle, $strTitreCours, $strNomProf, $objSQL) {
    $booEverything = false;
    if (preg_match("/^\d{3}-[[:alnum:]]{3}/", $strSigle)) {    
        if (strlen($strTitreCours) <= 50 && strlen($strTitreCours) >= 5 && preg_match("/^\w{5,50}/", $strNomProf) == 1) {        
            var_dump($booEverything);
            $booEverything = true;
        }
        if ($mode == "retir") {
            $booEverything = true;
        }
    }
    if ($booEverything) {
        switch ($mode) {
            case "ajout":
                $objSQL->insereEnregistrement("Cours", $strSigle, $strTitreCours, $strNomProf);
                break;
            case "modif": //a tester
                $objSQL->metAJourEnregistrements("Cours", "Sigle='$strSigle', Titre='$strTitreCours' , NomProf='$strNomProf'");
                break;
            case "retir": //a tester
                $objSQL->supprimeEnregistrements("Cours", "Sigle='$strSigle'");
                break;
            default:
                return false;
        }
        return $objSQL->OK;
    }
}

function gestionCoursSession($mode, $strCoursSession, $strSession, $strSigle, $strNomProf, $objSQL){
        $booOK = false;

        if($mode == "ajout"){
            if (preg_match("/-{4,9}/", $strNomProf) != 1 && preg_match("/-{4,9}/", $strSession) != 1 && preg_match("/-{4,9}/", $strSigle) != 1) 
            $booOK = true;
            }
        else if($mode == "modif"){
            if (preg_match("/-{4,9}/", $strCoursSession) != 1 && preg_match("/-{4,9}/", $strSession) != 1 && 
                    preg_match("/-{4,9}/", $strSigle) != 1 && preg_match("/-{4,9}/", $strNomProf)) 
            $booOK = true;
        }
        if ($mode == "retir") {
            if (preg_match("/-{4,9}/", $strCoursSession) != 1) 
            $booOK = true;
        }

    if ($booOK) {
        switch ($mode) {
            case "ajout":
                $objSQL->insereEnregistrement("coursSession", "$strSigle ($strSession)", $strSession, $strSigle, $strNomProf);
                break;
            case "modif":
                $objSQL->metAJourEnregistrements("coursSession", "Session='$strSession', Sigle='$strSigle', NomProf='$strNomProf'", "coursSession='$strCoursSession'");
                break;
            case "retir":
                $objSQL->supprimeEnregistrements("coursSession", "coursSession='$strCoursSession'");
                break;
        }
        return $objSQL->OK;
    }
    
}


function creerSelectHTML($strID, $strName, $strClass, $onchange, $tableauValues, $numerique = false) {
    $strSelectHTML = "<select id=\"$strID\" name=\"$strName\" class=\"$strClass\" onchange=\"$onchange\">";

    if ($numerique) {
        for ($i = 0; $i < count($tableauValues); $i++) {
            $strSelectHTML .= "<option value=\"" . ($i) . "\">" . $tableauValues[$i];
        }
    } else {
        foreach ($tableauValues as $val) {
            $strSelectHTML .= "<option value=\"$val\">" . $val;
        }
    }

    $strSelectHTML .= "</select>";
    return $strSelectHTML;
}

function creerSelectHTMLAvecRequete($strNomTable, $strNomColonne, $strCondition = "", $strID, $strName, $strClass, $onchange, $mySqli) {
    $mySqli->selectionneEnregistrements($strNomTable, $strCondition);
    $result = mysqli_query($mySqli->cBD, $mySqli->requete);

    while ($val = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Tableau[] = $val["$strNomColonne"];
    }
    if (!isset($Tableau))
        $Tableau[0] = " -------- ";

    return creerSelectHTML($strID, $strName, $strClass, $onchange, $Tableau);
}

function session($strNomVariable) {
    return isset($_SESSION["$strNomVariable"]) ? $_SESSION["$strNomVariable"] : false;
}

function GestionCategorieDocument($mode, $strDescription, $objSQL) {
    $booDescription = false;
    if (preg_match("/^\w{3,15}/", $strDescription)) {
        $booDescription = true;
        if ($mode == "retir") {
            $booDescription = true;
        }
    }
    var_dump($booDescription);
    if ($booDescription) {
        switch ($mode) {
            case "ajout":
                $objSQL->insereEnregistrement("Categorie", $strDescription);
                break;
            case "modif": //a tester
                $objSQL->metAJourEnregistrements("Categorie", "Description='$strDescription'");
                break;
            case "retir": //a tester
                $objSQL->supprimeEnregistrements("Categorie", "Description='$strDescription'");
                break;
            default:
                return false;
        }
        return $objSQL->OK;
    }
}