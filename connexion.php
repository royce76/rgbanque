<?php
  function test_input($data) {
    $data = trim($data); // remove space of both side
    $data = stripslashes($data);// remove backslashes
    $data = htmlspecialchars($data, ENT_QUOTES);//both quotes
    return $data;
  }
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

 <!DOCTYPE html>
 <html lang="fr" dir="ltr">
 <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
   <title>Page de connexion</title>
 </head>
 <body>
   <header>
     <h2 class="text-center my-4">Bienvenue chez RGBANQUE</h2>
   </header>
   <main>
     <section class="container">
       <h3 class="text-center">Connexion</h3>
       <div class="row">
         <form class="col-10 offset-1" action="" method="POST">
           <div class="form-group">
             <label for="email">Adresse E-mail :</label>
             <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
           </div>
           <div class="form-group">
             <label for="password">Mot de passe :</label>
             <input type="password" class="form-control" id="password" name="password">
           </div>
           <button type="submit" class="btn btn-primary" name="connect" value="Valider">Valider</button>
         </form>
       </div>
     </section>
   </main>

   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
 </body>
 </html>
