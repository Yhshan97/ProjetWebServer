<?php

function connexion($strNomUtil, $strMotPasse, $objSQL) {
    $booTrouve = false;
    $resultat = mysqli_query($objSQL->cBD, "SELECT * FROM Utilisateur");
    if ($resultat->num_rows == 0) {
        if (strcasecmp($strNomUtil, "admin") == 0 && $strMotPasse == "admin") {
            $_SESSION["NomComplet"] = "";
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

function GestionCours($mode, $strSigle, $strTitreCours, $objSQL) {
    $booEverything = false;
    if (preg_match("/^\d{3}-[[:alnum:]]{3}/", $strSigle)) {
        if (strlen($strTitreCours) <= 50 && strlen($strTitreCours) >= 5) {
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
                $objSQL->insereEnregistrement("Cours", $strSigle, $strTitreCours);
                break;
            case "modif": //a tester
                $objSQL->metAJourEnregistrements("Cours", "Sigle='$strSigle', Titre='$strTitreCours'");
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

function gestionCoursSession($mode, $strCoursSession, $strSession, $strSigle, $strNomProf, $objSQL) {
    $booOK = false;

    if ($mode == "ajout") {
        if (preg_match("/-{4,9}/", $strNomProf) != 1 && preg_match("/-{4,9}/", $strSession) != 1 && preg_match("/-{4,9}/", $strSigle) != 1)
            $booOK = true;
    }
    else if ($mode == "modif") {
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

    $Tableau[0] = " -------- ";
    while ($val = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Tableau[] = $val["$strNomColonne"];
    }
        

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
    if ($booDescription) {
        switch ($mode) {
            case "ajout":
                $objSQL->insereEnregistrement("Categorie", $strDescription);
                break;
            case "modif": //a tester
                $objSQL->metAJourEnregistrements("Categorie", "Description='" . func_get_arg(3) . "'", "Description='$strDescription'");
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

function gestionUtilisateur($mode, $strUtilSelect, $strNom, $strMotDePasse, $booStatut, $strNomComplet, $strCourriel, $objSQL) {

    if ($mode == "retir") {
        if (preg_match("/-{4,9}/", $strUtilSelect) != 1) {
            $objSQL->supprimeEnregistrements("utilisateur", "NomUtilisateur='$strUtilSelect'");
            return $objSQL->OK;
        } else return false;
    } else if ($mode == "modif") {
        if (preg_match("/\w{1,2}.\w{2,25}/", $strNom) && preg_match("/.{3,25}/", $strMotDePasse) &&
                preg_match("/\D+, \D+/", $strNomComplet) && preg_match("/\w{10,50}/", $strCourriel)) {
            $objSQL->metAJourEnregistrements("utilisateur", "NomUtilisateur='$strNom', MotDePasse='$strMotDePasse', StatutAdmin='$booStatut', NomComplet='$strNomComplet', Courriel='$strCourriel'", "NomUtilisateur='$strUtilSelect'");
            return $objSQL->OK;
        }
        else return false;
    }
}

function creerSelectAvecValeur($strNomTable, $strNomColonne, $strCondition = "", $strID, $strName, $strClass,$strValue, $onchange, $mySqli, $numerique=false){
    $mySqli->selectionneEnregistrements($strNomTable, $strCondition);
    $result = mysqli_query($mySqli->cBD, $mySqli->requete);

    $Tableau[0] = " -------- ";
    while ($val = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Tableau[] = $val["$strNomColonne"];
    }

    $strSelectHTML = "<select id=\"$strID\" name=\"$strName\" class=\"$strClass\" onchange=\"$onchange\">";

    if ($numerique) {
        for ($i = 0; $i < count($Tableau); $i++) {
            $strSelectHTML .= "<option value=\"" . ($i) . "\">" . $Tableau[$i];
        }
    } else {
        foreach ($Tableau as $val) {
            $choisi = $strValue === $val ? "selected": "";
            $strSelectHTML .= "<option value=\"$val\" $choisi>" . $val;
        }
    }

    $strSelectHTML .= "</select>";
    return $strSelectHTML;
}



function valideNomUtilisateur($strNomUtilisateur){
     return preg_match("/\w{1,2}.\w{2,25}/", $strNomUtilisateur);
}

function valideMDP($strMDP){
    return preg_match("/.{3,25}/", $strMDP);
}

function valideNomCOmplet($strNomComplet){
    return preg_match("/\D+, \D+/", $strNomComplet);
}

function valideCourriel($strCourriel){
    return preg_match("/\w{10,50}/", $strCourriel);
}

function valideSigle($strSigle){
    return preg_match("/-{4,9}/", $strSigle);
}