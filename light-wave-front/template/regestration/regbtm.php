<?php
$url = 'http://www.example.org/api/login';

$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(
            array(
                'username' => 'mlsdmitry',
                'password' => 'Vjkjrjdbx',
                )
            ),
            'timeout' => 60
    )
));

$resp = file_get_contents($url, FALSE, $context);
?>