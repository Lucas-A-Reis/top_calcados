<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
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
                                fetch("/processar_pagamento.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify(formData),
                                })
                                    .then((response) => response.json())
                                    .then((result) => {
                                        alert("Resultado: " + result.status);
                                        resolve();
                                    })
                                    .catch((error) => {
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