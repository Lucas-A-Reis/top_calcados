<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sanitizarDados($data)
{
    if (is_array($data)) {
        return array_map('sanitizarDados', $data);
    }
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
}

    function sanitizar($valor, $tipo)
    {
        switch ($tipo) {
            case 'string':
                return filter_var($valor, FILTER_SANITIZE_SPECIAL_CHARS);

            case 'email':
                return filter_var($valor, FILTER_SANITIZE_EMAIL);

            case 'telefone':
                return preg_replace('/[^0-9]/', '', $valor);

            case 'float':
                return filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            case 'int':
                return filter_var($valor, FILTER_SANITIZE_NUMBER_INT);

            case 'none':
                return $valor;

            default:
                return filter_var($valor, FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }

function validarCaptcha()
{
    $token = $_POST['g-recaptcha-response'];
    $front = $_GET['front'];
    if (empty($token)) {
        header("Location: $front?captcha_vazio=1");
        exit();
    }
    $chave = $_ENV['RECAPTCHA_SECRET_KEY'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$chave&response=$token";
    $resposta = file_get_contents($url);
    $atributos = json_decode($resposta, true);
    if (!$atributos['success']) {
        header("Location: $front?erro_captcha=1");
        exit();
    }
}

function enviarEmail($destinatario, $assunto, $corpoHtml) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;

        $mail->send();
        return true;
    } catch (Exception $e) {
    echo "Erro do PHPMailer: " . $mail->ErrorInfo; 
    die();
    return false;
}
}

function gerarSlug($texto) {

    $slug = mb_strtolower(trim(string: $texto), 'UTF-8');

    $substituicoes = [
        'a' => ['á', 'à', 'â', 'ã', 'ä'],
        'e' => ['é', 'è', 'ê', 'ë'],
        'i' => ['í', 'ì', 'î', 'ï'],
        'o' => ['ó', 'ò', 'ô', 'õ', 'ö'],
        'u' => ['ú', 'ù', 'û', 'ü'],
        'c' => ['ç'],
    ];

    foreach ($substituicoes as $semAcento => $comAcento) {
        $slug = str_replace($comAcento, $semAcento, $slug);
    }

    $slug = preg_replace('/[^a-z0-9]/', '-', $slug);

    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    return $slug;
}

function garantirSlugUnico(PDO $pdo, $slugOriginal) {
    $slugFinal = $slugOriginal;
    $contador = 1;

    while (true) {
        $sql = "SELECT COUNT(*) FROM modelos_calcado WHERE slug = :slug";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':slug' => $slugFinal]);
        $existe = $stmt->fetchColumn();

        if (!$existe) {
            break;
        }

        $slugFinal = $slugOriginal . '-' . $contador;
        $contador++;
    }

    return $slugFinal;
}

function temPalavraEmComum(string $string, string $string2): bool {

    $palavras = explode(' ', $string);
    $palavras2 = explode(' ', $string2);

    $comuns = array_intersect($palavras, $palavras2);

    return count($comuns) > 0;
}