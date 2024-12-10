<?php
session_start();
include './database/db_connect.php';


// ----------------- input validation -----------------
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
function usernameValidaton(string &$input, array &$errors): void {
  if (empty($input)) {
    $errors["username"] = "username is required !";
  } else {
    $input = try_input($input);
    $pattern =  "/^[a-zA-Z][a-zA-Z0-9_]{2,15}$/";
    if (!preg_match($pattern, $input)) {
      $errors["username"] = "invalid username format";
    }
  }
}
function fullnameValidaton(string &$input, array &$errors): void {
  if (empty($input)) {
    $errors["fullname"] = "fullname is required !";
  } else {
    $input = try_input($input);
    $pattern = "/^[A-Z][a-zA-Z,',-]{2,50}\s[a-zA-Z,',-]{2,50}$/";
    if (!preg_match($pattern, $input)) {
      $errors["fullname"] = "invalid fullname format";
    }
  }
}
function phonenumberValidaton(string &$input, array &$errors): void {
  if (empty($input)) {
    $errors["phonenumber"] = "phone number is required !";
  } else {
    $input = try_input($input);
    $pattern = "/^\+?[1-9][0-9]{7,14}$/";
    if (!preg_match($pattern, $input)) {
      $errors["phonenumber"] = "invalid phone number format";
    }
  }
}
function localisationValidaton(string &$input, array &$errors): void {
  if (empty($input)) {
    $errors["phonenumber"] = "phone number is required !";
  } else {
    $input = try_input($input);
    $pattern = "/[a-zA-Z,\s]/";
    if (!preg_match($pattern, $input)) {
      $errors["phonenumber"] = "invalid phone number format";
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
  
  // ----------------- login -----------------
  if (array_key_exists('emaillog', $_POST)){
    $emaillog = $_POST["emaillog"];
    $passwordlog = $_POST["passwordlog"];

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

          if ($passwordlog === $db_password) {
            $_SESSION["email"] = $emaillog;
          } else {
            $errors["invalid_password"] = "Incorrect password";
          }
      } else {
          $errors["no_exists_email"] = "Email not found";
      }

      $stmt->close();
    }

  }

  // ----------------- register -----------------
  if (array_key_exists('newusername', $_POST)) {
    $newusername = $_POST["newusername"];
    $newpassword = $_POST["newpassword"];
    $confirmnewpassword = $_POST["confirmnewpassword"];
    $newemail = $_POST["newemail"];
    $newfullname = $_POST["newfullname"];
    $newphonenumber = $_POST["newphonenumber"];
    $newlocalisation = $_POST["newlocalisation"];

    usernameValidaton($newusername, $errors);
    passwordValidation($newpassword, $errors);
    confirmPasswordValidation($newpassword, $confirmnewpassword, $errors);
    emailValidation($newemail, $errors);
    fullnameValidaton($newfullname, $errors);
    phonenumberValidaton($newphonenumber, $errors);
    localisationValidaton($newlocalisation, $errors);

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

          if ($stmt->execute()) {
            $_SESSION['email'] = $newemail;
          } else {
            $errors["error"] = "Error: " . $stmt->error;
          }

          $stmt->close();
          $checkEmail->close();
          $checkUsername->close();
      }
    }
  }

  // ----------------- help -----------------
  if (array_key_exists('emailto', $_POST)) {
    $emailto = $_POST["emailto"];
    $message = $_POST["helpmeassage"];

    emailValidation($emailto, $errors);
    textareaValidation($message, $errors);


    if (empty($errors)) {
      $_SESSION["success"] = "Message sent";

      header("location: ../../index.php");
    }
  }


  // ----------------- reset password -----------------
  if (array_key_exists('yourusername', $_POST)) {
    $yourusername = $_POST["yourusername"];
    $youremail = $_POST["youremail"];
    $yourpassword = $_POST["yourpassword"];
    $confirmyourpassword = $_POST["confirmyourpassword"];

    usernameValidaton($yourusername, $errors);
    emailValidation($youremail, $errors);
    passwordValidation($yourpassword, $errors);
    confirmPasswordValidation($yourpassword, $confirmyourpassword, $errors);

    if (empty($errors)) {
      $_SESSION["success"] = "Password changed";

      header("location: ../../index.php");
    }
  }


  // ----------------------------------------------------
  if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    $_SESSION["inputs"] = $_POST;

    header("Location: ../../index.php");
  } else {
    header("Location: ./../page/home.php");
    exit();
  }

  $conn->close();
}
?>