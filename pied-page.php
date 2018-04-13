

      <div id="divPiedPage" style="position: fixed; bottom: 10px;right=0;">
         <p class="sDroits">
            &copy; Département d'informatique G.-G.
         </p>
      </div >
            <div style="position: fixed; bottom: 40px; left: 20px;">
                <a href="https://cgodin.omnivox.ca">
                    <img src="omnivoxLogo.png" width="50" height="50">
                </a>
            </div>
          <div style="position: fixed; bottom: 40px; left: 80px;">
              <a href="https://mail.google.com">
                  <img src="emailLogo.png" width="50" height="50">
              </a>
          </div>
      <?php
      if(isset($_SESSION["connectee"]) ) {
          if ($_SESSION["connectee"]) {
              ?>
              <div style="position: fixed; bottom: 100px;right=0;">
                  <button class="sButton" onclick="deconnecter()">Deconnexion</button>
              </div>
              <script>
                  function deconnecter() {
                      <?php session_unset(); ?>
                  }
              </script>
              <?php
          }
      }
      ?>
</body>
</html>
