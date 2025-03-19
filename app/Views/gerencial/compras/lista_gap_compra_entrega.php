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

                            <div class="form-group col-2">
                                <label for="nomeProduto">Produto</label>
                                <input type="search" class="form-control form-control-sm" name="nomeProduto" id="nomeProduto" value="<?= $nomeProduto ?>">
                                <input type="hidden" name="produto" id="produto" value="<?= $produto ?>">
                            </div>

                            <div class="form-group col-3">
                                <label for="nomeProdutor">Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="nomeProdutor" id="nomeProdutor" value="<?= $nomeProdutor ?>">
                                <input type="hidden" name="produtor" id="produtor" value="<?= $produtor ?>">
                            </div>

                            <div class="form-group ms-3 col-2 d-flex align-items-end">
                                <a id="btnPesquisa" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i>Pesquisar</a>
                            </div>
                        </div>

                    </div>

                    <div class="card-body mt-0">
                        <div class="card-body mt-1 pt-1 ">
                            <div class="row">
                                <table class="display compact" id="tblGapCompraEntrega" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código Produto</th>
                                            <th>Produto</th>
                                            <th>Código Produtor</th>
                                            <th>Produtor</th>
                                            <th>Tipo</th>
                                            <th>Compra</th>
                                            <th>Sankhya</th>
                                            <th>Compra em</th>
                                            <th>Entregue em</th>
                                            <th>Em Dias</th>
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
    $(document).ready(function() {
        var table = $('#tblGapCompraEntrega').DataTable({
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
                url: "<?= base_url('gerencial/compra_get_gap_entrega') ?>",
                type: "POST",
                data: function(d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    d.produto = $('#produto').val();
                    d.produtor = $('#produtor').val();
                },
                dataSrc: function(json) {
                    if (json.error) {
                        toastr.error(json.error, 'Erro', {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: '5000',
                            extendedTimeOut: '2000',
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut'
                        });
                        return [];
                    }
                    return json.data;
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    var responseJSON = JSON.parse(xhr.responseText);
                    if (responseJSON && responseJSON.message) {
                        toastr.error(responseJSON.message, 'Erro', {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: '5000',
                            extendedTimeOut: '2000',
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut'
                        });
                    } else {
                        console.error('Erro desconhecido:', status, error);
                    }
                    $('#tblGapCompraEntrega_processing').hide();
                    $('#tblGapCompraEntrega').DataTable().clear().draw();
                }
            },
            pageLength: 25,

            columnDefs: [{
                targets: [0, 2], // Índice da coluna "Unidade"
                visible: false // Oculta a coluna "Unidade" da visualização
                //  searchable: false
            }],

            columns: [{
                    data: 'codigoProduto'
                },
                {
                    data: 'nomeProduto'
                },
                {
                    data: 'codigoProdutor'
                },
                {
                    data: 'nomeProdutor'
                },
                {
                    data: 'tipo'
                },
                {
                    data: 'numeroCompra'
                },
                {
                    data: 'idSankhya'
                },
                {
                    data: 'dataCompra',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'dataEntregaFinal',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'gapDias'
                }
            ]
        });

        $("#nomeProdutor").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/produtor_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nome,
                                value: item.nome,
                                id: item.codigo
                            };
                        }));
                    }
                });
            },

            select: function(event, ui) {
                $("#produtor").val(ui.item.id);
            },
            minLength: 2
        });

        $("#nomeProduto").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/produto_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.descricao,
                                value: item.descricao,
                                id: item.codigo
                            };
                        }));
                    }
                });
            },

            select: function(event, ui) {
                $("#produto").val(ui.item.id);
            },
            minLength: 2
        });

        $("#nomeProduto").on('change keyup', function() {
            if ($(this).val() === '') {
                $("#produto").val(''); // Limpa o campo #produto
            }
        });

        // Recarrega a tabela ao clicar no botão
        $('#btnPesquisa').on('click', function() {
            table.ajax.reload(null, false); // O 'false' mantém a página de paginação atual
        });


        $("#nomeProdutor").on('change keyup', function() {
            if ($(this).val() === '') {
                $("#produtor").val(''); // Limpa o campo #produto
            }
        });


    })
</script>

<?= $this->endSection() ?>