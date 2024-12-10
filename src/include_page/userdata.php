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
function serialValidation(string &$input, array &$errors) {
  if (empty($input)) {
    $errors["serialNumber"] = "Serial number is required !";
  } else {
    $input = try_input($input);
    $pattern = "/[0-9]{5}/";
    if (!preg_match($pattern, $input)) {
      $errors["serialNumber"] = "invalid serial number format";
    }
  }
}

// if the user sumbits form information
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $errors = [];
  
  // ----------------- login -----------------
  if (array_key_exists('emaillog', $_POST)){
    $email = $_POST["emaillog"];
    $password = $_POST["passwordlog"];

    emailValidation($email, $errors);
    passwordValidation($password, $errors);

    if(empty($errors)) {
      // Prepare and execute
      $stmt = $conn->prepare("SELECT password FROM userdata WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
          $stmt->bind_result($db_password);
          $stmt->fetch();

          if ($password === $db_password) {
            $_SESSION["email"] = $email;
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
    $username = $_POST["newusername"];
    $password = $_POST["newpassword"];
    $confirmpassword = $_POST["confirmnewpassword"];
    $email = $_POST["newemail"];
    $fullname = $_POST["newfullname"];
    $phonenumber = $_POST["newphonenumber"];
    $localisation = $_POST["newlocalisation"];

    usernameValidaton($username, $errors);
    passwordValidation($password, $errors);
    confirmPasswordValidation($password, $confirmpassword, $errors);
    emailValidation($email, $errors);
    fullnameValidaton($fullname, $errors);
    phonenumberValidaton($phonenumber, $errors);
    localisationValidaton($localisation, $errors);


    if(empty($errors)) {
      // create serialnumber
      do{
        $serial = rand(0, 99999);
        $serial = (string) sprintf("%05d", $serial);

        // verify the serial if not in the tadabase
        $checkSerial = $conn->prepare("SELECT serial FROM userdata WHERE serial = ?");
        $checkSerial->bind_param("s", $serial);
        $checkSerial->execute();
        $checkSerial->store_result();
      } while ($checkSerial->num_rows > 0);

      // Check if email already exists
      $checkEmail = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
      $checkEmail->bind_param("s", $email);
      $checkEmail->execute();
      $checkEmail->store_result();

      // check if username already exists
      $checkUsername = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
      $checkUsername->bind_param("s", $username);
      $checkUsername->execute();
      $checkUsername->store_result();

      if ($checkEmail->num_rows > 0) {
        $errors["exist_email"] = "Email ID already exists";
      } else if ($checkUsername->num_rows > 0) {
        $errors['exist_user'] = "Username ID already exists";
      } else {
          // Prepare and bind
          $stmt = $conn->prepare("INSERT INTO userdata (username, password, email, fullname, phonenumber, localisation, serial) VALUES (?, ?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("sssssss", $username, $password, $email, $fullname, $phonenumber, $localisation, $serial);

          if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION["serial"] = $serial;
          } else {
            $errors["error"] = "Error: " . $stmt->error;
          }

          $stmt->close();
          $checkEmail->close();
          $checkUsername->close();
          $checkSerial->close();
      }
    }
  }

  // ----------------- help -----------------
  if (array_key_exists('emailto', $_POST)) {
    $email = $_POST["emailto"];
    $message = $_POST["helpmeassage"];

    emailValidation($email, $errors);
    textareaValidation($message, $errors);


    if (empty($errors)) {
      $_SESSION["success"] = "Message sent";
    }
  }


  // ----------------- reset password -----------------
  if (array_key_exists('serial', $_POST)) {
    $serial = $_POST["serial"];
    $email = $_POST["youremail"];
    $password = $_POST["yourpassword"];
    $confirmpassword = $_POST["confirmyourpassword"];

    serialValidation($serial, $errors);
    emailValidation($email, $errors);
    passwordValidation($password, $errors);
    confirmPasswordValidation($password, $confirmpassword, $errors);

    
    if(empty($errors)) {
      // Check if email already exists
      $checkEmail = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
      $checkEmail->bind_param("s", $email);
      $checkEmail->execute();
      $checkEmail->store_result();

      // check if serial already exists
      $checkSerial = $conn->prepare("SELECT serial FROM userdata WHERE serial = ?");
      $checkSerial->bind_param("s", $serial);
      $checkSerial->execute();
      $checkSerial->store_result();

      // if the account exists in the database
      if ($checkEmail->num_rows > 0 && ($checkSerial->num_rows > 0)) {
        // Prepare and execute
        $stmt = $conn->prepare("UPDATE userdata SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $password, $email);

        if ($stmt->execute()) {
          $_SESSION["success"] = "Password updated successfully";
        } else {
          $_SESSION["errors"] = "Error updating password";
        }

        $stmt->close();
        $checkEmail->close();
        $checkSerial->close();
      } else {
        if (!($checkSerial->num_rows > 0)) {
          $errors["no_exist"] = "Your Serial does not exists in the database";
        } else {
          $errors["no_exist"] = "Your account does not exists in the database";
        }
      }
    }
  }


  // ------------------- END OF SCRIPT --------------------------
  if (!empty($errors) || !empty($_SESSION["success"])) {
    if (!empty($errors)) {
      $_SESSION["errors"] = $errors;
      $_SESSION["inputs"] = $_POST;
    }
    
    header("Location: ../../index.php");
  } else {
    header("Location: ./../page/home.php");
    exit();
  }

  $conn->close();
}
?>