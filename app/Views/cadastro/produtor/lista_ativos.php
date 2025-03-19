<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?= form_open("#", ['novalidate' => true]) ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">

                    <div class="card-header d-flex">
                        <div class="row col-md-12">
                            <div class="form-group col-2">
                                <label for="startDate">Data Inicial</label>
                                <input type="date" class="form-control form-control-sm" name="startDate" id="startDate"
                                    value="<?= $startDate ?>">
                            </div>

                            <div class="form-group col-2">
                                <label for="endDate">Data Final</label>
                                <input type="date" class="form-control form-control-sm" name="endDate" id="endDate"
                                    value="<?= $endDate ?>">
                            </div>

                            <div class="form-group ms-3 col-2 d-flex align-items-end">
                                <a id="btnPesquisa" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i>Pesquisar</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body mt-0">
                        <?php if (empty($produtores)) : ?>
                            <div class="text-center mt-5 mb-3">
                                <h4 class="opacity-50 mb-3">Nenhum produtor ativo no período informado.</h4>
                            </div>
                        <?php else : ?>
                            <div class="card-body mt-1 pt-1 ">
                                <div class="row">
                                    <table class="display compact" id="tblProdutores" style="width: 100%; height: auto">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produtor</th>
                                                <th>Produto(s)</th>
                                                <th>Local</th>
                                                <th>Sankhya</th>
                                                <th>Região</th>
                                                <th>Categoria</th>
                                                <th>Cadastro</th>
                                                <th>SERASA</th>
                                                <th>Embargado</th>
                                                <th>Total Pedidos</th>
                                                <th>Valor Pedidos</th>
                                                <th>Última Compra</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</section>

<!--
    Mensagens de erro, aviso e sucesso 
-->
<?php
if (!empty($server_error)) {
    sweetToast('error', $server_error, $title);
}
?>


<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>

<!-- Datatables -->
<script>
    $(document).ready(() => {
        var table = $('#tblProdutores').DataTable({
            caption: '',
            deferRender: true,
            fixedHeader: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: '48vh',
            language: {
                url: "<?= base_url(DATATABLES_PT_BR) ?>"
            },
            layout: {
                topStart: ['buttons', 'pageLength'],
                topEnd: ['search']
            },
            buttons: [{
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'searchBuilder',
                    text: '<i class="fa-solid fa-file-csv"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'copy',
                    text: '<i class="fa-solid fa-copy"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-solid fa-file-csv"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
            ],
            bPaginate: true,
            bProcessing: true,
            serverSide: false,
            ajax: {
                url: "<?= base_url('cadastro/produtor_getativos') ?>",
                type: "POST",
                data: function(d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
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

                    $('#tblProdutores_processing').hide();
                    $('#tblProdutores').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columnDefs: [{
                targets: [11], // Índices das colunas a serem alinhadas (começando em 0)
                className: 'dt-right',
            }, ],
            columns: [{
                    data: 'produtor'
                },
                {
                    data: 'produto'
                },
                {
                    data: 'localizacao'
                },
                {
                    data: 'id_sankhya'
                },
                {
                    data: 'regiao'
                },
                {
                    data: 'categoria',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'P':
                                return 'Pequeno';
                            case 'M':
                                return 'Médio';
                            case 'G':
                                return 'Grande';
                            default:
                                return 'Desconhecido';
                        }
                    }
                },
                {
                    data: 'cadastro_validado',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Validado';
                            default:
                                return '';
                        }
                    }
                },
                {
                    data: 'validado_serasa',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Verificado';
                            default:
                                return '';
                        }
                    }
                },
                {
                    data: 'embargado',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Sim';
                            default:
                                return '';
                        }
                    }
                },
                {
                    data: 'total_pedidos'
                },
                {
                    data: 'valor_pedidos'
                },
                {
                    data: 'data_ultima_compra',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },

            ]
        });

        // Recarrega a tabela ao clicar no botão
        $('#btnPesquisa').on('click', function() {
            table.ajax.reload(null, false); // O 'false' mantém a página de paginação atual
        });

    })
</script>

<?= $this->endSection() ?>