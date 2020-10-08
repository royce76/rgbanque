<?php
include "template/nav.php";
include "template/header.php";
?>

<?php
  $info_user = $_SESSION["user_email"];

  //create array accounts from user connected
  $con = $db->prepare(
    "SELECT a.id, a.amount, a.account_type
    FROM Account as a
    WHERE user_id = :id_user"
  );
  $result = $con->execute([
    "id_user" => $info_user["id"]
  ]);
  $account_type = $con->fetchAll(PDO::FETCH_ASSOC);

  //this three functions look for id,amount,account_type from array accounts
  function search_account_id() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $accounts["id"];
        }
      }
    }
  }

  function search_account_amount() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $accounts["amount"];
        }
      }
    }
  }

  function search_account_type() {
    global $account_type;
    foreach ($account_type as $key => $accounts) {
      foreach ($accounts as $key => $account) {
        if ($account == test_input($_POST["compte"])) {
          return $account;
        }
      }
    }
  }

  //then 2 choices, one with debit, one with credit, updating on the card account
  if (isset($_POST["valider"]) && $_POST["mouvement"] === "credit") {
    $query = $db->prepare(
      "UPDATE Account
      SET amount = :new_amount + :old_amount
      WHERE id = :a_id"
    );
    $result = $query->execute([
      "new_amount" => test_input($_POST["amount"]),
      "old_amount" => search_account_amount(),
      "a_id" => search_account_id()
    ]);

    //request with the right account_id
    $query = $db->prepare(
      "INSERT INTO Operation (operation_type, amount, registered, label, account_id)
      VALUES (:operation_type, :amount, NOW(), :label, :account_id)"
    );
    $result = $query->execute([
      "operation_type" => test_input($_POST["mouvement"]),
      "amount" => test_input($_POST["amount"]),
      "label" => test_input($_POST["label"]),
      "account_id" => search_account_id()
    ]);
  }

  elseif (isset($_POST["valider"]) && $_POST["mouvement"] === "debit") {

    $query = $db->prepare(
      "UPDATE Account
      SET amount = :old_amount - :new_amount
      WHERE id = :a_id"
    );
    $result = $query->execute([
      "new_amount" => test_input($_POST["amount"]),
      "old_amount" => search_account_amount(),
      "a_id" => search_account_id()
    ]);

    //request with the right account_id
    $query = $db->prepare(
      "INSERT INTO Operation (operation_type, amount, registered, label, account_id)
      VALUES (:operation_type, :amount, NOW(), :label, :account_id)"
    );
    $result = $query->execute([
      "operation_type" => test_input($_POST["mouvement"]),
      "amount" => test_input($_POST["amount"]),
      "label" => test_input($_POST["label"]),
      "account_id" => search_account_id()
    ]);
  }
 ?>

 <div class="container">
   <div class="row">
     <form action="" method="POST" class="col-10 mx-auto">
       <div class="form-group">
         <label for="compte_emetteur">Votre compte émetteur :</label>
         <select class="form-control" id="compte_emetteur" name="compte_emetteur">
           <option value="">--Choisissez votre de compte--</option>
           <?php foreach ($account_type as $key => $accounts): ?>
             <?php foreach ($accounts as $key => $account): ?>
               <?php if ($key === "account_type"): ?>
                 <option class="emetteur" value="<?php echo $account; ?>"> <?php echo $account; ?></option>
               <?php endif; ?>
             <?php endforeach; ?>
           <?php endforeach; ?>
         </select>
       </div>
       <div class="form-group">
         <label for="compte_beneficiaire">Votre compte bénéficiaire:</label>
         <select class="form-control" id="compte_beneficiaire" name="compte_beneficiaire">
           <option value="">--Choisissez votre de compte--</option>
           <?php foreach ($account_type as $key => $accounts): ?>
             <?php foreach ($accounts as $key => $account): ?>
               <?php if ($key === "account_type"): ?>
                 <option class="beneficiaire" value="<?php echo $account; ?>"> <?php echo $account; ?></option>
               <?php endif; ?>
             <?php endforeach; ?>
           <?php endforeach; ?>
         </select>
       </div>
       <div class="form-group">
         <label for="mouvement">Retrait/Dépôt :</label>
         <select class="form-control" id="mouvement" name="mouvement">
           <option value="">--Retrait/Dépôt--</option>
           <option value="debit">Débit</option>
           <option value="credit">Crédit</option>
         </select>
       </div>
       <div class="form-group">
         <label for="amount">Montant (Minimum 20 euro) :</label>
         <input type="number" class="form-control" id="amount" name="amount" value="20" min="20" required>
       </div>
       <div class="form-group">
         <label for="label">Example label</label>
         <input type="text" class="form-control" id="label" placeholder="label..." name="label">
       </div>
       <button type="submit" class="btn btn-primary mb-2" name="valider">Valider</button>
     </form>
   </div>
 </div>

<script src="js/transfer.js" charset="utf-8"></script>
<?php
include "template/footer.php";
?>
