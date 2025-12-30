<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <title>Top Calçados</title>
</head>

<body>

    <body>
        <h1>Finalizar Compra - Top Calçados</h1>

        <div id="paymentBrick_container"></div>

        <script>

            const mp = new MercadoPago('TEST-7e03c1e8-220e-40ad-b645-6e871e63d8e3');
            const bricksBuilder = mp.bricks();

            const renderPaymentBrick = async (bricksBuilder) => {
                const settings = {
                    initialization: {
                        amount: 100.00,
                        preferenceId: null,
                        payer: {
                            email: "teste@cliente.com",
                        },
                    },
                    customization: {
                        paymentMethods: {
                            ticket: "all",
                            bankTransfer: "all",
                            creditCard: "all",
                            debitCard: "all",
                        },
                    },
                    callbacks: {
                        onReady: () => {
                            console.log("Formulário pronto!");
                        },
                        onSubmit: ({ selectedPaymentMethod, formData }) => {
                            return new Promise((resolve, reject) => {
                                fetch("processar_pagamento.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify(formData),
                                })
                                    .then((response) => response.json())
                                    .then((result) => {
                                        if (result.status === 'approved') {
                                            Swal.fire('Sucesso!', 'Pagamento aprovado!', 'success');
                                        } else if (result.status === 'pending') {
                                            if (result.qr_code_base64) {
                                                Swal.fire({
                                                    title: 'Pague com Pix',
                                                    html: `<p>Aponte a câmera para o QR Code:</p>
                                                    <img src="data:image/png;base64,${result.qr_code_base64}" style="width:200px">
                                                    <br><br>
                                                    <p>Ou copie o código:</p>
                                                    <input type="text" value="${result.qr_code}" readonly style="width:100%">`,
                                                    icon: 'info'
                                                });
                                            } else {
                                                Swal.fire('Pendente', 'Aguardando pagamento do boleto.', 'info');
                                            }
                                        }
                                    })
                                resolve();
                            })
                                .catch((error) => {
                                    Swal.fire({
                                        title: 'Erro de Conexão',
                                        text: 'Não conseguimos falar com o servidor.',
                                        icon: 'error'
                                    });
                                    reject();
                                });
                        });
                },
                    onError: (error) => {
                            console.error(error);
            },
                    },
                };
            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
            );
            };
            renderPaymentBrick(bricksBuilder);
        </script>
    </body>

</html>
</body>

</html>