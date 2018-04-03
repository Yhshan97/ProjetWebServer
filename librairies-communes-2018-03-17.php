<?php


/*|--------------------------------------------------------------------------|
   5.e
  |--------------------------------------------------------------------------|*/

function extraitJJMMAAA(&$intJour, &$intMois, &$intAnnee){
   
    if(func_num_args() == 3)
        $strDate = date("d-m-Y");
    else 
        $strDate = func_get_arg (3);
    
    $intJour = convertitSousChaineEnEntier($strDate, 0, 2);
    $intMois = convertitSousChaineEnEntier($strDate, 3, 2);
    $intAnnee = convertitSousChaineEnEntier($strDate, 6, 4);
}

/*|--------------------------------------------------------------------------|
   Exercise 3
  |--------------------------------------------------------------------------|*/

function extraitJSSJJMMAAAA(&$intJourSemaine, &$intJour, &$intMois, &$intAnnee){
    $strDate;
    if(func_num_args() == 4){
        $strDate = date("d-m-Y"); 
        $intJourSemaine = date("N");
    }
    else if(func_num_args() == 5){
        $strDate = func_get_arg(4);
        $intJourSemaine = date("N",strtotime($strDate));
    }
    
    extraitJJMMAAA($intJour, $intMois, $intAnnee, $strDate);
}

/*|--------------------------------------------------------------------------|
   Exercise 1
  |--------------------------------------------------------------------------|*/


function jourSemaineEnLitteral($intNoJour, $binMajuscule = false){
   $strJour = "N/A";
    
   switch($intNoJour){
       case 1: $strJour = "lundi";      break;
       case 2: $strJour = "mardi";      break;
       case 3: $strJour = "mercredi";   break;
       case 4: $strJour = "jeudi";      break;
       case 5: $strJour = "vendredi";   break;
       case 6: $strJour = "samedi";     break;
       case 7: $strJour = "dimanche";   break;
   }
   
   $strJour = $binMajuscule ? ucwords($strJour) : $strJour;
   
   return $strJour;
}


/*|--------------------------------------------------------------------------|
   5.f
  |--------------------------------------------------------------------------|*/

function moisEnLitteral($intMois, $binMajuscule = false){
    $strMois = "N/A";
    switch ($intMois){
        case 1: $strMois = "janvier";           break;
        case 2: $strMois = "f&eacute;vrier";    break;
        case 3: $strMois = "mars";              break;
        case 4: $strMois = "avril";             break;
        case 5: $strMois = "mai";               break;
        case 6: $strMois = "juin";              break;
        case 7: $strMois = "juillet";           break;
        case 8: $strMois = "ao&ucirc;t";        break;
        case 9: $strMois = "septembre";         break;
        case 10: $strMois = "octobre";          break;
        case 11: $strMois = "novembre";         break;
        case 12: $strMois = "d&eacute;cembre";  break;
    }
    $strMois = $binMajuscule ? ucfirst($strMois):$strMois;
    
    return $strMois;
}

/*|--------------------------------------------------------------------------|
   5.d
  |--------------------------------------------------------------------------|*/


function convertitSousChaineEnEntier($strChaine,$intDepart,$intLongueur){
    $intEntier = intval(substr($strChaine,$intDepart,$intLongueur));
    return $intEntier;
}

/*|--------------------------------------------------------------------------|
   Exercise 2
  |--------------------------------------------------------------------------|*/


function er ($intEntier, $binExposant = true){
    $booPremier = $intEntier == 1;
    return $booPremier ? ($binExposant ? $intEntier . "<sup>er</sup>" : $intEntier . "er") : $intEntier;
}


function ajouteZeros($numValeur, $intLargeur){
    $strFormat = "%0".$intLargeur."d";
    return sprintf($strFormat,$numValeur);
}


function JJMMAAAA($intJour,$intMois,$intAnnee){
    if($intAnnee <=20 && $intAnnee >=0)
        $intAnnee +=2000;
    else if ($intAnnee >=21 && $intAnnee <= 99)
        $intAnnee += 1900;
    
    return ajouteZeros($intJour,2) . "-" . ajouteZeros($intMois,2) . "-" . ajouteZeros($intAnnee, 4);
}


