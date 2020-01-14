<?php

function getResponse($status, $message = null, $data = null)
{
    $response = [
        'status' => $status,
    ];

    if ($status == 'ok' && $data != null)
        $response['data'] = $data;

    if ($status == 'error' && $message != null)
        $response['messageError'] = $message;

    return $response;
}

function getDataURI($imagePath)
{
    $type = pathinfo($imagePath, PATHINFO_EXTENSION);
    $data = file_get_contents($imagePath);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}
