<?php
header("Access-Control-Allow-Origin: *");
session_start();
if (isset($_REQUEST["btnTeleversement"])) {
    $strNomDossier = "televersements/";
    $strNomFichier = $_FILES["tbNomFichier"]["name"];
    $strNomFichierTemp = $_FILES["tbNomFichier"]["tmp_name"];
    $strTypeFichier = $_FILES["tbNomFichier"]["type"];
    $binImage = strstr($strTypeFichier, "jpg") ||
            strstr($strTypeFichier, "jpeg") ||
            strstr($strTypeFichier, "bmp") ||
            strstr($strTypeFichier, "png") ||
            strstr($strTypeFichier, "gif");
    $intTaille = (int) $_FILES["tbNomFichier"]["size"];
    if (!is_uploaded_file($strNomFichierTemp)) {
        exit("Téléversement impossible...");
    }

    if (!move_uploaded_file($strNomFichierTemp, $strNomDossier . $strNomFichier)) {
        exit("Impossible de copier le fichier '$strNomFichier' dans le dossier '$strNomDossier'");
    }
}
/* Variables nécessaires pour les fichiers d'inclusion */
$strTitreApplication = "Mettre à jour les tables de r&eacutef&eacuterences";
$strNomFichierCSS = "index.css";
$strNomAuteur = "Yao Hua Shan, C&eacutedric Kouma, Alex Gariepy";



/* Liste des fichiers d'inclusion */
require_once("classe-fichier-2018-03-16.php");
require_once("classe-mysql-2018-03-17.php");
require_once("librairies-communes-2018-03-17.php");
require_once("librairies-projetFinal-2018-03-24.php");
require_once("background.php");
require_once("en-tete.php");


detecteServeur($strMonIP, $strIPServeur, $strNomServeur, $strInfosSensibles);

$mySqli = new mysql("", $strInfosSensibles);
?>

    <div id="divCorps">
        <p class="sTitreSection">
            Téléversement courant
        </p>
        <form id="frmTeleversement" method="post" enctype="multipart/form-data">
            <input id="tbNomFichier" name="tbNomFichier" style="height:25px;" class="" type="file" />
            <input id="btnTeleversement" name="btnTeleversement" style="height:25px;" class="" type="submit" value="Téléverser" />
        </form>
        <?php
        if (isset($_REQUEST["btnTeleversement"])) {
            ?>
            <p class="sTitreSection">
                Informations sur le fichier téléversé
            </p>
            <table>
                <tr>
                    <td><p>Nom du dossier :</p></td>
                    <td class="sGras">
                        <p> <?php echo $strNomDossier; ?></p>
                    </td>
                </tr>
                <tr>
                    <td><p> Nom du fichier téléversé :</p></td>
                    <td class="sGras">
                        <p>   <?php echo $strNomFichier; ?></p>
                    </td>
                </tr>
                <tr>
                    <td><p> Nom du fichier temporaire utilisé :</p></td>
                    <td class="sGras">
                        <p>    <?php echo basename($strNomFichierTemp); ?></p>
                    </td>
                </tr>
                <tr>
                    <td><p> Type du fichier :</p></td>
                    <td class="sGras">
                        <p>   <?php echo $strTypeFichier . " (" . ($binImage ? "Image" : "Pas une image") . ")"; ?></p>
                    </td>
                </tr>
                <tr>
                    <td><p> Taille du fichier :</p></td>
                    <td class="sGras">
                        <p>   <?php echo $intTaille; ?> octets</p>
                    </td>
                  
                </tr>
                <tr>
                    <td>
                        <a href="<?php echo $strNomDossier . $strNomFichier; ?>" target="_blank">
                            <?php echo $strNomDossier . $strNomFichier; ?>
                        </a>
                    </td>
                </tr>
            </table>
            <?php
        }
        ?>
    </div>
