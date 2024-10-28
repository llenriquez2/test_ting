<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>



<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script> -->




    <style>
        body {
            background-image: url('http://localhost/crud/public/background1.jpg'); /* Path to your image */
            background-size: contain; /* Ensures the image covers the entire body */
            background-position: center;
             /* Prevents the image from repeating */
            height: 100vh; /* Full height of the viewport */
            margin: 0; /* Remove default margin */
        }

        .navbar {
          
            background-color: transparent;

        }

        .table {
            background-color: white; /* Ensures the table has a white background */
        }

         /* Remove background from the search input */
        .dataTables_filter input {
          width: 300px;
          border: 2px solid black !important; 
          background-color: white !important;
          /* border: black !important; */
          box-shadow: none !important;
        }

        .dataTables_filter label {
            color: white; /* Change text color to white */
        }

       

        /* Remove background from the pagination buttons */
        .dataTables_paginate .paginate_button {
          background-color: white !important;
          border: none !important;
        }

        /* Remove background from the page length dropdown */
        .dataTables_length select {
          background-color: white !important;
          border: none !important;
          box-shadow: none !important;
        }

       
        /* Center the dropdown button to the top center of the cell */
        .dropdown-container {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to the top of the cell */
            height: 100%;
        }

        .dropdown-menu {
            top: 0; /* Position dropdown menu at the top */
            transform: translateY(-100%); /* Adjust to display above the button */
        }


        .dataTables_length label {
          color: white; /* Text color */
        }

        .dataTables_info {
          color: white !important;
        }
                
      

        /* Center align headers */
        #inventoryTable th {
            text-align: center;
            vertical-align: middle;
        }


        #inventoryTable tbody td {
           
            word-wrap: break-word; /* Wraps long words */
            white-space: normal; /* Allows text to break into multiple lines */
            overflow: visible; /* Ensures the whole content is visible */
            text-align: left !important; /* Force the centering */

        }

        #inventoryTable {
        width: 100%; /* Set to 100% to ensure the table uses the full width */
        table-layout: fixed; /* Use fixed layout to respect column widths */
       
        }

        #inventoryTable td {
            white-space: pre-wrap; /* Allows for line breaks to be rendered */
        }

       

                      


    </style>
</head>

<!-- navbar-light bg-light -->
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <!-- <a class="navbar-brand" href="<?= url_to("/") ?>">Navbar</a> -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= url_to("/") ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <?php if (auth()->loggedIn()): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= url_to('inventory') ?>">Inventory</a>
        </li>
        <?php endif; ?>
      </ul>

      <!-- Move the following items to the right using ms-auto -->
      <ul class="navbar-nav ms-auto">
        

        <?php if (auth()->loggedIn()): ?>
       
        

        <!-- Uncomment if you want to add admin group-based navigation -->
        <!-- <?php if (auth()->user()->inGroup('admin')): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= url_to('admin') ?>">Admin Dashboard</a>
        </li>
        <?php endif; ?> -->
        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= url_to('login') ?>">Login</a>
        </li>
        <?php endif; ?>

        <li class="nav-item dropdown">
          <?php if (auth()->loggedIn()): ?>
          
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
             
         Hello, <?= esc(auth()->user()->username) ?>
             
          </a>
          <?php endif; ?>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <?php if (auth()->loggedIn()): ?>
            <li><a class="dropdown-item" href="<?= url_to('logout') ?>">Logout</a></li>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid"><br/><br/>



