<?php 
  require "data.php";

  // generate random string 
function getInvoiceNumber ($length = 5) {
  $letters = range('A', 'Z');
  $number = [];
  
  for ($i = 0; $i < $length; $i++) {
    array_push($number, $letters[rand(0, count($letters) - 1)]);
  }
  return implode($number);
}
$randomString = getInvoiceNumber();

// // Form Post data
// if(isset($_POST['client_name'])) {
//   // var_dump($_POST);
//   array_push($invoices, [
//     'number' => strtoupper($randomString),
//     'client' => $_POST['client_name'],
//     'email' => $_POST['client_email'],
//     'amount' => $_POST['invoice_amount'],
//     'status' => $_POST['invoice_status']
//   ]);
//   // we're updating session when we update invoices
// // call the session
// $_SESSION['invoices'] = $invoices;
// }
// var_dump($invoice);
// var_dump($invoices);

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
  $statuses_url = isset($_GET['status']) ? $_GET['status'] : 'all';

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

//function for active tab
function activeTab ($status) {
  global $statuses_url;
  if ($status == $statuses_url){
    return "active";
  }
}

// Count the filtered data
    $counted_data = count($filtered_invoices);
?>
