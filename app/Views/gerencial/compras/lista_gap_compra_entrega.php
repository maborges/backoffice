<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?= form_open("#", ['novalidate' => true]) ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">

                    <div class="d-flex card-header">
                        <div class="col-md-12 row">

                            <div class="col-2 form-group">
                                <label for="startDate">Data Inicial</label>
                                <input type="date" class="form-control form-control-sm" name="startDate" id="startDate"
                                    value="<?= $startDate ?>">
                            </div>

                            <div class="col-2 form-group">
                                <label for="endDate">Data Final</label>
                                <input type="date" class="form-control form-control-sm" name="endDate" id="endDate"
                                    value="<?= $endDate ?>">
                            </div>

                            <div class="col-2 form-group">
                                <label for="nomeProduto">Produto</label>
                                <input type="search" class="form-control form-control-sm" name="nomeProduto" id="nomeProduto" value="<?= $nomeProduto ?>">
                                <input type="hidden" name="produto" id="produto" value="<?= $produto ?>">
                            </div>

                            <div class="col-3 form-group">
                                <label for="nomeComprador">Comprador</label>
                                <input type="text" class="form-control form-control-sm" name="nomeComprador" id="nomeComprador" value="<?= $nomeComprador ?>">
                                <input type="hidden" name="comprador" id="comprador" value="<?= $comprador ?>">
                            </div>


                            <div class="col-2 d-flex form-group align-items-end ms-3">
                                <a id="btnPesquisa" class="btn btn-flat btn-outline-secondary btn-sm shadow-sm px-4">
                                    <i class="fa-magnifying-glass fa-solid mr-1"></i>Pesquisar</a>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex card-header mb-0">
                        <div class="col-12 d-flex justify-content-left mb-0">
                            <div class="card border-success mb-3 shadow-sm">
                                <div class="card-body bg-success p-2 text-white bg-opacity-40">
                                    <div>Média Normal: <span id="mediaNormal"></span> - Média Ponderada: <span id="mediaPonderada"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body mt-0">
                        <div class="card-body mt-1 pt-1">
                            <div class="row">
                                <table class="compact display" id="tblGapCompraEntrega" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produto</th>
                                            <th>Produtor</th>
                                            <th>Compra</th>
                                            <th>Quantidade</th>
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
                    text: '<i class="fa-file-csv fa-solid"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'copy',
                    text: '<i class="fa-copy fa-solid"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'print',
                    text: '<i class="fa-print fa-solid"></i>',
                    className: "btn btn-sm btn-outline-secondary btn-flat shadow-sm"
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-file-csv fa-solid"></i>',
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

                    // Calcula a média normal e a média ponderada
                    let totalGap = 0;
                    let totalPeso = 0;
                    let totalQuantidade = 0;

                    json.data.forEach(row => {
                        const gapDias = parseFloat(row.gapDias) || 0;
                        const quantidade = parseFloat(row.quantidade) || 0;

                        totalGap += gapDias;
                        totalQuantidade += quantidade;
                        totalPeso += gapDias * quantidade;
                    });

                    const mediaNormal = totalGap / json.data.length || 1;
                    const mediaPonderada = totalPeso / totalQuantidade || 1;

                    // Exibe os resultados na página
                    $('#mediaNormal').text(mediaNormal.toFixed(2));
                    $('#mediaPonderada').text(mediaPonderada.toFixed(2));

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

            columns: [{
                    data: 'nomeProduto'
                },
                {
                    data: 'nomeProdutor'
                },
                {
                    data: 'numeroCompra'
                },
                {
                    data: 'quantidade',
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        return valorFormatado;
                    }
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
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                toastr.error('Por favor, verifique se ambas as datas são válidas.', 'Erro', {
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

            var startDateObj = new Date(startDate);
            var endDateObj = new Date(endDate);

            if (isNaN(startDateObj.getTime()) || isNaN(endDateObj.getTime())) {
                toastr.error('Por favor, insira datas válidas.', 'Erro', {
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

            if (startDateObj > endDateObj) {
                toastr.error('A data inicial não pode ser maior que a data final.', 'Erro', {
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

            var comprador = $('#comprador').val();
            if (!comprador || comprador <= 0) {
                toastr.error('Por favor, informe o comprador.', 'Erro', {
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