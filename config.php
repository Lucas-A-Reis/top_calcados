<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
MercadoPago\MercadoPagoConfig::setAccessToken($_ENV['MP_ACCESS_TOKEN']);