<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $this->renderSection('title') ?></title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <?= $this->renderSection('pageStyles') ?>




    <style>
        

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevents horizontal scrolling */
            position: relative; /* Ensure proper stacking context */
        }

        #particles-js {
            position: fixed; /* Fix particles to the viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2; /* Keeps particles behind all other content */
        }

        body {
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url("http://localhost/crud/public/doctor.jpg");
            background-size: cover;
            background-position: center;
            position: relative; /* Ensure body content layers above particles */
            z-index: 0; /* Ensure body content stays above particles */
        }

        .content-container {
            position: relative; /* Keeps your table content in front of the particles */
            z-index: 1; /* Ensures table appears above the particles */
        }

        .card-body {
            /* background-image: url('http://localhost/crud/public/dental.jpg'); Path to your image */
            background-size: cover; /* Ensures the image covers the entire body */
            background-position: center; /* Centers the image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            /* height: 100vh; Full height of the viewport */
            margin: 0; /* Remove default margin */
            background-color: #F5EFFF;
        }
    </style>
</head>

<div id = "particles-js"></div>
<body class="bg-light">


    <main role="main" class="container">
        <?= $this->renderSection('main') ?>
    </main>

<?= $this->renderSection('pageScripts') ?>
</body>
</html>
