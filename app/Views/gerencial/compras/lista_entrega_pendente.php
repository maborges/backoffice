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

                            <div class="form-group col-3">
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
                                <table class="display compact" id="tblEntregaPendente" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produto</th>
                                            <th>Produtor</th>
                                            <th>Filial</th>
                                            <th>Compra</th>
                                            <th>Compra em</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th>Valor Total</th>
                                            <th>Valor Pago</th>
                                            <th>Unidade</th>
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
        var table = $('#tblEntregaPendente').DataTable({
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
                url: "<?= base_url('gerencial/compra_get_entrega_pendente') ?>",
                type: "POST",
                data: function(d) {
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
                    toastr.error(error, 'erro', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '5000',
                        extendedTimeOut: '2000',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut'
                    });
                    $('#tblEntregaPendente_processing').hide();
                    $('#tblEntregaPendente').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columnDefs: [{
                    targets: 5,
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 3
                        });
                        var unidade = row['unidade'];
                        return `${valorFormatado} ${unidade}`;
                    }
                },
                {
                    targets: [6, 7, 8],
                    render: function(data, type, row) {
                        return parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
                },
                {
                    targets: 9, // Índice da coluna "Unidade"
                    visible: false, // Oculta a coluna "Unidade" da visualização
                    searchable: false
                }
            ],
            columns: [{
                    data: 'nomeProduto'
                },
                {
                    data: 'nomeProdutor'
                },
                {
                    data: 'filial'
                },
                {
                    data: 'numeroCompra'
                },
                {
                    data: 'dataCompra',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'quantidade'
                },
                {
                    data: 'precoUnitario'
                },
                {
                    data: 'totalCompra'
                },
                {
                    data: 'totalPago'
                },
                {
                    data: 'unidade'
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