<?php

function toastMessage($type, $message, $title = '') {
    // Define as opções de cores para cada tipo de mensagem
    $toastrTypes = [
        'success' => 'success',
        'info' => 'info',
        'error' => 'error',
        'warning' => 'warning',
        'question' => 'question'
    ];

    // Verifica se o tipo de mensagem é válido
    if (!array_key_exists($type, $toastrTypes)) {
        throw new Exception("Tipo de mensagem inválido: $type");
    }

    $positionClass = $type == 'error' ? 'toast-bottom-full-width' : 'toast-top-right';

    // Gera o código JavaScript para a mensagem
    $script = "<script>
        toastr.{$toastrTypes[$type]}('{$message}', '{$title}', {
            closeButton: true,
            progressBar: true,
            positionClass: '$positionClass',
            timeOut: '5000',
            extendedTimeOut: '2000',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        });
    </script>";

    // Retorna o script gerado
    echo $script;
}
