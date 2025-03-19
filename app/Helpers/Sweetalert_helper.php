<?php

function sweetToast($type, $message, $title = '') {
    // Define as opções de cores para cada tipo de mensagem
    $sweetTypes = [
        'success' => 'success',
        'info' => 'info',
        'error' => 'error',
        'warning' => 'warning',
        'question' => 'question'
    ];

    // Verifica se o tipo de mensagem é válido
    if (!array_key_exists($type, $sweetTypes)) {
        throw new Exception("Tipo de mensagem inválido: $type");
    }

    $positionClass = $type == 'error' ? 'center' : 'top-end';

    $script = "<script>
                    const Toast = Swal.mixin({
                    toast: true,
                    position: '$positionClass',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    padding: '.5em',
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                    });

                    Toast.fire({
                        icon: '$type',
                        title: '$title',
                        text: '$message'
                    });
                </script>";

    // Retorna o script gerado
    echo $script;
}

function confirmDelete(string $tableName, string $buttonName, string $title = '', string $message = '', string $route = '') {

    $script = "
        <script>
            $(document).ready(() => {
                var table = new DataTable('#$tableName');
                table.on('click','.$buttonName', function () {
                    var route = $(this).data('router');

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            title: 'fs-5',
                            text: 'fs-6',
                            icon: 'fa-sm p-1',
                            validationMessage: 'h5 text-primary',
                            confirmButton: 'btn btn-sm btn-outline-danger    btn-flat shadow-sm ml-2',
                            cancelButton:  'btn btn-sm btn-outline-secondary btn-flat shadow-sm ml-2'
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        padding: '.5em',
                        title: '$title',
                        text: '$message',
                        icon: 'question',
                        padding: '.5em', 
                        showCancelButton: true,
                        confirmButtonText: `<i class='fas fa-trash mr-1'></i>Sim, excluir`,
                        cancelButtonText:  `<i class='fas fa-ban   mr-1'></i>Não, retornar`,
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: {'X-Requested-With': 'XMLHttpRequest'},
                                type: 'GET',
                                dataType: 'text',
                                url: route,
                                success: function(response) {
                                    location.reload();
                                },
                                error: function(response) {
                                    console.log(response);
                                },
                            });
                        }
                    });

                })

            });

        </script>";

    echo $script; 
    
}

    

