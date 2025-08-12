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
                                <label for="baseDate">Data Base</label>
                                <input type="date" class="form-control form-control-sm" name="baseDate" id="baseDate"
                                    value="<?= $baseDate ?>">
                            </div>

                            <div class="form-group col-2">
                                <label for="nomeProduto">Produto</label>
                                <input type="search" class="form-control form-control-sm" name="nomeProduto" id="nomeProduto" value="<?= $nomeProduto ?>">
                                <input type="hidden" name="produto" id="produto" value="<?= $produto ?>">
                            </div>

                            <div class="form-group col-2">
                                <label for="filial">Filial</label>
                                <input type="text" class="form-control form-control-sm" name="filial" id="filial" value="<?= $filial ?>">
                            </div>

                            <div class="form-group col-1 d-flex align-items-end">
                                <a id="btnPesquisa" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i>Pesquisar</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="col-12">
                            <div class="row">
                                <table class="display compact" id="tblSaldoGerencial" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Saldo Acumulado</th>
                                            <th>Estoque</th>
                                            <th>Armazenado</th>
                                            <th>Saldo a Puxar</th>
                                            <th>Vendas</th>
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

        var table = $('#tblSaldoGerencial').DataTable({
            /*
            footerCallback: function(row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i :
                        0;
                };

                // Total over all pages
                totalValor = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalFUNRURAL = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalFrete = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalGerencial = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer
                api.column(6).footer().innerHTML =
                    totalValor.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                api.column(10).footer().innerHTML =
                    totalFUNRURAL.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                api.column(11).footer().innerHTML =
                    totalFrete.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                api.column(12).footer().innerHTML =
                    totalGerencial.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

            },
            */
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
                url: "<?= base_url('posicao_estoque/get_saldo_fiscal') ?>",
                type: "POST",
                data: function(d) {
                    d.baseDate = $('#baseDate').val();
                    d.produto = $('#produto').val();
                    d.filial = $('#filial').val();
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
                    $('#tblSaldoGerencial_processing').hide();
                    $('#tblSaldoGerencial').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columnDefs: [{
                targets: [1, 2, 3, 4, 5],
                render: function(data, type, row) {
                    return parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    });
                }
            }, ],
            columns: [{
                    data: 'data_movimento',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'saldo_acumulado'
                },
                {
                    data: 'estoque_fiscal_sankhya'
                },
                {
                    data: 'armazenado_sankhya'
                },
                {
                    data: 'saldo_puxar'
                },
                {
                    data: 'venda_acumulada_sankhya'
                },
            ],
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

        $("#filial").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/filial_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.descricao,
                                value: item.descricao,
                                id: item.descricao
                            };
                        }));
                    }
                });
            },

            select: function(event, ui) {
                $("#filial").val(ui.item.id);
            },
            minLength: 2
        });

        // Recarrega a tabela ao clicar no botão
        $('#btnPesquisa').on('click', function() {
            var baseDate = $('#baseDate').val();

            if (!baseDate) {
                toastr.error('Data base obrigatória.', 'Erro', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '5000',
                    extendedTimeOut: '2000',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                });
                return;
            }

            var baseDateObj = new Date(baseDate);

            if (isNaN(baseDateObj.getTime())) {
                toastr.error('Informe uma data válida', 'Erro', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '5000',
                    extendedTimeOut: '2000',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                });
                return;
            }

            var produto = $('#produto').val();
            if (!produto || produto <= 0) {
                toastr.error('Por favor, informe o produto.', 'Erro', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '5000',
                    extendedTimeOut: '2000',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                });
                return;
            }

            var filial = $('#filial').val();
            if (!filial) {
                toastr.error('Por favor, informe a filial.', 'Erro', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '5000',
                    extendedTimeOut: '2000',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                });
                return;
            }

            table.ajax.reload(null, false); // O 'false' mantém a página de paginação atual

        });

    })
</script>

<?= $this->endSection() ?>