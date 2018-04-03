<?php


function connexion($strNomUtil,$strMotPasse,$objSQL){
    $booTrouve = false;
    $resultat = mysqli_query($objSQL->cBD,"SELECT * FROM Utilisateur");
    $nbAdmins = mysqli_query($objSQL->cBD,"SELECT count(statutAdmin) FROM Utilisateur where StatutAdmin = 1");


    if($resultat->num_rows == 0 || $nbAdmins->fetch_row()[0] == 0){
        if(strcasecmp($strNomUtil,"admin") == 0 && $strMotPasse == "admin"){
            echo "ALLO ";
            header("location: nouvel-utilisateur.php");
        }
    }
    else{
        while($ligne = $resultat->fetch_assoc()){

            if(strcasecmp($ligne["NomUtilisateur"], $strNomUtil) == 0)
                if($ligne["MotDePasse"] == $strMotPasse){
                    $booTrouve = true;
                    ecrit("<p class=\"sVert\"> Connexion ok </p>");
                }
        }
            if(!$booTrouve)
                ecrit("<p class=\"sRouge\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
        }
    return $booTrouve;
}