/*
 * function aujourdhui($binAAAAMMJJ=true)
 * 
 * Retourne la date courante en format aaaa-mm-jj par defaut ou jj-mm-yyyy
 */
function aujourdhui($binAAAAMMJJ=true){
    return ($binAAAAMMJJ) ? date("Y-m-d") : date("d-m-Y");
}


/*
 * function bissextile($intAnnee)
 * 
 * Retourne true si l'annee passee en argument est bissextile; autrement false.
 */
function bissextile($intAnnee){
    return date("L",  strtotime("01-01-".$intAnnee)) ? true:false;
}


/*
 * function nombreJoursAnnee($intAnnee)
 * 
 * Retourne le nombre de jours de l'annee saisie en argument (365 ou 366)
 */
function nombreJoursAnnee($intAnnee){
    return bissextile($intAnnee)? 366:365;
}


/*
 * function nombreJoursMois($intMois, $intAnnee)
 * 
 * Retourne le nombre de jours du mois/annee saisi en argument (28 a 31)
 */
function nombreJoursMois($intMois,$intAnnee){
    return date("t",  strtotime("01-".$intMois."-".$intAnnee));
}


/*
 * function nombreJoursEntreDeuxDates($strDate1, $strDate2)
 * 
 * Retourne le nombre de jours entre $strDate1 et $strDate2. 
 * Une date ne peut etre anterieure au 14 decembre 1901
 */
function nombreJoursEntreDeuxDates($strDate1, $strDate2){
    return (strtotime($strDate1) >= strtotime("14-12-1901") && strtotime($strDate2) >= strtotime("14-12-1901"))? 
            abs((strtotime($strDate1) - strtotime($strDate2)) /(60*60*24)): "Date invalide";
}


/*
 * function extraitJSJJMMAAAAv2(&$intJourSemaine, &$intJour, &$intMois, &$intAnnee [,$strDate])
 * 
 * Meilleure version de extraitJSJJMMAAAA. $strDate peut etre en 
 * format "jj-mm-aaaa" ou "aaaa-mm-jj".
 */
function extraitJSJJMMAAAAv2(&$intJourSemaine,&$intJour,&$intMois,&$intAnnee){
    $strDate;
    if(func_num_args() == 4){
        $strDate = date("d-m-Y"); 
        $intJourSemaine = date("N");
    }
    else if(func_num_args() == 5){
        $strDate = func_get_arg(4);
        $strDate1 = explode("-",func_get_arg(4));
        
        if(strlen($strDate1[0]) == 4){
            $intJour = $strDate1[2];
            $intMois = $strDate1[1];
            $intAnnee = $strDate1[0];
            $strDate = $intJour."-".$intMois."-".$intAnnee;
        }else if(strlen($strDate1[0]) == 2){
            $intJour = $strDate1[0];
            $intMois = $strDate1[1];
            $intAnnee = $strDate1[2];
        }
        $intJourSemaine = date("N",strtotime($strDate));
    }
    extraitJJMMAAA($intJour, $intMois, $intAnnee, $strDate);
}

function JourDeSemaineDeDate($strDate){
    return date("N",strtotime($strDate));
}


/*
 * function dateValide($strDate)
 * 
 * Retourne true si la date passee en argument est valide. La date peut etre de 
 * format aaaa-mm-jj ou jj-mm-aaaa.
 */
function dateValide($strDate){
    $intJour;
    $intMois;
    $intAnnee;
    $intJS;
    extraitJSJJMMAAAAv2($intJS, $intJour, $intMois, $intAnnee,$strDate);
    return checkdate($intMois, $intJour, $intAnnee);
}


/*
 * function dateEnLitteral([$strDate],["C"])
 * 
 * Retourne la date passee en parametre sous forme litterale.
 * dateEnLitteral() retourne la date courante en litteral
 * dateEnLitteral("c") retourne la date courante avec JourSemaine
 * dateEnLitteral("c","2018-05-12") retourne la date saisie avec JourSemaine
 * 
 */
