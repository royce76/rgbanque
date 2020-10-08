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

  //this function look for id from array accounts
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

  if (isset($_POST["supprimer"])) {
    echo search_account_id();
    $query = $db->prepare(
      "DELETE FROM Account
      WHERE id = :a_id"
    );
    $results = $query->execute([
      "a_id" => search_account_id()
    ]);
  }
 ?>

 <div class="container">
   <div class="row">
     <form action="" method="POST" class="col-10 mx-auto">
       <div class="form-group">
         <label for="compte">Votre compte :</label>
         <select class="form-control" id="compte" name="compte">
           <option value="">--Choisissez votre de compte--</option>
           <?php foreach ($account_type as $key => $accounts): ?>
             <?php foreach ($accounts as $key => $account): ?>
               <?php if ($key === "account_type" ): ?>
                 <option value="<?php echo $account; ?>"> <?php echo $account; ?></option>
               <?php endif; ?>
             <?php endforeach; ?>
           <?php endforeach; ?>
         </select>
       </div>
       <button type="submit" class="btn btn-primary mb-2" name="supprimer">Supprimer</button>
     </form>
   </div>
 </div>

<?php
include "template/footer.php";
?>
