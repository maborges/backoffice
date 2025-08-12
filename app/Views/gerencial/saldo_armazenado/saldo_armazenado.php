<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?= form_open("#", ['novalidate' => true]) ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('saldo-armazenado/new') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <div class="card-body mt-1 pt-1">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="produto">Produto</label>
                                    <select id="produto" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        <!-- Opções de produtos serão carregadas dinamicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filial">Filial</label>
                                    <select id="filial" class="form-control form-control-sm">
                                        <option value="">Todas</option>
                                        <!-- Opções de filiais serão carregadas dinamicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <table class="display compact" id="tblSaldoArmazenado" style="width: 100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Código</th>
                                        <th class="text-center">Produto</th>
                                        <th class="text-center">Filial</th>
                                        <th class="text-center">Data do Saldo</th>
                                        <th class="text-center">Saldo</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= form_close() ?>

</section>

<!-- Mensagens -->
<?php
if (!empty($server_success)) {
    sweetToast('success', $server_success, $title);
} elseif (!empty($server_warning)) {
    sweetToast('warning', $server_warning, $title);
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>

<!-- Datatables de Saldo Armazenado -->
<script>
    $(document).ready(() => {
        // Carregar opções de produtos
        $.ajax({
            url: "<?= base_url('cadastro/produto_locate') ?>",
            type: "GET",
            success: function(data) {
                if (data && data.length > 0) {
                    $.each(data, function(i, item) {
                        $('#produto').append($('<option>', {
                            value: item.codigo,
                            text: item.descricao
                        }));
                    });
                }
            }
        });

        // Carregar opções de filiais
        $.ajax({
            url: "<?= base_url('cadastro/filial_locate') ?>",
            type: "GET",
            success: function(data) {
                if (data && data.length > 0) {
                    $.each(data, function(i, item) {
                        $('#filial').append($('<option>', {
                            value: item.codigo,
                            text: item.descricao
                        }));
                    });
                }
            }
        });

        // Inicializar DataTable
        var dataTable = $('#tblSaldoArmazenado').DataTable({
            caption: '',
            deferRender: true,
            fixedHeader: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: '55vh',
            language: {
                url: "<?= base_url(DATATABLES_PT_BR) ?>"
            },
            layout: {
                topStart: ['buttons', 'pageLength'],
                topEnd: ['search']
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="fa-solid fa-copy"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'

                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-solid fa-file-csv"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                }
            ],
            ajax: {
                url: "<?= base_url('saldo-armazenado/busca') ?>",
                type: "POST",
                data: function(d) {
                    d.produto = $('#produto').val();
                    d.filial = $('#filial').val();
                },
                dataSrc: function(json) {
                    if (!json.hasOwnProperty('data')) {
                        console.error('Resposta JSON inválida', json);
                        return [];
                    }
                    return json.data;
                },
                error: function(xhr, status, error) {
                    var responseJSON = JSON.parse(xhr.responseText);
                    toastr.error(responseJSON.message, 'Erro', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '5000',
                        extendedTimeOut: '2000',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut'
                    });

                    $('#tblSaldoArmazenado_processing').hide();
                    $('#tblSaldoArmazenado').DataTable().clear().draw();
                }
            },
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'codigo',
                    visible: false
                },
                {
                    data: 'nome_produto'
                },
                {
                    data: 'nome_filial'
                },
                {
                    data: 'data_saldo',
                    render: function(data) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'saldo',
                    className: 'text-right',
                    render: function(data) {
                        return parseFloat(data).toLocaleString('pt-BR', {
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 3
                        });
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href='<?= site_url('saldo-armazenado/edit/') ?>" + row.codigo + "'><i class='fa-regular fa-pen-to-square shadow-sm'></i></a>" +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiSaldo' data-router='<?= site_url('saldo-armazenado/delete/') ?>" + row.codigo + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";


                    }
                }
            ]
        });

        // Event handlers para filtros
        $('#produto, #filial').on('change', function() {
            dataTable.ajax.reload();
        });
    });
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblSaldoArmazenado',
    buttonName: 'btnExcluiSaldo',
    title: 'Exclusão de Saldo',
    message: 'Confirma exclusão do saldo?',
    route: '/saldo-armazenado/delete/'
) ?>

<?= $this->endSection() ?>