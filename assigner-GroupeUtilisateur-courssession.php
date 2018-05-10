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
            $booToutEstValidé = true;
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
                $booAucunDoublonSigle1 = TRUE;
                $booAucunDoublonSigle2 = TRUE;
                $booAucunDoublonSigle3 = TRUE;
                $booAucunDoublonSigle4 = TRUE;
                $booAucunDoublonSigle5 = TRUE;

                foreach ($tab[$index] as $key => $val) {

                    if ($key == "NomUtilisateur") {
                        $strNomUtil = $tab[$index][$key];
                        if ($tab[$index][$key] != "") {
                            $booNomUtilOK = valideNomUtilisateur($tab[$index][$key]);
                        }
                        echo $booNomUtilOK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "MotDePasse") {
                        $strMDP = $tab[$index][$key];
                        if ($tab[$index][$key] != "") {
                            $booMDPOK = valideMDP($tab[$index][$key]);
                        }
                        echo $booMDPOK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "NomComplet") {
                        $strNomComplet = $tab[$index][$key];
                        if ($tab[$index][$key] != "") {
                            $booNomCompletOK = valideNomCOmplet($tab[$index][$key]);
                        }
                        echo $booNomCompletOK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Courriel") {
                        $strCourriel = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booCourrielOK = TRUE;
                        } else {
                            $booCourrielOK = valideCourriel($tab[$index][$key]);
                        }
                        echo $booCourrielOK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Sigle1") {
                        $strSigle1 = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booSigle1OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle1OK = TRUE;
                            }
                        }
                        echo $booSigle1OK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Sigle2") {
                        $strSigle2 = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booSigle2OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle2OK = TRUE;
                            }
                        }
                        echo $booSigle2OK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Sigle3") {
                        $strSigle3 = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booSigle3OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle3OK = TRUE;
                            }
                        }
                        echo $booSigle3OK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Sigle4") {
                        $strSigle4 = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booSigle4OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle4OK = TRUE;
                            }
                        }
                        echo $booSigle4OK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    } else if ($key == "Sigle5") {
                        $strSigle5 = $tab[$index][$key];
                        if ($tab[$index][$key] == "") {
                            $booSigle5OK = true;
                        } else {
                            $object->selectionneEnregistrements("cours", "C=Sigle='" . $tab[$index][$key] . "'");
                            if ($object->nbEnregistrements == 1) {
                                $booSigle5OK = TRUE;
                            }
                        }
                        echo $booSigle5OK ? "<th class=\"sBgVert\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>" : "<th class=\"sBgRouge sBlanc\">" . str_replace('�', '&eacute;', $tab[$index][$key]) . "</th>";
                    }
                }

                $object->selectionneEnregistrements("utilisateur", "C=NomUtilisateur='" . $strNomUtil . "'");
                if ($object->nbEnregistrements == 0) {
                    if ($booNomUtilOK && $booMDPOK && $booNomCompletOK && $booCourrielOK) {
                        $object->insereEnregistrement("utilisateur", "$strNomUtil", "$strMDP", "0", "$strNomComplet", "$strCourriel");
                        echo ($object->OK ? "L'utilisateur $strNomUtil n'existe pas dans la base de données mais a été ajouté avec succès </br></br>" : "L'utilisateur $strNomUtil n'existe pas dans la base de données mais n'a pas pu être ajouté </br></br>");
                    }
                } else {
                    echo "Mercééééééééééé</br></br>";
                }


                if ($strSigle1 != "") {
                    if (trouveDansChaine($strSigle1, "$strSigle2,$strSigle3,$strSigle4,$strSigle5", $intPos)) {
                        $booAucunDoublonSigle1 = FALSE;
                    }
                }
                if ($strSigle2 != "") {
                    if (trouveDansChaine($strSigle2, "$strSigle1,$strSigle3,$strSigle4,$strSigle5", $intPos)) {
                        $booAucunDoublonSigle2 = FALSE;
                    }
                }
                if ($strSigle3 != "") {
                    if (trouveDansChaine($strSigle3, "$strSigle2,$strSigle1,$strSigle4,$strSigle5", $intPos)) {
                        $booAucunDoublonSigle3 = FALSE;
                    }
                }
                if ($strSigle4 != "") {
                    if (trouveDansChaine($strSigle4, "$strSigle2,$strSigle3,$strSigle1,$strSigle5", $intPos)) {
                        $booAucunDoublonSigle4 = FALSE;
                    }
                }
                if ($strSigle5 != "") {
                    if (trouveDansChaine($strSigle5, "$strSigle2,$strSigle3,$strSigle4,$strSigle1", $intPos)) {
                        $booAucunDoublonSigle5 = FALSE;
                    }
                }
                // echo ($booNomUtilOK && $booMDPOK && $booNomCompletOK && $booCourrielOK && $booSigle1OK && $booSigle2OK && $booSigle3OK && $booSigle4OK && $booSigle5OK && $booAucunDoublonSigle1 && $booAucunDoublonSigle2 && $booAucunDoublonSigle3 && $booAucunDoublonSigle4 && $booAucunDoublonSigle5 ? "<th id=$index class=\"sBgVert\">" : "<th id=$index class=\"sBgRouge\">");
                $booOK = $booNomUtilOK && $booMDPOK && $booNomCompletOK && $booCourrielOK && $booSigle1OK && $booSigle2OK && $booSigle3OK && $booSigle4OK && $booSigle5OK && $booAucunDoublonSigle1 && $booAucunDoublonSigle2 && $booAucunDoublonSigle3 && $booAucunDoublonSigle4 && $booAucunDoublonSigle5;
                $verdict = ($booOK ? "OK" : "PAS OK");
                if ($verdict == "PAS OK") {
                    $booToutEstValidé = false;
                }
               // $booToutEstValidé = $booToutEstValidé && $booOK ? TRUE : FALSE;
                echo "<th>$verdict</th>";
                /* echo "<th> NomUtil = $booNomUtilOK </th>";
                  echo "<th> MDP = $booMDPOK </th>";
                  echo "<th> NomComplet = $booNomCompletOK </th>";
                  echo "<th> Courriel = $booCourrielOK </th>";
                  echo "<th> Sigle1 = $booSigle1OK </th>";
                  echo "<th> Sigle2 = $booSigle2OK </th>";
                  echo "<th> Sigle3 = $booSigle3OK </th>";
                  echo "<th> Sigle4 = $booSigle4OK </th>";
                  echo "<th> Sigle5 = $booSigle5OK </th>"; */
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        var_dump($booToutEstValidé);
        if ($booToutEstValidé) {
            echo creerSelectHTMLAvecRequete("session", "Description", "", "session", "session", "sList", "", $object);
        }
        else {
            ?>
        <script type="text/javascript">
            alert("Certaines valeurs dans le fichier <?php echo $strNomFichier ?> ne sont pas correct.  Veuillez les corriger au risque de ne pas pouvoir avancer");
        </script>
                <?php
        }
    }
    ?>
</div>
