<?php
  include "template/nav.php";
  include "template/header.php";
?>
<?php
  $info_user = $_SESSION["user_email"];
  //Our SESSION["user_email"] give us the id from user connected
  //and then we can get accounts from user
  $query = $db->prepare(
    "SELECT DISTINCT a.id AS a_id, a.amount AS Votre_solde, a.opening_date AS Ouverture, a.account_type, o.operation_type AS Dernière_transaction, o.amount AS montant, o.registered AS Enregistré_le, o.label
    FROM User AS u
    INNER JOIN Account AS a
    ON u.id = a.user_id AND u.id = :user_id
    -- show account even there is no operation
    LEFT JOIN Operation AS o
    ON a.id = o.account_id
    WHERE o.id IN (SELECT MAX(o.id)
    FROM Operation AS o
    GROUP BY o.account_id)
    OR a.id NOT IN (SELECT o.account_id
    FROM Operation AS o)"
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
          <?php if (!empty($account) && $keys !== "account_type" && $keys !== "a_id"): ?>
              <li class="list-group-item"><?php echo $keys . ' : ' . $account ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <div class="card-body d-flex justify-content-center align-items-center">
          <a href="showaccount.php<?php echo '?id=' . $accounts["a_id"]?>" class="btn btn-primary">Voir mon compte</a>
        </div>
      </div>
<?php endforeach; ?>
  </div>
</div>



<!-- <script src="js/main.js"></script> -->
<?php
  include "template/footer.php";
?>
