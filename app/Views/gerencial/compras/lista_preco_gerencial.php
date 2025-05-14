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
                            <!--
                            /* A pedido do Saulo em 09/05/2025
                            <div class="form-group col-3">
                                <label for="nomeProdutor">Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="nomeProdutor" id="nomeProdutor" value="<?= $nomeProdutor ?>">
                                <input type="hidden" name="produtor" id="produtor" value="<?= $produtor ?>">
                            </div>
                            -->
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
                            <div id="resumo" class="d-flex flex-wrap">
                                <!-- Cards serão renderizados aqui -->
                            </div>

                            <div class="row">
                                <table class="display compact" id="tblPrecoGerencial" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produtor</th>
                                            <th>Produto</th>
                                            <th>Compra em</th>
                                            <th>Filial</th>
                                            <th>Quantidade</th>
                                            <th>Vlr.Unitário</th>
                                            <th>Vlr.Total</th>
                                            <th>INSS</th>
                                            <th>Rat</th>
                                            <th>SENAR</th>
                                            <th>FUNRURAL</th>
                                            <th>Frete</th>
                                            <th>Valor Gerencial</th>
                                            <th>Média Gerencial</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align:right"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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

        var table = $('#tblPrecoGerencial').DataTable({
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
                url: "<?= base_url('gerencial/compra_get_preco_gerencial') ?>",
                type: "POST",
                data: function(d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    d.produto = $('#produto').val();
                    d.produtor = $('#produtor').val();
                    d.filial = $('#filial').val();
                },
                beforeSend: function() {
                    carregarResumo();
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
                    $('#tblPrecoGerencial_processing').hide();
                    $('#tblPrecoGerencial').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columnDefs: [{
                    targets: [5, 6, 7, 8, 9, 10, 11],
                    render: function(data, type, row) {
                        return parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                },
                {
                    targets: [7, 8, 9],
                    visible: false,
                    searchable: false
                }
            ],
            columns: [{
                    data: 'nome'
                },
                {
                    data: 'produto'
                },
                {
                    data: 'data_compra',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: 'filial'
                },
                {
                    data: 'quantidade',
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 3
                        });
                        return valorFormatado;
                    }
                },
                {
                    data: 'preco_unitario'
                },
                {
                    data: 'valor_total'
                },
                {
                    data: 'inss'
                },
                {
                    data: 'rat'
                },
                {
                    data: 'senar'
                },
                {
                    data: 'valor_funrural'
                },
                {
                    data: 'valor_frete'
                },
                {
                    data: 'valor_gerencial',
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
                    data: 'valor_media_gerencial',
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        return valorFormatado;
                    }
                }

            ],
        });

        // Função para carregar o resumo
        function carregarResumo() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const produto = $('#produto').val();
            const produtor = $('#produtor').val();
            const filial = $('#filial').val();

            $.ajax({
                url: "<?= base_url('gerencial/compra_get_preco_gerencial_resumo') ?>",
                method: 'POST',
                data: {
                    startDate,
                    endDate,
                    produto,
                    produtor,
                    filial
                },
                success: function(response) {
                    montarCards(response);
                },
                error: function() {
                    $('#resumo').html('<div class="col-12">Erro ao carregar o resumo.</div>');
                }
            });
        }

        // Função para montar os cards
        function montarCards(dados) {
            var cardsHTML = '';
            dados.data.forEach(item => {

                cardsHTML += `
                <div class="col-md-4 col-12">
                    <div class="info-box bg-gradient-white callout callout-info elevation-1">
                        <div class="info-box-content position-relative" style="background-image: url('<?= base_url('assets/images/') ?>${item.nome_imagem}.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                            <div class="content position-relative" style="z-index: 1; background: rgba(255, 255, 255, 0.8);">
                                <div class="row mt-1 mb-1">
                                    <div class="col-7 info-box-number text-left">
                                        ${item.produto}
                                    </div>
                                    <div class="col-5 font-weight-normal text-left">
                                        Total: ${parseFloat(item.quantidade_total).toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})} ${item.unidade_print}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7 font-weight-normal text-left">
                                        Valor: R$ ${parseFloat(item.valor_total).toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})}
                                    </div>
                                    <div class="col-5 font-weight-normal text-left">
                                        Média: R$ ${parseFloat(item.valor_media).toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            });
            $('#resumo').html(cardsHTML);
        }

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

        $("#nomeProdutor").on('change keyup', function() {
            if ($(this).val() === '') {
                $("#produtor").val(''); // Limpa o campo #produto
            }
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
            /* A pedido do Saulo em 09/05/2025
            var produtor = $('#produtor').val();
            if (!produtor || produtor <= 0) {
                toastr.error('Por favor, informe o produtor.', 'Erro', {
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
            */

            table.ajax.reload(null, false); // O 'false' mantém a página de paginação atual
        });

    })
</script>

<?= $this->endSection() ?>