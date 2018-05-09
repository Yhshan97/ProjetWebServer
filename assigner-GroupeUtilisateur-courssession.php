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
        <table style="width: 1280px;">
            <tr class="sEntete">
                <td>
                    NomUtilisateur
                </td>
                <td>
                    MotDePasse
                </td>
                <td>
                    NomComplet
                </td>
                <td>
                    Courriel
                </td>
                <td>
                    Sigle1
                </td>
                <td>
                    Sigle2
                </td>
                <td>
                    Sigle3
                </td>
                <td>
                    Sigle4
                </td>
                <td>
                    Sigle5
                </td>
                <td>
                    Verdict
                </td>
            </tr>
            <?php
            $tab = csv_to_array("televersements/Fichier.csv", ";");
            
            $verdict = "";
            $object = new mysql("pjf_immigrants", $strInfosSensibles);
            for ($index = 0; $index < count($tab); $index++) {
                echo "<tr style=\"background-color: whitesmoke;\">";
                $booNomUtilOK = FALSE;
            $booMDPOK = FALSE;
            $booNomCompletOK = FALSE;
            $booCourrielOK = FALSE;
            $booSigle1OK = FALSE;
            $booSigle2OK = FALSE;
            $booSigle3OK = FALSE;
            $booSigle4OK = FALSE;
            $booSigle5OK = FALSE;
                foreach ($tab[$index] as $key => $val) {
                    echo "<th>" . $tab[$index][$key] . "</th>";
                    if ($key == "NomUtilisateur") {
                        if ($tab[$index][$key] != "") {
                            $object->selectionneEnregistrements("utilisateur", "C=NomUtilisateur=" . $tab[$index][$key]);
                            var_dump($object->listeEnregistrements);
                            if ($object->nbEnregistrements == 0) {
                                $booNomUtilOK = valideNomUtilisateur($tab[$index][$key]);
                            }
                        }
                    } else if ($key == "MotDePasse") {
                        if ($tab[$index][$key] != "") {
                            $booMDPOK = valideMDP($tab[$index][$key]);
                        }
                    } else if ($key == "NomComplet") {
                        if ($tab[$index][$key] != "") {
                            $booNomCompletOK = valideNomCOmplet($tab[$index][$key]);
                        }
                    } else if ($key == "Courriel") {
                        if ($tab[$index][$key] == "") {
                            $booCourrielOK = TRUE;
                        } else {
                            $booCourrielOK = valideCourriel($tab[$index][$key]);
                        }
                    } else if ($key == "Sigle1") {
                        if ($tab[$index][$key] == "") {
                            $booSigle1OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            echo $object->requete;
                            if ($object->nbEnregistrements == 1) {
                                $booSigle1OK = TRUE;
                            }
                        }
                    } else if ($key == "Sigle2") {
                        if ($tab[$index][$key] == "") {
                            $booSigle2OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            echo $object->requete;
                            if ($object->nbEnregistrements == 1) {
                                $booSigle2OK = TRUE;
                            }
                        }
                    } else if ($key == "Sigle3") {
                        if ($tab[$index][$key] == "") {
                            $booSigle3OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle3OK = TRUE;
                            }
                        }
                    } else if ($key == "Sigle4") {
                        if ($tab[$index][$key] == "") {
                            $booSigle4OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle4OK = TRUE;
                            }
                        }
                    } else if ($key == "Sigle5") {
                        if ($tab[$index][$key] == "") {
                            $booSigle5OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle5OK = TRUE;
                            }
                        }
                    }
                    $verdict = ($booNomUtilOK && $booMDPOK && $booNomCompletOK && $booCourrielOK && $booSigle1OK && $booSigle2OK && $booSigle3OK && $booSigle4OK && $booSigle5OK ? "OK" : "PAS OK");
                }
                echo "<th>$verdict</th>";
                echo "<th> NomUtil = $booNomUtilOK </th>";
                echo "<th> MDP = $booMDPOK </th>";
                echo "<th> NomComplet = $booNomCompletOK </th>";
                echo "<th> Courriel = $booCourrielOK </th>";
                echo "<th> Sigle1 = $booSigle1OK </th>";
                echo "<th> Sigle2 = $booSigle2OK </th>";
                echo "<th> Sigle3 = $booSigle3OK </th>";
                echo "<th> SIgle4 = $booSigle4OK </th>";
                echo "<th> Sigle5 = $booSigle5OK </th>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php
    }
    ?>
</div>
