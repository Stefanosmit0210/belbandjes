<!DOCTYPE html>
<html lang="en">

<?php
require_once("../backend/conn.php");

?>
<head>
    <!-- hier komt al je meta data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- hier komt je titel -->
    <title>Bel bandjes support</title>

    <!-- hier onder is de plek voor al je linkjes die te maken hebben met styling -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../js/jquery.steps.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- jquery validation -->
    <script src="<?php echo($base_url);?>/js/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://kit.fontawesome.com/0e33b66e0d.js" crossorigin="anonymous"></script>
    
     
    <script src="../js/jquery.steps.js"></script>
</head>
    <body>
        <div class="col-3 header-spacing-r logo-wrapper">
            <div class="verticle-align-logo">
                <a class="logo-wrapper" href="<?php echo $base_url; ?>/index.php">
                    <div class="logo-wrapper">
                        <img src="<?php echo $base_url; ?>/img/Logo-BSolutions.svg" alt="Logo">
                    </div>
                </a>
            </div>
        </div>
            <main class="d-flex align-items-center justify-content-center succesMsg">
                <div class="">
                    <section class="container-fluid step3 d-flex justify-content-center align-items-center">
                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    <div class="center-text">
                                        <h1 class="size-4">Dankjewel!</h1>
                                            <p class="center-text">Het bandje is verzonden en wij verwerken je verzoek zo snel mogelijk!</p>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>
            </main>
        <img class="minibasje fixed-bottom" src="<?php echo $base_url;?>/img/MiniBasje.svg" alt="">
    </body>
</html>