function dateEnLitteral(){
    $intJour;   $intMois;   $intAnnee;  $intJS;
    
    if(func_num_args() == 0){
        extraitJSJJMMAAAAv2($intJS, $intJour, $intMois, $intAnnee);
        echo er($intJour) . " " . moisEnLitteral($intMois) . " " . $intAnnee;
    }
    else if(func_num_args() == 1){
        if(strlen(func_get_arg(0)) == 10){
           extraitJSJJMMAAAAv2($intJS, $intJour, $intMois, $intAnnee,func_get_arg(0));
           echo er($intJour) . " " . moisEnLitteral($intMois) . " " . $intAnnee;
        }
        else if(strtoupper(func_get_arg(0)) == "C"){
           extraitJSJJMMAAAAv2($intJS, $intJour, $intMois, $intAnnee,  aujourdhui());
           echo jourSemaineEnLitteral ($intJS) . " " .er($intJour) . " " . moisEnLitteral($intMois) . " " . $intAnnee;
        }
        else 
            echo "Syntaxe invalide de dateEnLitteral. dateEnLitteral([\"c\"],[\"YYYY-mm-dd\" \\ \"dd-mm-YYYY\"]) .";
        
    }
    else if(func_num_args() == 2) {
        extraitJSJJMMAAAAv2($intJS, $intJour, $intMois, $intAnnee,func_get_arg(strtoupper(func_get_arg(0)) == "C"));
        echo jourSemaineEnLitteral ($intJS) . " " . er($intJour) . " " . moisEnLitteral($intMois) . " " . $intAnnee;
    }
}


/*
 * function AAAAMMJJ($strDate)
 * 
 * retourne la date en format aaaa-mm-jj
 * e-g. AAAAMMJJ("31-12-18") , AAAAMMJJ("31-12-2018")
 *      AAAAMMJJ(31, 12, 18) , AAAAMMJJ(31, 12, 2018)
 */
function AAAAMMJJ($strDate){
    if(func_num_args() == 1){
        $tabDate = explode("-", $strDate);
        
        if(strlen($tabDate[0]) == 4)
            list($intAnnee,$intMois,$intJour) = $tabDate;
        else
            list($intJour,$intMois,$intAnnee) = $tabDate;
    }
    else if(func_num_args() == 3)
        list($intJour,$intMois,$intAnnee) = func_get_args();
    
    if($intAnnee <= 20 && $intAnnee >= 0)
        $intAnnee += 2000;
    else if ($intAnnee >= 21 && $intAnnee <= 99)
        $intAnnee += 1900;
    
    return "$intAnnee-$intMois-$intJour";
    
}


/*
 * function get($strNomParametre)
 * 
 * retourne la valeur du parametre si valide sinon null
 */
function get($strNomParametre){
    return isset($_GET["$strNomParametre"]) ? $_GET["$strNomParametre"] : null;
}

/*
 * function post($strNomParametre)
 * 
 * retourne la valeur du parametre si valide sinon null
 */
function post($strNomParametre){
    return isset($_POST["$strNomParametre"]) ? $_POST["$strNomParametre"] : isset($_POST["$strNomParametre"]);
}


/*
 * function input($strID, $strCLASS, $strType, $strMAXLENGTH, $strVALUE, $binECHO=false)
 * 
 * Genere une balise <input type="text" /> avec ou sans la commande echo ($binEcho)
 */
function input($strID, $strCLASS, $strType, $strMAXLENGTH, $strVALUE, $binECHO=false){
    
    $strResultat = "<input id=\"$strID\" name=\"$strID\" class=\"$strCLASS\" type=\"$strType\" maxlength=\"$strMAXLENGTH\" value=\"$strVALUE\" />";
    
    if ($binECHO)
        echo $strResultat;
    
    else
        return $strResultat;
}

function annee($strDate){
    extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee,$strDate);
    return $intAnnee;
}

