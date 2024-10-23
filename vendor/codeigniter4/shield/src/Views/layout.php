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
        body {
            background-image: url('http://localhost/crud/public/doctor.jpg'); /* Path to your image */
            background-size: cover; /* Ensures the image covers the entire body */
            background-position: center; /* Centers the image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            height: 100vh; /* Full height of the viewport */
            margin: 0; /* Remove default margin */
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

<body class="bg-light">

    <main role="main" class="container">
        <?= $this->renderSection('main') ?>
    </main>

<?= $this->renderSection('pageScripts') ?>
</body>
</html>
