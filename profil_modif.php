<?php
  include "template/nav.php";
  include "template/header.php";
?>

<?php
$info_user = $_SESSION["user_email"];
print_r($info_user);

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
  print_r($array_user[0]);
  foreach ($array_user[0] as $key => $value) {
    array_push($array_post_input,test_input($_POST[$key]));
    array_push($array_key_user,$key);
  }
  //en combinant ces tableaux, la lecture est plus facile
  $array_user_entry = array_combine($array_key_user, $array_post_input);

  $email = filter_var($array_user_entry["email"], FILTER_VALIDATE_EMAIL);
  $city = preg_match("/^[a-zA-Z-' ]{2,30}$/",$array_user_entry["city"]);
  $city_code = preg_match("/^[0-9]{0,5}$/",$array_user_entry["city_code"]);
  $adress = preg_match("/[0-9a-zA-Z-' ]{2,50}/",$array_user_entry["adress"]);
  $password = preg_match("/.{2,255}/",$array_user_entry["password"]);
  $password_hash = password_hash($array_user_entry["password"], PASSWORD_BCRYPT);

  //est ce que l'email est dans la bdd
  function test_email() {
    global $email, $array_user, $array_user_entry;
    foreach ($array_user as $key => $value) {
      if ($value !== $info_user["email"]) {
        if (in_array($email,$value)) {
          $email = "" ;
        }
        else {
          $email;
        }
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
  test_email();
  if($email !== "") {
    echo "ok1";
    if ($city) {
      if ($city_code) {
        if ($adress) {
          if ($password && $array_user_entry["password"] === test_input($_POST["password_b"])) {
            test_password();
            if ($password_hash !== "") {
              $query = $db->prepare(
                "UPDATE User AS u
                SET u.email = :email, u.city = :city, u.city_code = :city_code, u.adress = :adress, u.password = :password
                WHERE u.id = :user_id"
              );
              $result = $query->execute([
                "user_id" => $info_user["id"],
                "email" => $email,
                "city" => $array_user_entry["city"],
                "city_code" => $array_user_entry["city_code"],
                "adress" => $array_user_entry["adress"],
                "password" => $password_hash,
              ]);
            }
          }
        }
      }
    }
  }
}
?>
<section class="container">
  <h3 class="text-center">Modifier votre profil</h3>
  <div class="row">
    <form class="col-10 mx-auto" action="" method="POST">
      <div class="form-group">
        <label for="email">E-mail :</label>
        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value=<?php echo $info_user["email"];?>>
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
        <input type="text" class="form-control" id="city" name="city" value=<?php echo $info_user["city"];?>>
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
        <input type="number" class="form-control" id="city_code" name="city_code" value=<?php echo $info_user["city_code"];?>>
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
        <input type="text" class="form-control" id="adress" name="adress" value=<?php echo $info_user["adress"];?>>
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
      <button type="submit" class="btn btn-primary" name="validate" value="valider">Valider</button>
    </form>
  </div>
</section>

<?php
  include "template/footer.php";
?>
