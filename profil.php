
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
// mes varibales initialisés
$result = FALSE;
$not_the_same_password = "";
$wrong_email = "";
$wrong_password = "";
$error = "Champs requis";
$text_rules = "";
$array_post_input = [];
$array_key_user = [];

if (isset($_POST["validate"]) && !empty($_POST["validate"])) {

  //on récupère les entrées "post" de user avec les noms des champs dans un tableau "array_user_entry"
  $con = $db->query(
    "SELECT u.lastname, u.firstname, u.email, u.city, u.city_code, u.adress, u.sex, u.password, u.birth_date
    FROM User AS u"
  );
  $array_user = $con->fetchAll(PDO::FETCH_ASSOC);
  foreach ($array_user[0] as $key => $value) {
    array_push($array_post_input,test_input($_POST[$key]));
    array_push($array_key_user,$key);
  }
  //en combinant ces tableaux, la lecture est plus facile
  $array_user_entry = array_combine($array_key_user, $array_post_input);

  $lastname = preg_match("/^[a-zA-Z-' ]{2,50}$/",$array_user_entry["lastname"]);
  $firstname = preg_match("/^[a-zA-Z-' ]{2,50}$/",$array_user_entry["firstname"]);
  $email = filter_var($array_user_entry["email"], FILTER_VALIDATE_EMAIL);
  $city = preg_match("/^[a-zA-Z-' ]{2,30}$/",$array_user_entry["city"]);
  $city_code = preg_match("/^[0-9]{0,5}$/",$array_user_entry["city_code"]);
  $adress = preg_match("/^[0-9a-zA-Z-' ]{2,50}$/",$array_user_entry["adress"]);
  $password = preg_match("/.{2,255}/",$array_user_entry["password"]);
  $password_hash = password_hash($array_user_entry["password"], PASSWORD_BCRYPT);

  //est ce que l'email est dans la bdd
  function test_email() {
    global $email, $array_user, $array_user_entry;
    foreach ($array_user as $key => $value) {
      if (in_array($email,$value)) {
        $email = "" ;
      }
      else {
        $email;
      }
    }
    return $email;
  }

  //est ce que le mot de passe hashé est dans la bdd
  function test_password() {
    global $password_hash, $array_user, $array_user_entry;
    foreach ($array_user as $key => $value) {
      if (password_verify($array_user_entry["password"],$value["password"])) {
        $password_hash = "";
      }
      else {
        $password_hash;
      }
    }
    return $password_hash;
  }

  //pour ne pas que l'utilisateur attende les informations fausse on les lance hors conditions

  function wrong_email() {
    global $wrong_email, $array_user, $array_user_entry;
    foreach ($array_user as $key => $value) {
      if (in_array($array_user_entry["email"],$value)) {
        $wrong_email = "Email déja utilisé" ;
      }
      else {
        $wrong_email;
      }
    }
    return $wrong_email;
  }

  function wrong_password() {
    global $wrong_password, $array_user, $array_user_entry;
    foreach ($array_user as $key => $value) {
      if (password_verify($array_user_entry["password"],$value["password"])) {
        $wrong_password = "Mot de passe déjà utilisé";
      }
      else {
        $wrong_password;
      }
    }
    return $wrong_password;
  }

  // on teste l'un après l'autre les champs
  if ($lastname) {
    if ($firstname) {
      test_email();
      if($email !== "") {
        if ($city) {
          if ($city) {
            if ($adress) {
              if(!empty($array_user_entry["sex"])) {
                if ($password && $array_user_entry["password"] === test_input($_POST["password_b"])) {
                  test_password();
                  if ($password_hash !== "") {
                    if (!empty($array_user_entry["birth_date"])) {
                      $query = $db->prepare(
                        "INSERT INTO User (lastname, firstname, email, city, city_code, adress, sex, password, birth_date)
                        VALUES (:lastname, :firstname, :email, :city, :city_code, :adress, :sex, :password, :birth_date)"
                      );
                      $result = $query->execute([
                        "lastname" => $array_user_entry["lastname"],
                        "firstname" => $array_user_entry["firstname"],
                        "email" => $email,
                        "city" => $array_user_entry["city"],
                        "city_code" => $array_user_entry["city_code"],
                        "adress" => $array_user_entry["adress"],
                        "sex" => $array_user_entry["sex"],
                        "password" => $password_hash,
                        "birth_date" => $array_user_entry["birth_date"]
                      ]);
                      header("Location: connexion.php");
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
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
            <input type="text" class="form-control" id="lastname" name="lastname" value=<?php if(!empty($_POST["lastname"])){ echo $array_user_entry["lastname"];}?>>
            <small>
              <?php
                if (empty($_POST["lastname"]) && isset($_POST["validate"])) {
                  echo "$error" ;
                }
                elseif (!empty($_POST["lastname"]) && isset($_POST["validate"])) {
                  if (!$lastname) {
                    echo "pas bon";
                  }
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" class="form-control" id="firstname" name="firstname" value=<?php if(!empty($_POST["firstname"])){ echo $array_user_entry["firstname"];}?>>
            <small>
              <?php
                if (empty($_POST["firstname"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["firstname"]) && isset($_POST["validate"])) {
                  if (!$firstname) {
                    echo "pas bon";
                  }
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="email">E-mail :</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value=<?php if(!empty($_POST["email"])){ echo $array_user_entry["email"];}?>>
            <small>
              <?php
                if (empty($_POST["email"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["email"]) && isset($_POST["validate"])) {
                  $w_e = wrong_email();
                  echo "$w_e<br>";
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city">Ville :</label>
            <input type="text" class="form-control" id="city" name="city" value=<?php if(!empty($_POST["city"])){ echo $array_user_entry["city"];}?>>
            <small>
              <?php
                if (empty($_POST["city"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["city"]) && isset($_POST["validate"])) {
                  if (!$city) {
                    echo "pas bon";
                  }
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="city_code">Code Postale :</label>
            <input type="number" class="form-control" id="city_code" name="city_code" value=<?php if(!empty($_POST["city_code"])){ echo $array_user_entry["city_code"];}?>>
            <small>
              <?php
                if (empty($_POST["city_code"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["city_code"]) && isset($_POST["validate"])) {
                  if (!$city_code) {
                    echo "pas bon";
                  }
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="adress">Adresse :</label>
            <input type="text" class="form-control" id="adress" name="adress" value=<?php if(!empty($_POST["adress"])){ echo $array_user_entry["adress"];}?>>
            <small>
              <?php
                if (empty($_POST["adress"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["adress"]) && isset($_POST["validate"])) {
                  if (!$adress) {
                    echo "pas bon";
                  }
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
            <input type="text" class="form-control" id="password" name="password" value=<?php if(!empty($_POST["password"])){ echo $array_user_entry["password"];}?>>
            <small>
              <?php
                if (empty($_POST["password"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["password"]) && isset($_POST["validate"])) {
                  $w_p = wrong_password();
                  echo "$w_p";
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="password_b">Confirmer mot de passe :</label>
            <input type="text" class="form-control" id="password_b" name="password_b" value=<?php if(!empty($_POST["password_b"])){ echo test_input($_POST["password_b"]);}?>>
            <small>
              <?php
                if (empty($_POST["password_b"]) && isset($_POST["validate"])) {
                  echo $error;
                }
                elseif (!empty($_POST["password_b"]) && isset($_POST["validate"])) {
                  echo "saisie non correspondante";
                }
              ?>
            </small>
          </div>
          <div class="form-group">
            <label for="birth_date">Date de naissance :</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" value=<?php if(!empty($_POST["birth_date"])){ echo $array_user_entry["birth_date"];}?>>
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
