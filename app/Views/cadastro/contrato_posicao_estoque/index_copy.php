<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('/cadastro/contrato_posicao_estoque_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <div class="card-body mt-1 pt-1">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="produto">Produto</label>
                                    <select id="produto" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        <!-- Opções de produtos seriam carregadas dinamicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filial">Filial</label>
                                    <select id="filial" class="form-control form-control-sm">
                                        <option value="">Todas</option>
                                        <!-- Opções de filiais seriam carregadas dinamicamente -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <table class="display compact" id="tblContrato" style="width: 100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Código</th>
                                        <th class="text-center">Contrato</th>
                                        <th class="text-center">Data Referencia</th>
                                        <th class="text-center">Filial</th>
                                        <th class="text-center">Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

<!-- Datatables de Contratos -->
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
        var dataTable = $('#tblContrato').DataTable({
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
                    text: '<i class="fa-solid fa-copy"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-solid fa-file-csv"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                }
            ],
            initComplete: function() {
                $('.dt-input').addClass('form-control-sm '); // Adiciona classes Bootstrap a todos os botões
            },
            ajax: {
                url: "<?= base_url('cadastro/contrato_posicao_estoque_busca') ?>",
                type: "POST",
                data: function(d) {
                    d.produto = $('#produto').val();
                    d.filial = $('#filial').val();
                },
                dataSrc: function(json) {
                    console.log(json);
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

                    $('#tblContrato_processing').hide();
                    $('#tblContrato').DataTable().clear().draw();
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
                    data: 'numero_contrato'
                },
                {
                    data: 'data_referencia',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'nome_filial'
                },
                {
                    data: 'nome_produto'
                },
                {
                    data: 'quantidade'
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href='<?= site_url('/cadastro/contrato_posicao_estoque_edita/') ?>" + data.codigo + "'><i class='fa-regular fa-pen-to-square shadow-sm'></i></a>" +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiContrato' data-router='/cadastro/contrato_posicao_estoque_exclui/" + data.codigo + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";
                    }
                }
            ]
        });

        // Adicionar evento de mudança nos filtros para recarregar a tabela
        $('#produto, #filial').change(function() {
            dataTable.ajax.reload();
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblContrato',
    buttonName: 'btnExcluiContrato',
    title: 'Exclusão de Contrato',
    message: 'Confirma exclusão do contrato?',
    route: '/cadastro/contrato_posicao_estoque_exclui/'
) ?>

<?= $this->endSection() ?>