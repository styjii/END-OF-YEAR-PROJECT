<?php session_start();
if (isset($_SESSION['email'])) {
  header("Location: ./src/page/home.php");
  exit();
}


$emaillog = $passwordlog = '';
$newusername = $newpassword = $confirmnewpassword = '';
$newemail = $newfullname = $newphonenumber = '';
$newlocalisation = $emailto = $message = '';

if (array_key_exists("inputs", $_SESSION)) {
  if (array_key_exists('emaillog', $_SESSION["inputs"])) {
    $emaillog = $_SESSION["inputs"]["emaillog"];
    $passwordlog = $_SESSION["inputs"]["passwordlog"];
  }

  if (array_key_exists('newusername', $_SESSION["inputs"])) {
    $newusername = $_SESSION["inputs"]["newusername"];
    $newpassword = $_SESSION["inputs"]["newpassword"];
    $confirmnewpassword = $_SESSION["inputs"]["confirmnewpassword"];
    $newemail = $_SESSION["inputs"]["newemail"];
    $newfullname = $_SESSION["inputs"]["newfullname"];
    $newphonenumber = $_SESSION["inputs"]["newphonenumber"];
    $newlocalisation = $_SESSION["inputs"]["newlocalisation"];
  }

  if (array_key_exists('emailto', $_SESSION["inputs"])) {
    $emailto = $_SESSION["inputs"]["emailto"];
    $message = $_SESSION["inputs"]["helpmeassage"];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PROJECT PHP 2025</title>

  <!-- custom css -->
  <link rel="stylesheet" href="icons/bootstrap-icons.css">
  <link rel="stylesheet" href="css/style.css">
  
  <!-- project -->
  <script src="js/script.js"></script>
</head>

<body>
  <section id="errors">
    <div class="container">
      <?php
      if (isset($_SESSION["errors"])) {
        foreach ($_SESSION["errors"] as $error) {
          echo "<p>" . $error . "</p>";
        }
      }
      if (isset($_SESSION["success"])) {
        echo "<p>" . $_SESSION["success"] . "</p>";
      }
      ?>
    </div>
  </section>

  <main>
    <?php include_once "./src/include_page/help.php"?>

    <section id="login">
      <?php
      include_once "./src/include_page/login.php";
      include_once "./src/include_page/register.php";
      ?>
    </section>
  </main>
  
  <!-- jquery -->
  <script src="js/jquery.js"></script>
  <script>
    function inputValidation(element, pattern) {
      if (pattern.test(element.val())) {
        element.next().children().attr("class", "bi bi-check")
        element.next().children().css("color", "#198754")
      } else {
        element.next().children().attr("class", "bi bi-x")
        element.next().children().css("color", "#dc3545")
      }
    }
    function confirmPasswordValidation(password, confirmPassword) {
      if (password === confirmPassword.val() && confirmPassword.val() !== ''){
        confirmPassword.next().children().attr("class", "bi bi-check")
        confirmPassword.next().children().css("color", "#198754")
      } else {
        confirmPassword.next().children().attr("class", "bi bi-x")
        confirmPassword.next().children().css("color", "#dc3545")
      }
    }

    $(document).ready(function(){
      // ------------------------ input ---------------------------
      const email = [$('#email-to'), $('#email-log'), $('#new-email')]
      const password = [$('#password-log'), $('#new-password')]
      let newpassword = ($('#new-password').val())? $('#new-password').val() : ''

      email.forEach(element => {
        element.on("input", function(){
          inputValidation($(this), /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/)
        })
        inputValidation($(element), /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/)
      })
      password.forEach(element => {
        element.on("input", function(){
          inputValidation($(this), /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)

          if ($(this).attr('id') === $('#new-password').attr('id')){
            newpassword = $(this).val()
          }
        })
        inputValidation($(element), /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)
      })
      $('#confirm-password').on("input", function(){
        confirmPasswordValidation(newpassword, $(this))
      })
      confirmPasswordValidation(newpassword, $('#confirm-password'))
      inputValidation($('#new-username'), /^[a-zA-Z][a-zA-Z0-9_]{2,15}$/)
      inputValidation($('#new-fullname'), /^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/)
      inputValidation($('#new-phonenumber'), /^\+?[1-9][0-9]{7,14}$/)
      inputValidation($('#new-localisation'), /[a-zA-Z,\s]{3,100}$/)
      

      $('#new-username').on("input", function(){
        inputValidation($(this), /^[a-zA-Z][a-zA-Z0-9_]{2,15}$/)
      })
      $('#new-fullname').on("input", function(){
        inputValidation($(this), /^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/)
      })
      $('#new-phonenumber').on("input", function(){
        inputValidation($(this), /^\+?[1-9][0-9]{7,14}$/)
      })
      $('#new-localisation').on("input", function(){
        inputValidation($(this), /[a-zA-Z,\s]{3,100}/)
      })

      // --------------------- element --------------------------
      $('.help').on("click", function(){
        $('#help').animate({top: '25px'}, 1100)

        setTimeout(function(){
          if ($('#help').css("position") === "absolute") {
            $('body').attr("class", "shadow")
          }
        }, 1000)
      })  
      $('.close').on("click", function(){
        $(this).parent().animate({top: '-200%'}, 1000)
        setTimeout(function(){
          if ($('#help').css("position") === "absolute") {
            $('body').attr("class", "light")
          }
        }, 500)
      })
      $('.change-mode').on("click", function(){
        if ($('.sing-in').attr("style") === "display: block;"){
          $('.sing-in').hide(500)
          $('.sing-up').show(500)
          $('#help .mode').text("login")
        } else {
          $('.sing-in').show(500)
          $('.sing-up').hide(500)
          $('#help .mode').text("create an account")
        }
      })
      
      // show the error
      if (/[a-zA-Z]/.test($('#errors .container').text())) {
        $('#errors').show(2000)
                    .animate({padding: "20px"}, 500)
        setTimeout(function(){
          $('body').css("background", "#d4d4d4")
        }, 0)
      } else {
        $('#errors').hide()
                    .animate({padding: "0px"}, 200)
        setTimeout(function(){
          $('body').css("background", "#f8f9fa")
        }, 0)
      }

      // tap to the window and the error hidden
      $('body').on("click", function(){
        $('#errors').hide(500)
                    .animate({padding: "0px"}, 200)
        setTimeout(function(){
          $('body').css("background", "#f8f9fa")
        }, 400)
      })
    })
  </script>
</body>

</html>
<?php
unset($_SESSION["inputs"]);
unset($_SESSION["errors"]);
unset($_SESSION["success"]);
?>