
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
$error_lastname = $error_firstname = $error_email = $error_city = $error_city_code = $error_adress = $error_sex = $error_password = $error_password_b = $error_birth_date = "";
if (isset($_POST["validate"]) && !empty($_POST["validate"])) {

  $con = $db->query(
    "SELECT *
    FROM User AS u"
  );
  $email_password = $con->fetchAll(PDO::FETCH_ASSOC);

  //required test
  function required($datas) {
    $error= "";
    if(empty($_POST[$datas])) {
      $error = "Champs requis";
    }
    return $error;
  }
  $error_lastname = required("lastname");
  $error_firstname = required("firstname");
  $error_email = required("email");
  $error_city = required("city");
  $error_city_code = required("city_code");
  $error_adress = required("adress");
  $error_sex = required("sex");
  $error_password = required("password");
  $error_password_b = required("password_b");
  $error_birth_date = required("birth_date");

  foreach ($email_password as $key => $value) {
    if ($_POST["email"] === $value["email"]) {
      $wrong_email = "Choisissez un autre email car déja utilisé";
    }
    if ($_POST["password"] === $value["password"]) {
      $wrong_password = "Choisissez un autre mot de passe car déjà utilisé";
    }
  }

  if ($_POST["password"] === $_POST["password_b"] && !$wrong_email && !$wrong_password && !required()) {
    $query = $db->prepare(
      "INSERT INTO User (lastname, firstname, email, city, city_code, adress, sex, password, birth_date)
      VALUES (:lastname, :firstname, :email, :city, :city_code, :adress, :sex, :password, :birth_date)"
    );
    $result = $query->execute([
      "lastname" => test_input($_POST["lastname"]),
      "firstname" => test_input($_POST["firstname"]),
      "email" => test_input($_POST["email"]),
      "city" => test_input($_POST["city"]),
      "city_code" => test_input($_POST["city_code"]),
      "adress" => test_input($_POST["adress"]),
      "sex" => test_input($_POST["sex"]),
      "password" => password_hash(test_input($_POST["password"]), PASSWORD_BCRYPT),
      "birth_date" => test_input($_POST["birth_date"])
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
            <small>
              <?php echo $error_lastname ; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" class="form-control" id="firstname" name="firstname">
            <small>
              <?php echo $error_firstname; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="email">E-mail :</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
            <small>
              <?php
                echo "$wrong_email<br>";
                echo $error_email;
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city">Ville :</label>
            <input type="text" class="form-control" id="city" name="city">
            <small>
              <?php echo $error_city; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city_code">Code Postale :</label>
            <input type="number" class="form-control" id="city_code" name="city_code">
            <small>
              <?php echo $error_city_code; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="adress">Adresse :</label>
            <textarea class="form-control" id="adress" name="adress" rows="3"></textarea>
            <small>
              <?php echo $error_adress; ?>
            </small>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sex" id="sex" value="h">
              <label class="form-check-label" for="sex">Homme</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sex" id="sex" value="f">
              <label class="form-check-label" for="sex">Femme</label>
            </div>
            <small>
              <?php echo $error_sex; ?>
            </small>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="text" class="form-control" id="password" name="password">
            <small>
              <?php
                echo "$wrong_password<br>";
                echo $error_password;
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="password_b">Confirmer mot de passe :</label>
            <input type="text" class="form-control" id="password_b" name="password_b">
            <small>
              <?php
                echo "$not_the_same_password<br>";
                echo "$error_password_b";
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="birth_date">Date de naissance :</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date">
            <small>
              <?php echo $error_birth_date; ?>
            </small>
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
