<?php 
require "data.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <title>Add</title>
</head>
<body>
<h1>Invoice Manager</h1>
<p>Create a new invoice.</p>
<div class="text-end">
      <a href="index.php">Back></a> 
      </div>

      <div class="container">
  <div class="row">
    <div class="col">      
<form class="form" method="post" action="index.php">
<label for="client_name">Client Name</label>
<input type="text" class="form-control col-8" name="client_name" placeholder="Client Name" required>
      <label for="client_email">Client Email</label>
      <input type="text" class="form-control" name="client_email" placeholder="Client Email" required>
            <label for="invoice_amount">Invoice Amount</label>      
    <input type="number" class="form-control" name="invoice_amount" placeholder="Invoice Amount" required>
          <label for="invoice_status">Invoice Status</label>      
    <select class="form-select" name="invoice_status">
        <option value="">Select a Status</option>
        <?php foreach($statuses as $status) : ?>
          <?php if($status !== 'all') : ?>
        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
      </select>
            <button type="submit" class="button">Submit</button>
    </form>
    </div>
  </div>
</div>
</body>
</html>