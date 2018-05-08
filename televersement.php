<?php 

/*
 
 * Page de téléversement pour l'ajout d'un document

*/
$strMsgErreur = "";
    if(isset($_REQUEST["btnSubmit"])){
                $strNomDossier      = "televersements/";
                $strNomFichier      = $_FILES["tbFichier"]["name"];
                $strNomFichierTemp  = $_FILES["tbFichier"]["tmp_name"];
                $strTypeFichier     = $_FILES["tbFichier"]["type"];
                
                if (!is_uploaded_file($strNomFichierTemp)) {
                   $strMsgErreur = "Téléversement impossible...";
                }

                if (!move_uploaded_file($strNomFichierTemp,$strNomDossier.$strNomFichier)) {
                   $strMsgErreur = "Impossible de copier le fichier '$strNomFichier' dans le dossier '$strNomDossier'";
                }else{
                    session_start();
                    $_SESSION["nomFichierAjout"] = $strNomFichier;
                    $strMsgErreur = "Le téléversement à été effectué";
                    
                    ?>
                <script>    
                    window.opener.setNomFichier('<?php echo $strNomFichier; ?>');
                </script>
                    <?php 
                }
    }
?>
<body>
<div style='margin:0 auto;'>
    <form id="frmTeleversement" method="post" enctype="multipart/form-data">
        <table >
            <tr>
            <input type="file" name="tbFichier" >
            </tr>
            <tr>
            <input type='submit' name='btnSubmit' value='Téléverser' >
            </tr>
            <tr><br />
                <?php echo $strMsgErreur; ?>
            </tr>
        </table>
    </form>
</div>
</body>