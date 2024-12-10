<?php
session_start();

// Check if the user is logged in, if
// not then redirect them to the login page
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME PAGE</title>

  <!-- custom css -->
  <link rel="stylesheet" href="../../css/home_style.css">
</head>
<body>
  <main>
    <section id="debug">
      <div class="container">
        <?php 
        if (isset($_SESSION["serial"])) {
            echo "<p class='serial'>" . 
                    "Your serial is :" . 
                    $_SESSION["serial"] . 
                  "</p>";
        }
        ?>
      </div>
      <div class="loading"></div>
    </section>
    
    <section id="logout">
      <a href="../include_page/logout.php" type="submit">LOGOUT</a>
      <h2>Welcome to online sale</h2>
    </section>
  </main>

  <!-- jquery -->
  <script src="../../js/jquery.js"></script>
  <script>
    $("document").ready(function(){
      // show the debug if it containts texts
      if (/[a-zA-Z]/.test($('#debug .container').text())) {
        $('#debug .loading').css("height", "10px")
        // show debug
        $('#debug').show(2000)
        $('#debug .container').animate({padding: "20px"}, 500)
        $('#debug .loading').delay(500)
                            .animate({width: "100%"}, 20000)
        setTimeout(function(){
          $('body').css("background", "#d4d4d4")
        }, 0)
      } else {
        // hide debug
        $('#debug').hide()
        $('#debug .container').animate({padding: "0px"}, 200)
      }

      // hidden after 20s
      setTimeout(function() {
        $('#debug .container').animate({padding: "0px"}, 500)
        $('#debug').delay(500)
                   .hide(100)
        setTimeout(function(){
          $('body').css("background", "#f8f9fa")
        }, 400)
      }, 20000)
    })
  </script>
</body>
</html>

<?php
unset($_SESSION["serial"]);
?>