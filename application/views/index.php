<?php if (isset($page) && $page == "Front" && settings()->home_layout == 3): ?>

  <?php include'include/header3.php';?>
    <?php echo $main_content;?>
  <?php include'include/footer3.php';?>

<?php else: ?>

  <?php include'include/header.php';?>
    <?php echo $main_content;?>
  <?php include'include/footer.php';?>
  
<?php endif ?>