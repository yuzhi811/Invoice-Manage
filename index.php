<?php 
require "template.php";

// Form Post data
if(isset($_POST['client_name'])) {
  // var_dump($_POST);
  array_push($invoices, [
    'number' => strtoupper($randomString),
    'client' => $_POST['client_name'],
    'email' => $_POST['client_email'],
    'amount' => $_POST['invoice_amount'],
    'status' => $_POST['invoice_status']
  ]);
  // we're updating session when we update invoices
// call the session
$_SESSION['invoices'] = $invoices;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <title><?php echo $statuses_url ?></title>
</head>
<body>
  <h1>Invoice Manager</h1>
  <?php 
      echo "<p>There are $counted_data invoices.</p>";
      // var_dump(isset($_GET['status'])); //true
      ?>
      <div class="text-end">
      <a href="add.php">Add></a> 
      </div>
    <!-- Navigation Tabs -->
    <nav class="nav nav-tabs">
        <div class="nav-item">
          <a class="nav-link <?php echo activeTab("all") ?>" href="index.php?status=all">all</a> 
        </div>
        <div class="nav-item">
            <a class="nav-link <?php echo activeTab("draft") ?>" href="index.php?status=draft">draft</a> 
            </div>
            <div class="nav-item">
          <a class="nav-link <?php echo activeTab("pending") ?>" href="index.php?status=pending">pending</a> 
          </div>
          <div class="nav-item">
          <a class="nav-link <?php echo activeTab("paid") ?>" href="index.php?status=paid">paid</a> 
          </div>
            </div>
          </nav>
          
<!-- queryselector is being passed through the URL to dusplay the different lists -->
<div class="container">
  <div class="row">
    <?php foreach($filtered_invoices as $invoice) : ?>
      <div class="col-md-13 col-lg-10">
        <div class="d-flex flex-column justify-content-between">
          <div class="eachData p-3">
            <div class="invoice-number px-4">#<?php echo $invoice['number']?></div>
            <?php 
            $subject = 'This is your invoice';
            $emailLink = 'mailto:' . $invoice['email'] . $subject . urlencode($subject);
            ?>
            <div class="invoice-client px-4">
              <div class="w-100">
                <a href="<?php echo $emailLink;?>"><?php echo $invoice['client'];?></a>
              </div>
            </div>
            <div class="invoice-amount text-center mx-3">$<?php echo $invoice['amount']?></div>
            <div class="invoice-status px-4 mx-auto mx-3 btn <?php echo switch_colours($invoice['status']); ?>"><?php echo $invoice['status']?></div>
            <div class="d-flex justify-content-center mx-3">
              <button class="editBtn btn mx-2 btn-outline-primary"><a class="btn" href="update.php?number=<?php echo $invoice['number']; ?>" >Edit</a></button>
              <form class="form" method="post" action="delete.php">
                <input type="hidden" name="number" value="<?php echo $invoice['number']; ?>">
                <!-- role="button" -->
                <button class="deleteBtn btn mx-2 btn-outline-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>