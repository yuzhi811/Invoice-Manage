<?php 
require "data.php";
require "template.php";
// sanitizing
// function sanitize ($data){
//   //remove extra spaces from ends
//   $data = trim($data);
//   //removie slashes 
//   $data = stripslashes($data);
//   // convert HTML
//   $data = htmlspecialchars($data);
// // all in one line
// //  return htmlspecialchars(stripslashes(trim($data)));
//   return $data;
// }
function sanitize ($data) {
  return array_map(function($value){
    return htmlspecialchars(stripslashes(trim($value)));
  }, $data);
}

// function formDataIsValid()

// created new invoice array containing all of the post data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // replace the data for this number with the data we've received from POST
  $edit = [
    'number' => $_POST['number'],
    'amount' => $_POST['invoiceAmount'],
    'status' => $_POST['invoiceStatus'],
    'client' => $_POST['clientName'],
    'email' => $_POST['clientEmail']
  ];
  // VALIDATION
  // $name = sanitize($_POST['clientName']); 대신에
  $data = sanitize($_POST);
  extract($data);
  // hold every error inside this array
  $errors = [];
  $hasError = false;

  // 1. see if it is empty
  if (!$clientName) {
    $errors['name'] = "Name is required";
  } else if (!preg_match('/^[a-zA-Z\s]+$/', $clientName)) {
    $errors['name'] = 'Name is invaild';
  } else if (strlen($clientName) > 255) {
    $errors['name'] = 'Name must be fewer than 255 characters';
  }

  //email
  if(!$clientEmail){
    $errors['email'] = 'Email is required';
  } else if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email is invalid';
  }

  //invoice amount
  if(!$invoiceAmount) {
    $errors['amount'] = 'Invoice amount is required';
  } else if(!filter_var($invoiceAmount, FILTER_VALIDATE_INT) || $invoiceAmount < 0) {
$errors['amount'] = 'Invoice amount is invalid';
  }

  //Invoice Status
  if (!$invoiceStatus) {
    $errors['status'] = 'Status is required';
  } else if (!in_array($invoiceStatus, $statuses)) {
    $errors['status'] = 'Status is invalid';
  }
 // Check if there are any errors
  if(empty($errors)) {
    header("Location: index.php");
  }

  // array_map으로 기존 데이터에 새로운 데이터 추가 (replace)
  // $iv: each invoice
  $invoices = array_map(function ($iv) use ($edit){
    if ($iv['number'] == $edit['number']){
      // var_dump($edit);
      // replace
      return $edit;
    }
    // if it's not invoice we want, just return its orginal value. that means all the other invoices will be left alone but the new invoice will be put in place 
    // var_dump($iv);
    return $iv;
  }, $invoices);

  $_SESSION['invoices'] = $invoices;

  // header("Location: index.php");
} 

// querystring 이용해 해당하는 데이터 가져오기
if (isset($_GET['number'])){
  $invoice = current(array_filter($invoices, function($invoice){
    return $_GET['number'] == $invoice['number'];
  }));
if (!$invoice) {
  header("Location: index.php");
} 
} else {
  header("Location: index.php");
} // exit();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Invoice Info</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="main">
    <h2 class="form-title">Edit Invoice</h2>
    <form class="form" method="post"> 
      <!-- hidden input: replace $_GET['id'] 유저들한텐 안 보이지만 form에 속함 -->
<!-- will allow us to replace 위의 $Get[id] with post-->
      <input type="hidden" name="number" value="<?php echo $invoice['number']; ?>">
      

      <!-- 1. value: whatever movie has been selected // validation: <?php echo $name ?? '' ?>"> if it is null, put the empty string -->
      <input type="text" class="form-control <?php if(isset($errors['name'])) : ?> is-invalid <?php endif; ?>" name="clientName" value="<?php echo $invoice['client'] ?? '' ?>"> 
<!-- based off of whether or not we have an error and what that error is  -->
      <div class="invaild-feedback error text-danger">
        <?php if(isset($errors['name'])) : ?>
          <?php echo $errors['name']; ?>
          <?php endif; ?>
      </div>
      <!--  -->
      <input type="text" class="form-control <?php if(isset($errors['email'])): ?> is-invalid <?php endif; ?>" name="clientEmail" placeholder="Email" required
          value="<?php echo $invoice['email'] ?? '' ?>">
          <div class="invaild-feedback error text-danger">
          <?php if(isset($errors['email'])): ?>
                  <?php echo $errors['email']; ?>
                <?php endif; ?>
          </div>
      <input type="number" class="form-control <?php if(isset($errors['amount'])): ?> is-invalid <?php endif; ?>" name="invoiceAmount" placeholder="Invoice Amount" required
          value="<?php echo $invoice['amount'] ?? '' ?>">
          <div class="invaild-feedback error text-danger">
          <?php if(isset($errors['amount'])): ?>
                  <?php echo $errors['amount']; ?>
                <?php endif; ?>
          </div>
          <!-- 2. select: which option is the one that matches the invoice and we're gonna have to add the select attribute to that option -->
      <select class="form-select <?php if(isset($errors['status'])): ?> is-invalid <?php endif; ?>" name="invoiceStatus">
        <option value=""> Select a Status</option>
        <?php foreach($statuses as $status) : ?>
          <?php if($status !== 'all') : ?>
        <!-- <option value="<?php echo $_POST['invoiceStatus'] ?? ''; ?>" -->
        <option value="<?php echo $status; ?>"
        <?php if ($status === $invoice['status']): ?> selected <?php endif; ?>>

        <?php echo $status; ?></option>
        <?php endif; ?>
        <?php endforeach; ?>
          <!-- compare status, selected movie , add to select -->
          <!-- selected: tell html that this option should be selected -->
        </option>
          <!-- generating each option dynamically with php inside of a foreach loop // compare the status of the current option with the $movie status of the selected movie -->
          <!-- select attribute will tell html that this option should be selected -->
      </select>
      <!-- we pre-populated this form with this selected movie data -->
      <div class="invaild-feedback error text-danger">
      <?php if(isset($errors['status'])): ?>
                  <?php echo $errors['status']; ?>
                <?php endif; ?>
      </div>
      <button type="submit" class="button updateBtn">Update Invoice Info</button>
    </form>
    <!-- delete -->
    <!-- <form action="delete.php" class="form">
      <input type="hidden" name="movie_id" value="<?php echo $movie['movie_id']; ?>">
      <button class="button danger">Delete Movie</button> -->
    </form>
  </main>
</body>
</html>