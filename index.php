<?php

if(isset($_POST['submit'])) {
    if(count($_FILES['file']['name'])> 0) {
        for($i = 0; $i < count($_FILES['file']['name']); $i++) {
            if(filesize($_FILES['file']['tmp_name'][$i]) < 1000000 &&
                (
                        $_FILES['file']['type'][$i] == 'image/png' ||
                        $_FILES['file']['type'][$i] == 'image/gif' ||
                        $_FILES['file']['type'][$i] == 'image/jpeg'
                )
            ) {
                $tmpFilePath = $_FILES['file']['tmp_name'][$i];
                if ($tmpFilePath != "") {
                    $extension = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extension;
                    $filePath = "uploaded/image" . $filename;
                    move_uploaded_file($tmpFilePath, $filePath);
                }
            } else {
                $errors[] = "Impossible d'envoyer le fichier " . $_FILES['file']['name'][$i];
            }
        }
    }
}

if(isset($_GET['file'])) {
    $filePath = 'uploaded/';

    if(file_exists($filePath . $_GET['file'])) {
        delete($filePath . $_GET['file']);
    }
}

function delete(string $filePath)
{
    unlink($filePath);
    header('location:index.php');

}

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS & JS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Galerie d'images</h1>
        <div class="jumbotron">
            <form action="" method="post" enctype="multipart/form-data">
               <?php if(isset($errors)) { ?>
                <div class="well alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error) {?>
                        <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="file">Fichier(s)</label>
                    <input type="file" class="form-control" name="file[]" id="file" multiple="multiple" placeholder="">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <?php $i = new FilesystemIterator('uploaded');
            foreach ($i as $fileinfo) { ?>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="thumbnail">
                    <img src="uploaded/<?= $fileinfo->getFilename() ?>" alt="">
                    <div class="caption">
                        <p><?= $fileinfo->getFilename() ?></p>
                        <p><a href="index.php?file=<?= $fileinfo->getFilename() ?>" class="btn btn-primary" role="button">Supprimer</a></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
