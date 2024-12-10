<section id="help">
  <div class="close"><i class="bi bi-x-circle"></i></div>
  <h1>Welcom to online sale</h1>
  <p>
    You can login by entering your identification otheverwise 
    <span class="change-mode">click here</span> to 
    <span class="mode">create an account</span>
  </p>

  <p>
    This website is specializing for people who wants to sell Laptop, 
    Android, Iphone, ... <br>
  </p>

  <p>
    If you have a problem when connecting, you can inform me directly 
    below or <a href="page/about.php">click here</a> to see the 
    about of page <br>
  </p>
  
  <form class="form-control" action="./src/include_page/userdata.php" method="post">
    <fieldset>
      <label for="email-to"><i class="bi bi-envelope"></i></label>
      <input type="email" value="<?= $emailto?>" name="emailto" id="email-to" placeholder="Email">
      <span><i class="bi bi-x"></i></span>
    </fieldset>

    <textarea name="helpmeassage" rows="5" name="text" placeholder="What's your problem ?"><?= $message?></textarea>
    <input type="submit" class="btn" value="SEND">
  </form>
</section>