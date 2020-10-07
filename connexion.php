<?php
  include "template/nav.php";
  include "template/header.php";
?>
<?php
  try {
    $db = new PDO('mysql:host=localhost;dbname=banque_php', 'BanquePHP', 'banque76');
  } catch (\Exception $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
  }
  if(isset($_POST["connect"]) && !empty($_POST["connect"])) {
    $email = test_input($_POST["email"]);
    if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
      $query = $db->prepare(
        "SELECT * FROM User WHERE email= :email"
      );
      $query->execute([
        "email" => $_POST["email"]
      ]);
      $user_email= $query->fetch(PDO::FETCH_ASSOC);
      if ($user_email) {
        if (password_verify($_POST["password"], $user_email["password"])) {
          session_start();
          $_SESSION["user_email"] = $user_email;
          header("Location: index.php");
        }
      }
    }
  }
 ?>

<section class="container">
  <h3 class="text-center">Connexion</h3>
  <div class="row">
    <form class="col-10 offset-1" action="" method="POST">
      <div class="form-group">
        <label for="email">Adresse E-mail</label>
        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <button type="submit" class="btn btn-primary" name="connect" value="Valider">Valider</button>
    </form>
  </div>
</section>

<?php
  include "template/footer.php";
?>
