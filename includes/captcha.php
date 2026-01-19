<div style="margin-bottom: 10px;" class="g-recaptcha" data-sitekey="6Lc5y00sAAAAAMd6mkoAdncVd3Tihd1SYT8VGgnV"></div>

<?php if (isset($_GET['captcha_vazio'])): ?>
    <div class="alerta-erro">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-circle-x-icon lucide-circle-x">
            <circle cx="12" cy="12" r="10" />
            <path d="m15 9-6 6" />
            <path d="m9 9 6 6" />
        </svg>
        Complete o captcha antes de prosseguir.
    </div>
<?php endif; ?>
<?php if (isset($_GET['erro_captcha'])): ?>
    <div class="alerta-erro">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-circle-x-icon lucide-circle-x">
            <circle cx="12" cy="12" r="10" />
            <path d="m15 9-6 6" />
            <path d="m9 9 6 6" />
        </svg>
        Houve um erro ao verificar o captcha, tente novamente.
    </div>
<?php endif; ?>