<?php
session_start();
error_reporting(1);

$db_config_path = '../application/config/database.php';

if (!isset($_SESSION["license_code"])) {
    $_SESSION["error"] = "Invalid purchase code!";
    header("Location: index.php");
    exit();
}

if (isset($_POST["btn_admin"])) {

    $_SESSION["db_host"] = $_POST['db_host'];
    $_SESSION["db_name"] = $_POST['db_name'];
    $_SESSION["db_user"] = $_POST['db_user'];
    $_SESSION["db_password"] = $_POST['db_password'];


    /* Database Credentials */
    defined("DB_HOST") ? null : define("DB_HOST", $_SESSION["db_host"]);
    defined("DB_USER") ? null : define("DB_USER", $_SESSION["db_user"]);
    defined("DB_PASS") ? null : define("DB_PASS", $_SESSION["db_password"]);
    defined("DB_NAME") ? null : define("DB_NAME", $_SESSION["db_name"]);

    /* Connect */
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $connection->query("SET CHARACTER SET utf8");
    $connection->query("SET NAMES utf8");

    /* check connection */
    if (mysqli_connect_errno()) {
        $error = 0;
    } else {
        
        mysqli_query($connection, "UPDATE settings SET version = '2.8' WHERE id = 1;");

        
        mysqli_query($connection, "INSERT INTO `features` (`id`, `name`, `slug`, `is_limit`, `basic`, `standared`, `premium`, `plus`) VALUES (NULL, 'Products', 'products', '1', '10', '100', '200', '-1');");

        mysqli_query($connection, "ALTER TABLE `customers` ADD `address` VARCHAR(255) NULL AFTER `created_at`, ADD `shipping_address` VARCHAR(255) NULL AFTER `address`;");

        mysqli_query($connection, "ALTER TABLE `business` ADD `enable_product` INT(2) NULL DEFAULT '0' AFTER `enable_event`;");

        mysqli_query($connection, "ALTER TABLE `settings` ADD `card_fee` INT NULL DEFAULT '0' AFTER `enable_default_tzone`;");

        mysqli_query($connection, "ALTER TABLE `settings` ADD `confirm_notify` INT NULL DEFAULT '0' AFTER `card_fee`;");

        mysqli_query($connection, "ALTER TABLE `business` ADD `card_fee` INT NULL DEFAULT '0' AFTER `disabled_customers`;");


        // import database table
        $query = '';
          $sqlScript = file('sql/products-all.sql');
          foreach ($sqlScript as $line) {
            
            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);
            
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
              continue;
            }
              
            $query = $query . $line;
            if ($endWith == ';') {
              mysqli_query($connection, $query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
              $query= '';   
            }
        }

        
        mysqli_query($connection, "INSERT INTO `lang_values` (`type`, `label`, `keyword`, `english`) VALUES
        ('user', 'Card Processing Fee', 'card-processing-fee', 'Card Processing Fee'),
        ('user', 'Set 0 to disable this option', 'set-0-to-disable-this-option', 'Set 0 to disable this option'),
        ('user', 'Card Processing Fee', 'card-processing-fee', 'Card Processing Fee'),
        ('user', 'Set 0 to disable this option', 'set-0-to-disable-this-option', 'Set 0 to disable this option'),
        ('user', 'Proucts', 'proucts', 'Proucts'),
        ('user', 'Products', 'products', 'Products'),
        ('user', 'Subcategories', 'subcategories', 'Subcategories'),
        ('user', 'Orders', 'orders', 'Orders'),
        ('user', 'Cart Total', 'cart-total', 'Cart Total'),
        ('user', 'Checkout', 'checkout', 'Checkout'),
        ('user', 'Cash On Delivery', 'cash-on-delivery', 'Cash On Delivery'),
        ('user', 'Online Payment', 'online-payment', 'Online Payment'),
        ('user', 'Shipping Address', 'shipping-address', 'Shipping Address'),
        ('user', 'View cart', 'view-cart', 'View cart'),
        ('user', 'Your Shopping cart is empty !', 'your-shopping-cart-is-empty', 'Your Shopping cart is empty !'),
        ('user', 'Product', 'product', 'Product'),
        ('user', 'Shoping Cart', 'shoping-cart', 'Shoping Cart'),
        ('user', 'Shopping Cart', 'shopping-cart', 'Shopping Cart'),
        ('user', 'Items', 'items', 'Items'),
        ('user', 'Qty', 'qty', 'Qty'),
        ('user', 'Clear Cart', 'clear-cart', 'Clear Cart'),
        ('user', 'Update Cart', 'update-cart', 'Update Cart'),
        ('user', 'Creat New Account', 'creat-new-account', 'Creat New Account'),
        ('user', 'Create New Account', 'create-new-account', 'Create New Account'),
        ('user', 'User name', 'user-name', 'User name'),
        ('user', 'You are already signed in you just go through with the checkout. ', 'already-signed-in-msg', 'You are already signed in you just go through with the checkout. '),
        ('user', 'Add to cart', 'add-to-cart', 'Add to cart'),
        ('user', 'Stock out', 'stock-out', 'Stock out'),
        ('user', 'Subcategory', 'subcategory', 'Subcategory'),
        ('user', 'Sort Price', 'sort-price', 'Sort Price'),
        ('user', 'Low To high', 'low-to-high', 'Low To high'),
        ('user', 'High To low', 'high-to-low', 'High To low'),
        ('user', 'Short Description', 'short_desc', 'Short Description'),
        ('user', 'Attributes', 'attributes', 'Attributes'),
        ('user', 'Meta Description', 'meta-desc', 'Meta Description'),
        ('user', 'Limited Stock', 'limited-stock', 'Limited Stock'),
        ('user', 'Order Status', 'order-status', 'Order Status'),
        ('user', 'Delivered', 'delivered', 'Delivered'),
        ('user', 'Order Date', 'order-date', 'Order Date'),
        ('user', 'Confirm Order', 'confirm-order', 'Confirm Order'),
        ('user', 'Cancel order', 'cancel-order', 'Cancel order'),
        ('user', 'Order delivered', 'order-delivered', 'Order delivered'),
        ('user', 'Confirm', 'confirm', 'Confirm'),
        ('user', 'Order Info', 'order-info', 'Order Info'),
        ('user', 'Total Items', 'total-item', 'Total Items');");

            
      /* close connection */
      mysqli_close($connection);

      $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
      $redir .= "://" . $_SERVER['HTTP_HOST'];
      $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
      $redir = str_replace('updates/v2.8/', '', $redir);
      header("refresh:5;url=" . $redir);
      $success = 1;
    }



}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aoxio &bull; Update Installer</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/libs/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,500,600,700&display=swap" rel="stylesheet">
    <script src="assets/js/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-md-offset-2">

                <div class="row">
                    <div class="col-sm-12 logo-cnt">
                        <p>
                           <img src="assets/img/logo.png" alt="">
                       </p>
                       <h1>Welcome to the Update Installer</h1>
                   </div>
               </div>

               <div class="row">
                <div class="col-sm-12">

                    <div class="install-box">

                        <div class="steps">
                            <div class="step-progress">
                                <div class="step-progress-line" data-now-value="100" data-number-of-steps="3" style="width: 100%;"></div>
                            </div>
                            <div class="step" style="width: 50%">
                                <div class="step-icon"><i class="fa fa-arrow-circle-right"></i></div>
                                <p>Start</p>
                            </div>
                            <div class="step active" style="width: 50%">
                                <div class="step-icon"><i class="fa fa-database"></i></div>
                                <p>Database</p>
                            </div>
                        </div>

                        <div class="messages">
                            <?php if (isset($message)) { ?>
                            <div class="alert alert-danger">
                                <strong><?php echo htmlspecialchars($message); ?></strong>
                            </div>
                            <?php } ?>
                            <?php if (isset($success)) { ?>
                            <div class="alert alert-success">
                                <strong>Completing Updates ... <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Please wait 5 second </strong>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="step-contents">
                            <div class="tab-1">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                    <div class="tab-content">
                                        <div class="tab_1">
                                            <h1 class="step-title">Database</h1>
                                            <div class="form-group">
                                                <label for="email">Host</label>
                                                <input type="text" class="form-control form-input" name="db_host" placeholder="Host"
                                                value="<?php echo isset($_SESSION["db_host"]) ? $_SESSION["db_host"] : 'localhost'; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Database Name</label>
                                                <input type="text" class="form-control form-input" name="db_name" placeholder="Database Name" value="<?php echo @$_SESSION["db_name"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Username</label>
                                                <input type="text" class="form-control form-input" name="db_user" placeholder="Username" value="<?php echo @$_SESSION["db_user"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Password</label>
                                                <input type="password" class="form-control form-input" name="db_password" placeholder="Password" value="<?php echo @$_SESSION["db_password"]; ?>">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="buttons">
                                        <a href="index.php" class="btn btn-success btn-custom pull-left">Prev</a>
                                        <button type="submit" name="btn_admin" class="btn btn-success btn-custom pull-right">Finish</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </div>


    </div>


</div>

<?php

unset($_SESSION["error"]);
unset($_SESSION["success"]);

?>

</body>
</html>

