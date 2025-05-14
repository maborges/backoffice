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
                                <label for="filial">Filial</label>
                                <input type="text" class="form-control form-control-sm" name="filial" id="filial" value="<?= $filial ?>">
                            </div>

                            <div class="col-3 form-group">
                                <label for="nomeComprador">Comprador</label>
                                <input type="text" class="form-control form-control-sm" name="nomeComprador" id="nomeComprador" value="<?= $nomeComprador ?>">
                                <input type="hidden" name="comprador" id="comprador" value="<?= $comprador ?>">
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
                                            <th>Quantidade</th>
                                            <th>Média Dias Atraso</th>
                                            <th>Média Valor</th>
                                            <th>Valor Pago</th>
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
                    d.filial = $('#filial').val();
                    d.comprador = $('#comprador').val();
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
                    console.log(xhr, status, error);
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
                    targets: 3,
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 1,
                            maximumFractionDigits: 1
                        });
                        return `${valorFormatado}`;
                    }
                },
                {
                    targets: 4,
                    type: 'num-fmt',
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        });
                        return `${valorFormatado}`;
                    }
                },
                {
                    targets: [5, 6],
                    render: function(data, type, row) {
                        return parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
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
                    data: 'quantidade'
                },
                {
                    data: 'mediaDiasAtraso'
                },
                {
                    data: 'mediaValorCompra'
                },
                {
                    data: 'totalPago'
                }
            ]
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
            if (!filial || filial <= 0) {
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

        $("#nomeComprador").on('change keyup', function() {
            if ($(this).val() === '') {
                $("#comprador").val(''); // Limpa o campo #produto
            }
        });

        $("#nomeComprador").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/comprador_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nome_completo,
                                value: item.nome_completo,
                                id: item.username
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $("#comprador").val(ui.item.id);
            },
            minLength: 2
        });




    })
</script>

<?= $this->endSection() ?>