function jour($strDate){
    extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee,$strDate);
    return $intJour;
}

function mois($strDate){
    extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee,$strDate);
    return $intMois;
}


/* 

				Projet 01

*/

function hierOuDemain($date,$booDemain=true){
    return $booDemain ? date('Y-m-d', strtotime($date .' +1 day')) : 
        date('Y-m-d', strtotime($date .' -1 day')); 
}

function dollar($intMontant){
    $intNbDecimal = 2;
    if(func_num_args()==2){
        $intNbDecimal = func_get_arg (1);
    }
    return number_format(floatval($intMontant),$intNbDecimal,","," ");
}

function dollarParentheses($intMontant){
    return floatval($intMontant) < 0 ? "(".dollar(str_replace('-','',$intMontant)).")" : dollar($intMontant);
}

function pourcent($nombre){
    return floatval($nombre / 100);
}

/* 

				Exercice 03

*/


function decomposeURL($strURL, &$strChemin, &$strNom, &$strSuffixe){
    //Trouve la position du dernier point et divise le string en deux -> dans $tabValeurs
    $intIndexDernierPoint = strrpos($strURL,".");
    $tabValeurs = str_split($strURL, $intIndexDernierPoint);
    
    //Si le URL a des barre obliques
    if(strrpos($tabValeurs[0],"/")){
        $strChemin = substr($tabValeurs[0],0, strrpos($tabValeurs[0],"/"));
        $strNom = substr($tabValeurs[0], strrpos($tabValeurs[0],"/")+1,$intIndexDernierPoint);
    }
    //Si le URL est un fichier sans barre obliques
    else{
        $strChemin = ".";
        $strNom = $tabValeurs[0];
    }
    
    $strSuffixe = str_replace(".","",$tabValeurs[1]);
}

function droite($strChaine, $intLargeur){
    return substr($strChaine,strlen($strChaine)-$intLargeur,strlen($strChaine));
}

function ecrit($strChaine, $intNbBR=0){
    for($i=0;$i<$intNbBR;$i++){
        $strChaine = $strChaine."<br />";
    }
    echo $strChaine;
}

function estNumerique($strValeur){
    return is_numeric(str_replace(",", ".",$strValeur));
}

function etatCivilValide($chrEtat, $chrSexe, &$strEtatCivil){
    $strEtatCivil = false;
    switch(strtoupper($chrEtat)){
        case "C": $strEtatCivil = "Célibataire";
            break;
        case "F": $strEtatCivil = strtoupper($chrSexe) == "M" ? "Conjoint de fait" : "Conjointe de fait";
            break;
        case "M": $strEtatCivil = strtoupper($chrSexe) == "M" ? "Marié" : "Mariée";
            break;
        case "S": $strEtatCivil = strtoupper($chrSexe) == "M" ? "Séparé" : "Séparée";
            break;
        case "D": $strEtatCivil = strtoupper($chrSexe) == "M" ? "Divorcé" : "divorcée";
            break;
        case "V": $strEtatCivil = strtoupper($chrSexe) == "M" ? "Veuf" : "Veuve";
            break;
    }
    return $strEtatCivil ? $strEtatCivil : false;
    
}

function gauche($strChaine, $intLargeur){
    return substr($strChaine, 0,$intLargeur);
}

function majuscules($strChaine){
    return mb_strtoupper($strChaine);    
}

function minuscules($strChaine){
    return mb_strtolower($strChaine);
}

/* 

				Exercice 04

*/

/*
|-------------------------------------------------------------------------------------|
| detecteServeur (2017-03-18)
|-------------------------------------------------------------------------------------|
*/
function detecteServeur(&$strMonIP, &$strIPServeur, &$strNomServeur, &$strInfosSensibles) {
  $strMonIP = $_SERVER["REMOTE_ADDR"];
  $strIPServeur = $_SERVER["SERVER_ADDR"];
  $strNomServeur = $_SERVER["SERVER_NAME"];
  $strInfosSensibles = str_replace(".", "-", $strNomServeur) . ".php";
}



?>