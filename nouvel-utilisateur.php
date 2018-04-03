<?php

header("Access-Control-Allow-Origin: *");

/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Gestion des documents (Administrateur)";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";

/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");

require_once("en-tete.php");

?>

<table class="sTableau">
    <tr>
        <td>
            Nom d'utilisateur :
        </td><td>
            <?php input("nomUtilisateur","","text", 15,"",true); ?>
        </td>
        <td>
            <?php
            if (empty(post("nomUtilisateur")) && isset($_POST["nomUtilisateur"]))
                echo "<div class=\"sRouge\"> Entrez un nom d'utilisateur! </div>"?>
        </td>
    </tr>
    <tr>
        <td>
            Mot de passe :
        </td><td>
            <?php input("motDePasse","","password", 15,"",true); ?>
        </td>
        <td>
            <?php if(empty(post("motDePasse")) && isset($_POST["motDePasse"]))
                echo "<div class=\"sRouge\"> Entrez un mot de passe! </div>"?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td align="right">
            <input id="btnCreer" name="btnCreer" type="submit" value="Creer Utilisateur" onclick="">
        </td>
    </tr>
</table>



<?php
require_once("pied-page.php");
?>