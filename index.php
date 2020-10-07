<?php
  include "template/nav.php";
  include "template/header.php";
?>
<?php
  $info_user = $_SESSION["user_email"];
  $query = $db->prepare(
    "SELECT a.id, a.amount, a.opening_date, a.account_type FROM User AS u
      INNER JOIN Account AS a
      WHERE u.id = a.user_id AND u.id = :user_id"
  );
  $result = $query->execute([
    "user_id" => $info_user["id"]
  ]);

  $account_user = $query->fetchAll(PDO::FETCH_ASSOC);
 ?>
<h2 class="text-center my-4">Tous vos comptes</h2>
<div class="container">
  <div class="row">
<?php foreach ($account_user as $key => $accounts): ?>
      <div class="card col-10 col-md-5 mx-auto my-4" style="width: 18rem;">
        <div class="card-header">
          <?php echo $accounts["account_type"] ?>
        </div>
        <ul class="list-group list-group-flush">
        <?php foreach ($accounts as $keys => $account): ?>
          <?php if ($keys !== "account_type" && $keys !== "id"): ?>
              <li class="list-group-item"><?php echo $keys . ' : ' . $account ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <div class="card-body d-flex justify-content-center align-items-center">
          <a href="showaccount.php<?php echo '?id=' . $accounts["id"]?>" class="btn btn-primary">Voir mon compte
          </a>
        </div>
      </div>
<?php endforeach; ?>
  </div>
</div>



<!-- <script src="js/main.js"></script> -->
<?php
  include "template/footer.php";
?>
