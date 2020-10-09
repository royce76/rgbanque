
<?php

function test_input($data) {
    $data = trim($data); // remove space of both side
    $data = stripslashes($data);// remove backslashes
    $data = htmlspecialchars($data, ENT_QUOTES);//both quotes
    return $data;
}

try {
  $db = new PDO('mysql:host=localhost;dbname=banque_php', 'BanquePHP', 'banque76');
} catch (\Exception $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}
$result = FALSE;
$not_the_same_password = "";
$wrong_email = "";
$wrong_password = "";
if (isset($_POST["validate"]) && !empty($_POST["validate"])) {

  $con = $db->query(
    "SELECT u.email, u.password
    FROM User AS u"
  );
  $email_password = $con->fetchAll(PDO::FETCH_ASSOC);
  print_r($email_password);
  foreach ($email_password as $key => $value) {
    if ($_POST["email"] === $value["email"]) {
      $wrong_email = "Choisissez un autre email car déja utilisé";
    }
    if ($_POST["password_a"] === $value["password"]) {
      $wrong_password = "Choisissez un autre mot de passe car déjà utilisé";
    }
  }

  if ($_POST["password_a"] === $_POST["password_b"] || !$wrong_email || !$wrong_password) {
    $query = $db->prepare(
      "INSERT INTO User (lastname, firstname, email, city, city_code, adress, sex, password, birth_date)
      VALUES (:lastname, :firstname, :email, :city, :city_code, :adress, :genre, :password_a, :date_birth)"
    );
    $result = $query->execute([
      "lastname" => test_input($_POST["lastname"]),
      "firstname" => test_input($_POST["firstname"]),
      "email" => test_input($_POST["email"]),
      "city" => test_input($_POST["city"]),
      "city_code" => test_input($_POST["city_code"]),
      "adress" => test_input($_POST["adress"]),
      "genre" => test_input($_POST["genre"]),
      "password_a" => test_input($_POST["password_a"]),
      "date_birth" => test_input($_POST["date_birth"])
    ]);
  }
  else {
    $not_the_same_password = "saisie non correspondante";
  }
}

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title></title>
  </head>
  <body>
    <section class="container">
      <h3 class="text-center">Créer votre profil</h3>
      <div class="row">
        <form class="col-10 mx-auto" action="" method="POST">
          <div class="form-group">
            <label for="lastname">Nom :</label>
            <input type="text" class="form-control" id="lastname" name="lastname">
          </div>
          <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" class="form-control" id="firstname" name="firstname">
          </div>
          <div class="form-group">
            <label for="email">E-mail :</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
            <small>
              <?php echo $wrong_email; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city">Ville :</label>
            <input type="text" class="form-control" id="city" name="city">
          </div>
          <div class="form-group">
            <label for="city_code">Code Postale :</label>
            <input type="number" class="form-control" id="city_code" name="city_code">
          </div>
          <div class="form-group">
            <label for="adress">Adresse :</label>
            <textarea class="form-control" id="adress" name="adress" rows="3"></textarea>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="genre" id="genre" value="h">
              <label class="form-check-label" for="genre">Homme</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="genre" id="genre" value="f">
              <label class="form-check-label" for="genre">Femme</label>
            </div>
          </div>
          <div class="form-group">
            <label for="password_a">Mot de passe :</label>
            <input type="text" class="form-control" id="password_a" name="password_a">
            <small>
              <?php echo $wrong_password ?>
            </small>
          </div>
          <div class="form-group">
            <label for="password_b">Confirmer mot de passe :</label>
            <input type="text" class="form-control" id="password_b" name="password_b">
            <small><?php echo $not_the_same_password; ?></small>
          </div>
          <div class="form-group">
            <label for="date_birth">Date de naissance :</label>
            <input type="date" class="form-control" id="date_birth" name="date_birth">
          </div>
          <button type="submit" class="btn btn-primary" name="validate" value="valider">Valider</button>
        </form>
      </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>
