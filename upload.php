<?php

/*
 * Función personalizada para comprimir y
 * subir una imagen mediante PHP
 */

function compressImage($source, $destination, $quality) {
    // Obtenemos la información de la imagen
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Creamos una imagen
    switch($mime){
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Guardamos la imagen
    imagejpeg($image, $destination, $quality);

    // Devolvemos la imagen comprimida
    return $destination;
}


// Ruta subida
$uploadPath = "uploads/";

// Si el fichero se ha enviado
$status = $statusMsg = '';
if(isset($_POST["submit"])){
    $status = 'error';
    if(!empty($_FILES["image"]["name"])) {
        // File info
        $fileName = basename($_FILES["image"]["name"]);
        $imageUploadPath = $uploadPath . $fileName;
        $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);

        // Permitimos solo unas extensiones
        $allowTypes = array('jpg','png','jpeg','gif');
        if(in_array($fileType, $allowTypes)){
            // Image temp source
            $imageTemp = $_FILES["image"]["tmp_name"];

            // Comprimos el fichero
            $compressedImage = compressImage($imageTemp, $imageUploadPath, 25);

            if($compressedImage){
                $status = 'success';
                $statusMsg = "La imagen se ha subido satisfactoriamente.";
            }else{
                $statusMsg = "La compresion de la imagen ha fallado";
            }
        }else{
            $statusMsg = 'Lo sentimos, solo se permiten imágenes con estas extensiones: JPG, JPEG, PNG, & GIF.';
        }
    }else{
        $statusMsg = 'Por favor, selecciona una imagen.';
    }
}

// Mostrar el estado de la imagen
echo $statusMsg;

echo '<a href="index.html">Volver</a>';
