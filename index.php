<?php session_start();
if (isset($_SESSION['email'])) {
  header("Location: ./src/page/home.php");
  exit();
}


$emaillog = $passwordlog = '';
$newusername = $newpassword = $confirmnewpassword = '';
$newemail = $newfullname = $newphonenumber = '';
$newlocalisation = $emailto = $message = '';
$yourusername = $youremail = $yourpassword = $confirmyourpassword = '';

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

  if (array_key_exists('yourusername', $_SESSION["inputs"])) {
    $yourusername = $_SESSION["inputs"]["yourusername"];
    $youremail = $_SESSION["inputs"]["youremail"];
    $yourpassword = $_SESSION["inputs"]["yourpassword"];
    $confirmyourpassword = $_SESSION["inputs"]["confirmyourpassword"];
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
          echo "<p class='errors'>" . $error . "</p>";
        }
      }
      if (isset($_SESSION["success"])) {
        echo "<p class='success'>" . $_SESSION["success"] . "</p>";
      }
      ?>
    </div>
  </section>

  <main>
    <?php include_once "./src/include_page/help.php"?>

    <section id="login">
      <?php
      include_once "./src/include_page/resetpassword.php";
      include_once "./src/include_page/login.php";
      include_once "./src/include_page/register.php";
      ?>
    </section>
  </main>
  
  <!-- jquery -->
  <script src="js/jquery.js"></script>
  <script>
    // all inputs validation
    function inputValidation(element, pattern) {
      if (pattern.test(element.val())) {
        element.next().children().attr("class", "bi bi-check")
        element.next().children().css("color", "#198754")
      } else {
        element.next().children().attr("class", "bi bi-x")
        element.next().children().css("color", "#dc3545")
      }
    }
    function emailValidation(element) {
      const pattern = /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/
      inputValidation(element, pattern)
    }
    function passwordValidation(element) {
      const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
      inputValidation(element, pattern)
    }
    function usernameValidation(element) {
      const pattern = /^[a-zA-Z][a-zA-Z0-9_]{2,15}$/
      inputValidation(element, pattern)
    }
    function fullnameValidation(element) {
      const pattern = /^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/
      inputValidation(element, pattern)
    }
    function phonenumberValidation(element) {
      const pattern = /^\+?[1-9][0-9]{7,14}$/
      inputValidation(element, pattern)
    }
    function localisationValidation(element) {
      const pattern = /[a-zA-Z,\s]{3,100}$/
      inputValidation(element, pattern)
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
      const email = [$('#email-to'), $('#email-log'), $('#new-email'), $('#your-email')]
      const password = [$('#password-log'), $('#new-password'), $('#your-password')]
      
      let passwrd = ''
      if ($('#new-password').val() !== '') {
        passwrd = $('#new-password').val()
      } else if ($('#your-password').val() !== '') {
        passwrd = $('#your-password').val()
      }

      email.forEach(element => {
        // email validation when type a text
        element.on("input", function(){
          emailValidation($(this))
        })
        // email validation when the page is loading
        emailValidation(element)
      })
      password.forEach(element => {
        // password validation when type a text
        element.on("input", function(){
          passwordValidation($(this))
          
          if ($(this).attr('id') === 'new-password' | 'your-password'){
            passwrd = $(this).val()
          }
        })
        // password validation when the page is loading
        passwordValidation(element)
      })

      // password confirmaiton validatoin when the page is loading
      confirmPasswordValidation(passwrd, $('#confirm-password'))
      confirmPasswordValidation(passwrd, $('#confirm-your-password'))
      // password confirmaiton validatoin when type a text
      $('#confirm-password').on("input", function(){
        confirmPasswordValidation(passwrd, $(this))
      })
      $('#confirm-your-password').on("input", function(){
        confirmPasswordValidation(passwrd, $(this))
      })

      // other input when the page is loading
      usernameValidation($('#your-username'))
      usernameValidation($('#new-username'))
      fullnameValidation($('#new-fullname'))
      phonenumberValidation($('#new-phonenumber'))
      localisationValidation($('#new-localisation'))
      
      // other input validatoin when type a text
      $('#new-username').on("input", function(){
        usernameValidation($(this))
      })
      $('#your-username').on("input", function(){
        usernameValidation($(this))
      })
      $('#new-fullname').on("input", function(){
        fullnameValidation($(this))
      })
      $('#new-phonenumber').on("input", function(){
        phonenumberValidation($(this))
      })
      $('#new-localisation').on("input", function(){
        localisationValidation($(this))
      })

      // --------------------- element --------------------------
      // When .help is targeted, #help is in the middle
      $('.help').on("click", function(){
        $('#help').animate({top: '25px'}, 1100)
        
        setTimeout(function(){
          if ($('#help').css("position") === "absolute") {
            $('body').attr("class", "shadow")
          }
        }, 1000)
      })

      // When .resetpassword is targeted, #reset-password is in the middle
      $('.resetpassword').on("click", function(){
        $('#reset-password').animate({top: '50%'}, 1100)
        
        setTimeout(function(){
          if (parseInt($('#reset-password').css("top")) < 0) {
            $('body').attr("class", "shadow")
          }
        }, 1000)
      })

      // When x is targeted, his parent is hidden
      $('.close').on("click", function(){
        $(this).parent().animate({top: '-200%'}, 1000)
        setTimeout(function(){
          if ($('#help').css("position") === "absolute") {
            $('body').attr("class", "light")
          }
          if (parseInt($('#reset-password').css("top")) < 0) {
            $('body').attr("class", "light")
          }
        }, 500)
      })

      // change login or register
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
      
      // show the error if it containts texts
      if (/[a-zA-Z]/.test($('#errors .container').text())) {
        // custom background of error
        if ($('#errors p').attr("class") === 'success') {
          $('#errors').css("background", "#04AA6D")
        } else {
          $('#errors').css("background", "#dc3545")
        }
        // show error
        $('#errors').show(2000)
                    .animate({padding: "20px"}, 500)
        setTimeout(function(){
          $('body').css("background", "#d4d4d4")
        }, 0)
      } else {
        // hide error
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