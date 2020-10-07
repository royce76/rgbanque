<?php
  include "template/nav.php";
  include "template/header.php";
?>
<?php
  $info_user = $_SESSION["user_email"];
  echo $info_user["id"];
  $query = $db->prepare(
    "SELECT u.id, a.amount, a.opening_date, a.account_type FROM User AS u
      INNER JOIN Account AS a
      WHERE u.id = a.user_id AND u.id = :user_id"
  );
  $result = $query->execute([
    "user_id" => $info_user["id"]
  ]);

  $account_user = $query->fetch(PDO::FETCH_ASSOC);
  print_r($account_user);
 ?>
  <section class="container">
    <h2 class="text-center my-4">Tous vos comptes</h2>
    <div class="row">
      <div class="card col-10 col-md-5 col-lg-3 mx-auto my-4" style="width: 18rem;">
        <div class="card-header col-10 mx-auto">
        </div>
        <ul class="list-group list-group-flush">
        </ul>
        <div class="card-body d-flex justify-content-center align-items-center">
          <a href="showaccount.php" class="btn btn-primary">Voir mon compte
          </a>
        </div>
      </div>
    </div>
  </section>


<script src="js/main.js"></script>
<?php
  include "template/footer.php";
?>
