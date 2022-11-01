<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uploadDir = '/home/william/Public/uploads/';
    $authorizedExtensions = ['jpg', 'png', 'gif', 'webp'];
    $maxFileSize = 1000000;
    $errors = [];
    $success = [];
    $failed = [];

    if (!empty($_FILES['avatar'])) {
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

        if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
            $errors[] = 'Votre fichier doit être inférieur à ' . $maxFileSize / 1000000 . 'Mo.';
        }

        if ((!in_array($extension, $authorizedExtensions))) {
            $authorizedExtensionsString = implode(', ', $authorizedExtensions);
            $errors[] = 'Veuillez sélectionner une image de type ' . $authorizedExtensionsString . '.';
        }

        if (!empty($_FILES['error'])) {
            $errors[] = 'Erreur d \'upload : ' . $_FILES['error'] . '.';
        }

        if (empty($errors)) {
            $uniqName = uniqid('', true) . '.' . $extension;
            $uploadFile = $uploadDir . $uniqName;
            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ça y est, le fichier est uploadé
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                $success[] = $_FILES['avatar']['name'];
            } else {
                $failed[] = $_FILES['avatar']['name'];
            }
        }
    } else {
        $errors[] = 'Aucun fichier séléctionné.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload quest</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <label for="imageUpload">Upload an profile image</label>
        <input type="file" name="avatar" id="imageUpload" />
        <button name="send">Send</button>
    </form>
    <?php if (isset($errors) && !empty($errors)) : ?>
        <ul>Liste des erreurs
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <?php if (isset($failed) && !empty($failed)) : ?>
        <ul>Liste des upload échoué
            <?php foreach ($failed as $fail) : ?>
                <li><?= $fail ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <?php if (isset($success) && !empty($success)) : ?>
        <ul>Liste des upload réussi
            <?php foreach ($success as $succes) : ?>
                <li><?= $succes ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

</body>

</html>