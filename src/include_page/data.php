<?php
function try_input(string $input): string {
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input);

  return $input;
}

function emailValidation(string &$email, array &$errors): void {
  if (empty($email)) {
    $errors["email"] = "email is required !";
  } else {
    $email = try_input($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "invalid email format";
    }
  }
}
function passwordValidation(string &$password, array &$errors): void {
  if (empty($password)) {
    $errors["password"] = "password is required !";
  } else {
    $password = try_input($password);
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";

    if (!preg_match($pattern, $password)) {
      $errors["password"] = "Password minimul length should be 8. <br>" .
                            "at least one uppercase letter, <br>" . 
                            "at least one lowercase letter, <br>" .
                            "and one digit";
    }
  }
}
function confirmPasswordValidation(string $password, string &$confirmpassword, array &$errors): void {
  if (empty($confirmpassword)) {
    $errors['confirmpassword'] = "confirm password required !";
  } else {
    $confirmpassword = try_input($confirmpassword);
    if ($confirmpassword !== $password) {
      $errors['confirmpassword'] = "password no confirmed !";
    }
  }
}
function otherInputValidation(string &$input, string $inputName, array &$errors, $pattern): void {
  if (empty($input)) {
    $errors[$inputName] = "$inputName is required !";
  } else {
    $input = try_input($input);
    if (!preg_match($pattern, $input)) {
      $errors[$inputName] = "invalid $inputName format";
    }
  }
}
function textareaValidation(&$textarea, &$errors) {
  if (empty($textarea)) {
    $errors['message'] = "message is required !";
  } else {
    $textarea = try_input($textarea);
  }
}


// to avoid anyone who wants to access directly
if (empty($_POST)) {
  header("Location: ../../index.php");
}

// if the user sumbits form information
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $errors = [];
  
  // ----------------- sing-in form -----------------
  if (array_key_exists('emaillog', $_POST)){
    $emaillog = $_POST["emaillog"];
    $passwordlog = $_POST["passwordlog"];

    emailValidation($emaillog, $errors);
    passwordValidation($passwordlog, $errors);
  }

  // ----------------- sing-in form -----------------
  if (array_key_exists('newusername', $_POST)) {
    $newusername = $_POST["newusername"];
    $newpassword = $_POST["newpassword"];
    $confirmnewpassword = $_POST["confirmnewpassword"];
    $newemail = $_POST["newemail"];
    $newfullname = $_POST["newfullname"];
    $newphonenumber = $_POST["newphonenumber"];
    $newlocalisation = $_POST["newlocalisation"];

    otherInputValidation($newusername, 'username', $errors, "/^[a-zA-Z][a-zA-Z0-9_]{2,15}$/");
    passwordValidation($newpassword, $errors);
    confirmPasswordValidation($newpassword, $confirmnewpassword, $errors);
    emailValidation($newemail, $errors);
    otherInputValidation($newfullname, 'fullname', $errors, "/^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/");
    otherInputValidation($newphonenumber, 'phone number', $errors, "/^\+?[1-9][0-9]{7,14}$/");
    otherInputValidation($newlocalisation, 'localisation', $errors, "/[a-zA-Z,\s]/");
  }

  // ----------------- information form -----------------
  if (array_key_exists('emailto', $_POST)) {
    $emailto = $_POST["emailto"];
    $message = $_POST["helpmeassage"];

    emailValidation($emailto, $errors);
    textareaValidation($message, $errors);
  }


  // ----------------------------------------------------
  if (!empty($errors)) {
    session_start();
    $_SESSION["errors"] = $errors;
    $_SESSION["inputs"] = $_POST;

    header("Location: ../../index.php");
  } else {
    echo "Information validing !";
  }
}
?>