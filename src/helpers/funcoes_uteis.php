<?php

function sanitizarDados($data) {
    if (is_array($data)) {
        return array_map('sanitizarDados', $data);
    }
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}