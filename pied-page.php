<div>
    <?php
    if (isset($_SESSION["connectee"])) {
        if ($_SESSION["connectee"]) {
            ?>
            <div>
                <button class="sButton" style="position: relative;font-family: Poppins-Regular;" onclick="deconnecter()">Deconnexion</button>
            </div>
            <script>
                function deconnecter() {
                    window.location.href = "logout.php";
                }
            </script>
            <?php
        }
    }
    ?>
    <br>
    <div style="bottom:0;">
        <a href="https://cgodin.omnivox.ca"><img src="omnivoxLogo.png" width="50" height="50"></a>
        <a href="https://mail.google.com"><img src="emailLogo.png" width="50" height="50"></a>
        <a href="https://www.cgodin.qc.ca/"><img src="logo_cegep.png" width="50" height="50"></a>
    </div>
    <div id="divPiedPage">
        <p class="sDroits">
            &copy; Département d'informatique G.-G.
        </p>
    </div>
</div>
</body>
</html>
