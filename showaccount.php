<?php
  include "template/nav.php";
  include "template/header.php";
 ?>
<?php
  if (!empty($_GET["id"]) && isset($_GET["id"])):
    //with GET, we have the ID from Account table
    $account_id = test_input($_GET["id"]);

    //we target columns from Operation table
    $query = $db->prepare(
      "SELECT o.operation_type, o.amount, o.registered, o.label
      FROM Account AS a
      INNER JOIN Operation AS o
      WHERE a.id = o.account_id AND a.id = :account_id"
    );
    $result = $query->execute([
      "account_id" => $account_id
    ]);
    $operation_user = $query->fetchAll(PDO::FETCH_ASSOC);

    //we target columns from Account table
    $con = $db->prepare(
      "SELECT a.amount, a.opening_date, a.account_type
      FROM Account AS a
      WHERE a.id = :id_account"
    );
    $results = $con->execute([
      "id_account" => $account_id
    ]);
    //we only fetch cause there is only one account to show
    $account_user = $con->fetch(PDO::FETCH_ASSOC);
  endif;
?>
<div class="container">
  <div class="row">
    <div class="card col-10 col-md-5 mx-auto my-4" style="width: 18rem;">
      <div class="card-header">
        <?php echo $account_user["account_type"] ?>
      </div>
      <ul class="list-group list-group-flush">
        <?php foreach ($account_user as $key => $account): ?>
          <?php if ($key !== "account_type"): ?>
            <li class="list-group-item"><?php echo $key . ' : ' . $account; ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <a href="index.php" class="btn btn-primary">Acceuil</a>
    </div>
    <?php foreach ($operation_user as $key => $operations): ?>
      <div class="card col-10 col-md-5 mx-auto my-4" style="width: 18rem;">
        <div class="card-header">
          Dernières opérations
        </div>
        <ul class="list-group list-group-flush">
          <?php foreach ($operations as $key => $operation): ?>
            <li class="list-group-item"><?php echo $key . ' : ' . $operation; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php
  include "template/footer.php";
 ?>
