<?php
require_once '../vendor/autoload.php';

if (class_exists('MercadoPago\MercadoPagoConfig')) {
    echo "A classe foi encontrada com sucesso!";
} else {
    echo "A classe NÃO foi encontrada. Verifique a pasta vendor.";
}