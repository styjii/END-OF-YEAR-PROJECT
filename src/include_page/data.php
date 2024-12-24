<?php
session_start();
include './db_connect.php';

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

// if the user sumbits form information
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $errors = [];
  $email = '';
  
  // ----------------- sing-in form -----------------
  if (array_key_exists('emaillog', $_POST)){
    $emaillog = $_POST["emaillog"];
    $passwordlog = $_POST["passwordlog"];
    $email = $emaillog;

    emailValidation($emaillog, $errors);
    passwordValidation($passwordlog, $errors);

    if(empty($errors)) {
      // Prepare and execute
      $stmt = $conn->prepare("SELECT password FROM userdata WHERE email = ?");
      $stmt->bind_param("s", $emaillog);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
          $stmt->bind_result($db_password);
          $stmt->fetch();

          if (!($passwordlog === $db_password)) {
              $errors["invalid_password"] = "Incorrect password";
          }
      } else {
          $errors["no_exists_email"] = "Email not found";
      }
    }

  }

  // ----------------- sing-up form -----------------
  if (array_key_exists('newusername', $_POST)) {
    $newusername = $_POST["newusername"];
    $newpassword = $_POST["newpassword"];
    $confirmnewpassword = $_POST["confirmnewpassword"];
    $newemail = $_POST["newemail"];
    $newfullname = $_POST["newfullname"];
    $newphonenumber = $_POST["newphonenumber"];
    $newlocalisation = $_POST["newlocalisation"];
    $email = $newemail;

    otherInputValidation($newusername, 'username', $errors, "/^[a-zA-Z][a-zA-Z0-9_]{2,15}$/");
    passwordValidation($newpassword, $errors);
    confirmPasswordValidation($newpassword, $confirmnewpassword, $errors);
    emailValidation($newemail, $errors);
    otherInputValidation($newfullname, 'fullname', $errors, "/^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/");
    otherInputValidation($newphonenumber, 'phone number', $errors, "/^\+?[1-9][0-9]{7,14}$/");
    otherInputValidation($newlocalisation, 'localisation', $errors, "/[a-zA-Z,\s]/");

    if(empty($errors)) {
      // Check if email already exists
      $checkEmail = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
      $checkEmail->bind_param("s", $newemail);
      $checkEmail->execute();
      $checkEmail->store_result();

      // check if username already exists
      $checkUsername = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
      $checkUsername->bind_param("s", $newusername);
      $checkUsername->execute();
      $checkUsername->store_result();

      if ($checkEmail->num_rows > 0) {
        $errors["exist_email"] = "Email ID already exists";
      } else if ($checkUsername->num_rows > 0) {
        $errors['exist_user'] = "Username ID already exists";
      } else {
          // Prepare and bind
          $stmt = $conn->prepare("INSERT INTO userdata (username, password, email, fullname, phonenumber, localisation) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("ssssss", $newusername, $newpassword, $newemail, $newfullname, $newphonenumber, $newlocalisation);

          if (!$stmt->execute()) {
              $errors["error"] = "Error: " . $stmt->error;
          }

          $checkEmail->close();
          $checkUsername->close();
      }
    }
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
    $_SESSION["errors"] = $errors;
    $_SESSION["inputs"] = $_POST;

    header("Location: ../../index.php");
  } else {
    $_SESSION['email'] = $email;
    header("Location: ./../page/home.php");
    exit();
  }

  $stmt->close();
  $conn->close();
}
?>