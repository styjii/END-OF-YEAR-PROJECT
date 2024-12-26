<form
  action="./src/include_page/userdata.php"
  method="post"
  id="reset-password"
>
  <div class="close"><i class="bi bi-x-circle"></i></div>
  <h1>RESSET PASSWORD</h1>
  <fieldset>
    <label for="serial-number"><i class="bi bi-person-fill"></i></label>
    <input
      type="text"
      value="<?= $serial ?>"
      name="serial"
      id="serial-number"
      placeholder="Serial Number(5)"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset>
    <label for="your-email"><i class="bi bi-envelope"></i></label>
    <input
      type="email"
      value="<?= $youremail ?>"
      name="youremail"
      id="your-email"
      placeholder="Email"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset class="password">
    <label for="your-password"><i class="bi bi-lock-fill"></i></label>
    <input
      type="password"
      value="<?= $yourpassword ?>"
      name="yourpassword"
      id="your-password"
      placeholder="Password"
    />
    <span><i class="bi bi-x"></i></span>
    <span><i class="bi bi-eye-fill"></i></span>
  </fieldset>
  <fieldset class="password">
    <label for="confirm-your-password"><i class="bi bi-lock-fill"></i></label>
    <input
      type="password"
      value="<?= $confirmyourpassword ?>"
      name="confirmyourpassword"
      id="confirm-your-password"
      placeholder="Confirm Password"
    />
    <span><i class="bi bi-x"></i></span>
    <span><i class="bi bi-eye-fill"></i></span>
  </fieldset>

  <input type="submit" value="RESET PASSWORD" />
</form>
