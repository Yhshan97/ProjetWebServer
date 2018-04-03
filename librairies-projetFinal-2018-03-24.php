<?php


function connexion($strNomUtil,$strMotPasse,$objSQL){
    $booTrouve = false;
    $resultat = mysqli_query($objSQL->cBD,"SELECT * FROM Utilisateur");
        
    if($resultat->num_rows == 0){
        if(strcasecmp($strNomUtil,"admin") == 0 && $strMotPasse == "admin"){
            header("location: nouvel-utilisateur.php");
        }
        else {
            ecrit("<p class=\"sRouge\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
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
                ecrit("<p class=\"sRouge\"> Mauvaise combination de nom d'utilisateur/mot de passe </p>");
    }
    return $booTrouve;
}
