<?php 
  //Callback function to filter 'draft'
  function draft_filter ($data) {
    return $data['status'] === 'draft';
  }
  //Filter the array using array_filter
  $draft = array_filter($invoices, 'draft_filter');

  //Callback function to filter 'paid'
  function paid_filter ($data) {
    return $data['status'] === 'paid';
  }

  //Filter the array using array_filter
  $paid = array_filter($invoices, 'paid_filter');

    //Callback function to filter 'pending'
    function pending_filter ($data) {
      return $data['status'] === 'pending';
    }
  
    //Filter the array using array_filter
    $pending = array_filter($invoices, 'pending_filter');

// **Optimized
// Callback function to filter invoices by status
    function status_filter($data, $status) {
      return $data['status'] === $status;
    }
// Filter the array using array_filter and the specified status
    function filter_invoices($invoices, $status) {
      return array_filter($invoices, function ($data) use ($status){
        return status_filter($data, $status);
      });
    }


  // Get the selected status from the URL (the value of the data parameter from the URL query string to the variable $data. If the data parameter is not present, it assigns the default value 'all' to $data.)
  $statuses_url = isset($_GET['data']) ? $_GET['data'] : 'all';

  //Filter the invoices based on the selected status
  if($statuses_url === 'all') {
    $filtered_invoices = $invoices;
  } else {
    $filtered_invoices = filter_invoices($invoices, $statuses_url);
  }

 //function for change colors
      function switch_colours($statuses_url){
      switch($statuses_url) {
        case 'draft':
          return 'btn-info';
          break;
          case 'paid':
            return 'btn-success';
            break;
          case 'pending':
            return 'btn-warning';
            break;
            default:
            // Handle other cases if needed
            break;
      }
    
      }

// Count the filtered data
    $counted_data = count($filtered_invoices);

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
      ?>
    <!-- Tabs -->
  <nav class="nav nav-tabs">
    <?php foreach($statuses as $status) :?>
      <?php if($status === 'all') :?>
        <div class="nav-item">
<!--  <a class="nav-link" href="<?php echo $status; ?>.php?data=<?php echo $status; ?>"><?php echo $status; ?></a> -->
          <a class="nav-link" href="index.php?data=all">all</a> 
        </div>
          <?php else : ?>
            <div class="nav-item">
            <a class="nav-link" href="<?php echo $status; ?>.php?data=<?php echo $status; ?>"><?php echo $status; ?></a>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>  
          </nav>

<?php foreach($filtered_invoices as $invoice) : ?>
  <div class="eachData col-">
  <div class="invoice-number col">#<?php echo $invoice['number']?></div>
  <?php 
  $subject = 'This is your invoice';
  $emailLink = 'mailto:' . $invoice['email'] . $subject . urlencode($subject);
  ?>
  <div class="invoice-client col">
    <a href="<?php echo $emailLink;?>"><?php echo $invoice['client'];?></a>
</div>
  <div class="invoice-amount col">$<?php echo $invoice['amount']?></div>
  <div class="invoice-status col btn <?php echo switch_colours($invoice['status']); ?>"><?php echo $invoice['status']?></div>
  </div>
  <?php endforeach; ?>

</body>
</html>