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
   
   $strMonIP = "";
   $strIPServeur = "";
   $strNomServeur = "";
   $strInfosSensibles = "";
   detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

   $mySqli = new mysql("bdh18_Shan", $strInfosSensibles);

   $booConnexion = false;

   if(post("nomUtilisateur") && post("motDePasse"))
        if(connexion(post("nomUtilisateur"),post("motDePasse"),$mySqli))
            $booConnexion = true;


?>

<!-- A changer bien sur -->
<div <?php echo $booConnexion  ? "style=\"display:none\"": "" ?> >
<table class="sTableau">
   <tr>
      <td>
         Nom d'utilisateur :
      </td><td>
         <?php input("nomUtilisateur","","text", 15, post("nomUtilisateur"),true); ?>
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
         <input id="btnConnexion" name="btnConnexion" type="submit" value="Se connecter" onclick="">
      </td>
   </tr>
</table>
</div>

<div <?php echo !$booConnexion  ? "style=\"display:none\"": "" ?> >

<!--  ici   -->


</div>

<?php
   require_once("pied-page.php");
?>