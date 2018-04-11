<?php


function connexion($strNomUtil,$strMotPasse,$objSQL){
    $booTrouve = false;
    $resultat = mysqli_query($objSQL->cBD,"SELECT * FROM Utilisateur");
        
    if($resultat->num_rows == 0){
        if(strcasecmp($strNomUtil,"admin") == 0 && $strMotPasse == "admin"){
            header("location: nouvel-utilisateur.php");
        }
        else {
            ecrit("<p class=\"sErreur\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
        }
    }
    else{
        while($ligne = $resultat->fetch_assoc()){
            if(strcasecmp($ligne["NomUtilisateur"], $strNomUtil) == 0)
                if($ligne["MotDePasse"] == $strMotPasse){
                    $booTrouve = true;
                    ecrit("<p class=\"sVert\"> Connexion ok </p>",1);
                    ecrit("<div class=\"sGras\">Nom Utilisateur : " . $ligne["NomUtilisateur"],1);
                    ecrit("Statut : " . ($ligne["StatutAdmin"] == 0 ? "Utilisateur":"Administrateur"),1);
                    ecrit("Nom Complet : " . $ligne["NomComplet"],1);
                    ecrit("Courriel : " . $ligne["Courriel"] . "</p>",1);
                }
        }
            if(!$booTrouve)
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
        else if($mode == "retirer"){
            $booOK = true;
        }
    }
    
    if ($booOK) {
        switch ($mode) {
            case "ajouter":
                $objSQL->insereEnregistrement("Session", $session, $dateDebut, $dateFin);
                break;
            case "modifier":
                $objSQL->metAJourEnregistrements("Session", "DateDebut='$dateDebut', DateFin='$dateFin'", "Description='$session'");
                break;
            case "retirer":
                //if( pas associé à un cours/cours-session/document ){
                    $objSQL->supprimeEnregistrements("Session", "Description='$session'");
                //}
                break;
            default:
                return false;
        }
        return $objSQL->OK;
    }
}


function GestionCours($mode, $strSigle, $strTitreCours, $objSQL) {
    $booSigleOk = false;
    $booTitreOk = false;
    if(strlen($strSigle) == 7 && ctype_digit(gauche($strSigle, 3)) && substr($strSigle, 3,1) == '-' && ctype_alnum(droite($strSigle, 3))){
        $booSigleOk = true;
    }
    
    if(strlen($strTitreCours)<=50 && strlen($strTitreCours)>=5 ){
        $booTitreOk = true;
    }
    
    if($booSigleOk && $booTitreOk){
        switch ($mode) {
            case "ajouter":
                $objSQL->insereEnregistrement("Cours", $strSigle, $strTitreCours);
                break;
            case "modifier": //a tester
                $objSQL->metAJourEnregistrements("Cours", "Sigle='$strSigle', Titre='$strTitreCours'");
                break;
            case "retirer": //a tester
                $objSQL->supprimeEnregistrements("Cours", "Where Sigle=$strSigle");
                break;
            default:
                return false;
        }
        return $objSQL->OK;
    }
}


function creerSelectHTML($strID,$strName,$strClass,$tableauValues){
    $strSelectHTML = "<select id=\"$strID\" name=\"$strName\" class=\"$strClass\">";
    
    for($i=1;$i<=count($tableauValues);$i++){
        $strSelectHTML .= "<option value=\"$i\">" . $tableauValues[$i];
    }
    $strSelectHTML .= "</select>";
}
