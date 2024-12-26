<form
  action="./src/include_page/userdata.php"
  method="post"
  class="sing-up"
  style="display: none"
>
  <h1>CREATE ACCOUNT</h1>
  <fieldset>
    <label for="new-username"><i class="bi bi-person-fill"></i></label>
    <input
      type="text"
      value="<?= $newusername ?>"
      name="newusername"
      id="new-username"
      placeholder="Username"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset class="password">
    <label for="new-password"><i class="bi bi-lock-fill"></i></label>
    <input
      type="password"
      value="<?= $newpassword ?>"
      name="newpassword"
      id="new-password"
      placeholder="Password"
    />
    <span><i class="bi bi-x"></i></span>
    <span><i class="bi bi-eye-fill"></i></span>
  </fieldset>
  <fieldset class="password">
    <label for="confirm-password"><i class="bi bi-lock-fill"></i></label>
    <input
      type="password"
      value="<?= $confirmnewpassword ?>"
      name="confirmnewpassword"
      id="confirm-password"
      placeholder="Confirm Password"
    />
    <span><i class="bi bi-x"></i></span>
    <span><i class="bi bi-eye-fill"></i></span>
  </fieldset>
  <fieldset>
    <label for="new-email"><i class="bi bi-envelope"></i></label>
    <input
      type="email"
      value="<?= $newemail ?>"
      name="newemail"
      id="new-email"
      placeholder="Email"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset>
    <label for="new-fullname"><i class="bi bi-person-fill"></i></label>
    <input
      type="text"
      value="<?= $newfullname ?>"
      name="newfullname"
      id="new-fullname"
      placeholder="Fullname"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset>
    <label for="new-phonenumber"><i class="bi bi-telephone-fill"></i></label>
    <input
      type="text"
      value="<?= $newphonenumber ?>"
      name="newphonenumber"
      id="new-phonenumber"
      placeholder="Phone Number"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>
  <fieldset>
    <label for="new-localisation"><i class="bi bi-cursor-fill"></i></label>
    <input
      type="text"
      value="<?= $newlocalisation ?>"
      name="newlocalisation"
      id="new-localisation"
      placeholder="Localisation"
    />
    <span><i class="bi bi-x"></i></span>
  </fieldset>

  <input type="submit" value="CREATE ACCOUNT" />

  <ul class="tools">
    <li class="help">help</li>
    <li class="change-mode">Sing Up</li>
  </ul>
</form>
