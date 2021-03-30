<?php

if (!empty($_FILES['files']['name'][0])) {
    
    $files = $_FILES['files'];

    $uploaded = [];
    $failed = [];
    $allowed = ['txt', 'jpg', 'pdf'];

    foreach ($files['name'] as $position => $fileName) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $fileName);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size <= 2097152) {
                    $fileNameNew = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $fileNameNew;

                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "[$fileName] failed to upload";
                    }
                } else {
                    $failed[$position] = "[$fileName] is too large.";
                }
            } else {
                $failed[$position] = "[$fileName] failed to uploaded - $file_error";
            }   
        } else {
            $failed[$position] = "[$fileName] file extension '$file_ext' is not allowed";
        }
    }

    if (!empty($uploaded)) {
        var_dump($uploaded);
    }

    if (!empty($failed)) {
        var_dump($failed);
    }

    header("Location: index.php");

}