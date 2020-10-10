
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
$error = "Champs requis";
$test = 0;

if (isset($_POST["validate"]) && !empty($_POST["validate"])) {

  $con = $db->query(
    "SELECT u.lastname, u.firstname, u.email, u.city, u.city_code, u.adress, u.sex, u.password, u.birth_date
    FROM User AS u"
  );
  $array_user = $con->fetchAll(PDO::FETCH_ASSOC);

  if (test_input($_POST["password"]) !== test_input($_POST["password_b"])) {
      $not_the_same_password = "saisie non correspondante";
  }

  foreach ($array_user as $key => $value) {
    if (test_input($_POST["email"]) === $value["email"]) {
      $wrong_email = "Choisissez un autre email car déja utilisé";
    }
    if (password_verify(test_input($_POST["password"]),$value["password"])) {
      $wrong_password = "Choisissez un autre mot de passe car déjà utilisé";
    }
    // if (!empty($POST[$key])) {
    //   $test = 1;
    // }
  }
  if (
    test_input($_POST["password"]) === test_input($_POST["password_b"])
    && !$wrong_email
    && !$wrong_password
    // && $test === 1
    && !empty($_POST["lastname"])
    && !empty($_POST["firstname"])
    && !empty($_POST["email"])
    && !empty($_POST["city"])
    && !empty($_POST["city_code"])
    && !empty($_POST["adress"])
    && !empty($_POST["sex"])
    && !empty($_POST["password"])
    && !empty($_POST["password_b"])
    && !empty($_POST["birth_date"])
  ) {
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
      "sex" => $_POST["sex"],
      "password" => password_hash(test_input($_POST["password"]), PASSWORD_BCRYPT),
      "birth_date" => test_input($_POST["birth_date"])
    ]);
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
            <input type="text" class="form-control" id="lastname" name="lastname" value=<?php if(!empty($_POST["lastname"])){ echo $_POST["lastname"];}?>>
            <small>
              <?php
                if (empty($_POST["lastname"]) && isset($_POST["validate"])) {
                  echo "$error" ;
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" class="form-control" id="firstname" name="firstname" value=<?php if(!empty($_POST["firstname"])){ echo $_POST["firstname"];}?>>
            <small>
              <?php
                if (empty($_POST["firstname"]) && isset($_POST["validate"])) {
                  echo $error;
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="email">E-mail :</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value=<?php if(!empty($_POST["email"])){ echo $_POST["email"];}?>>
            <small>
              <?php
                if (empty($_POST["email"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                echo $wrong_email;
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city">Ville :</label>
            <input type="text" class="form-control" id="city" name="city" value=<?php if(!empty($_POST["city"])){ echo $_POST["city"];}?>>
            <small>
              <?php
                if (empty($_POST["city"]) && isset($_POST["validate"])) {
                  echo $error;
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city_code">Code Postale :</label>
            <input type="number" class="form-control" id="city_code" name="city_code" value=<?php if(!empty($_POST["city_code"])){ echo $_POST["city_code"];}?>>
            <small>
              <?php
                if (empty($_POST["city_code"]) && isset($_POST["validate"])) {
                  echo $error;
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="adress">Adresse :</label>
            <input type="text" class="form-control" id="adress" name="adress" value=<?php if(!empty($_POST["adress"])){ echo $_POST["adress"];}?>>
            <small>
              <?php
                if (empty($_POST["adress"]) && isset($_POST["validate"])) {
                  echo $error;
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sex" id="sex" value="h" checked>
              <label class="form-check-label" for="sex">Homme</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sex" id="sex" value="f" checked>
              <label class="form-check-label" for="sex">Femme</label>
            </div>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="text" class="form-control" id="password" name="password" value=<?php if(!empty($_POST["password"])){ echo $_POST["password"];}?>>
            <small>
              <?php
                if (empty($_POST["password"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                echo "$wrong_password";
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="password_b">Confirmer mot de passe :</label>
            <input type="text" class="form-control" id="password_b" name="password_b" value=<?php if(!empty($_POST["password_b"])){ echo $_POST["password_b"];}?>>
            <small>
              <?php
                if (empty($_POST["password_b"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                echo "$not_the_same_password";
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="birth_date">Date de naissance :</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" value=<?php if(!empty($_POST["birth_date"])){ echo $_POST["birth_date"];}?>>
            <small>
              <?php
                if (empty($_POST["birth_date"]) && isset($_POST["validate"])) {
                  echo "$error";
                }
              ?>
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
