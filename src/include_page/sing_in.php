<form action="./src/include_page/data.php" method="post" class="sing-in" style="display: block;">
  <h1>LOGIN TO YOUR ACCOUNT</h1>
  <fieldset>
    <label for="email-log"><i class="bi bi-envelope"></i></label>
    <input type="email" value="<?= $emaillog?>" name="emaillog" id="email-log" placeholder="Email">
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  
  <fieldset class="password">
    <label for="password-log"><i class="bi bi-lock-fill"></i></label>
    <input type="password" value="<?= $passwordlog?>" name="passwordlog" id="password-log" placeholder="Password">
    <span><i class="bi bi-x"></i></span>
    <span><i class="bi bi-eye-fill"></i></span>
  </fieldset>

  <input type="submit" value="LOGIN NOW">

  <ul class="tools">
    <li>Forget Password?</li>
    <li class="help">help</li>
    <li class="change-mode">Sing Up</li>
  </ul>
</form>