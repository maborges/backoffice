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

                            <div class="form-group col-3">
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-body shadow-sm">
                    <table class="display compact" id="tblResumoComprador" style="width: 100%; height: auto">
                        <thead class="table-light">
                            <tr>
                                <th>Comprador</th>
                                <th>Volume</th>
                                <th>Ticket Médio</th>
                                <th>Clientes Ativos</th>
                                <th>Preço Médio</th>
                                <th>Volume Puxar</th>
                                <th>Tempo Puxar</th>
                            </tr>
                        </thead>
                        <tfoot class="table-light">
                            <tr>
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

            <div class="col-md-4">
                <div class="card card-body shadow-sm">
                    <table class="display compact" id="tblTop10Cliente" style="width: 100%; height: auto">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-body shadow-sm">
                    <table class="display compact" id="tblResumoFilial" style="width: 100%; height: auto">
                        <thead class="table-light">
                            <tr>
                                <th>Filial</th>
                                <th>Volume</th>
                                <th>Ticket Médio</th>
                                <th>Clientes Ativos</th>
                                <th>Preço Médio</th>
                                <th>Volume Puxar</th>
                                <th>Tempo Puxar</th>
                            </tr>
                        </thead>
                        <tfoot class="table-light">
                            <tr>
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
            <div class="col-md-4">
                <div class="card card-body shadow-sm">
                    <table class="display compact" id="tblTop10Regiao" style="width: 100%; height: auto">
                        <thead class="table-light">
                            <tr>
                                <th>Região</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <!--
            <div class="col-md-6">
                <div class="card card-body shadow-sm">
                    <div class="chart">
                        <canvas id="chartResumoClassificacao" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            -->

            <div class="col-md-6">
                <div class="card card-body shadow-sm">
                    <div class="chart">
                        <canvas id="chartDashboardFilial" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-body shadow-sm">
                    <div class="chart">
                        <canvas id="chartDashboardComprador" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-body shadow-sm">
                    <div class="chart">
                        <canvas id="chartDashboardClassificacao" style="min-height: 250px; height: 250px; max-height: auto; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body shadow-sm">
                    <div class="chart">
                        <canvas id="chartDashboardCategoria" style="min-height: 250px; height: 250px; max-height: auto; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <?= form_close() ?>
</section>

<?= $this->endSection() ?>


<?= $this->section('scripts'); ?>
<script>
    var tableComprador = $('#tblResumoComprador').DataTable({
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

            // Total Volume
            let totalVolume = api
                .column(1)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Ticket Médio
            let totalTicket = api
                .column(2)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countTicket = api.column(2).data().length;
            let mediaTicket = countTicket > 0 ? totalTicket / countTicket : 0;

            // Total Clientes Ativos
            let totalClientes = api
                .column(3)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Preço Médio
            let totalPreco = api
                .column(4)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countPreco = api.column(4).data().length;
            let mediaPreco = countPreco > 0 ? totalPreco / countPreco : 0;

            // Total Volume Puxar
            let totalVolumePuxar = api
                .column(5)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Tempo Puxar
            let totalTempo = api
                .column(6)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countTempo = api.column(6).data().length;
            let mediaTempoPuxar = countTempo > 0 ? totalTempo / countTempo : 0;

            // Atualiza o rodapé
            $(api.column(1).footer()).html(
                '<strong>' + totalVolume.toLocaleString('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + '</strong>'
            );
            $(api.column(2).footer()).html(
                '<strong>' + mediaTicket.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>'
            );
            $(api.column(3).footer()).html(
                '<strong>' + totalClientes.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + '</strong>'
            );
            $(api.column(4).footer()).html(
                '<strong>' + mediaPreco.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>'
            );
            $(api.column(5).footer()).html(
                '<strong>' + totalVolumePuxar.toLocaleString('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + '</strong>'
            );
            $(api.column(6).footer()).html(
                '<strong>' + mediaTempoPuxar.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + '</strong>'
            );
        },
        caption: 'Resumo Comprador',
        paging: false,
        searching: false,
        info: false,
        lengthChange: false,
        deferRender: true,
        fixedHeader: true,
        scrollCollapse: true,
        scroller: true,
        scrollY: '48vh',
        language: {
            url: "<?= base_url(DATATABLES_PT_BR) ?>"
        },
        bPaginate: true,
        bProcessing: true,
        serverSide: false,
        ajax: {
            url: "<?= base_url('gerencial/resumo_comprador') ?>",
            type: "POST",
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
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
                $('#tblResumoComprador_processing').hide();
                $('#tblResumoComprador').DataTable().clear().draw();
            }
        },
        pageLength: 10,
        columns: [{
                data: 'comprador'
            },
            {
                data: 'volume_comprado',
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
                data: 'ticket_medio',
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
                data: 'clientes_ativos',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    return valorFormatado;
                }
            },
            {
                data: 'preco_medio',
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
                data: 'volume_puxar',
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
                data: 'tempo_puxar',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    return valorFormatado;
                }
            }

        ],
    });

    var tableFilial = $('#tblResumoFilial').DataTable({
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

            // Total Volume
            let totalVolume = api
                .column(1)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Ticket Médio
            let totalTicket = api
                .column(2)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countTicket = api.column(2).data().length;
            let mediaTicket = countTicket > 0 ? totalTicket / countTicket : 0;

            // Total Clientes Ativos
            let totalClientes = api
                .column(3)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Preço Médio
            let totalPreco = api
                .column(4)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countPreco = api.column(4).data().length;
            let mediaPreco = countPreco > 0 ? totalPreco / countPreco : 0;

            // Total Volume Puxar
            let totalVolumePuxar = api
                .column(5)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Média Tempo Puxar
            let totalTempo = api
                .column(6)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            let countTempo = api.column(6).data().length;
            let mediaTempoPuxar = countTempo > 0 ? totalTempo / countTempo : 0;

            // Atualiza o rodapé
            $(api.column(1).footer()).html(
                '<strong>' + totalVolume.toLocaleString('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + '</strong>'
            );
            $(api.column(2).footer()).html(
                '<strong>' + mediaTicket.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>'
            );
            $(api.column(3).footer()).html(
                '<strong>' + totalClientes.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + '</strong>'
            );
            $(api.column(4).footer()).html(
                '<strong>' + mediaPreco.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>'
            );
            $(api.column(5).footer()).html(
                '<strong>' + totalVolumePuxar.toLocaleString('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + '</strong>'
            );
            $(api.column(6).footer()).html(
                '<strong>' + mediaTempoPuxar.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + '</strong>'
            );
        },
        caption: 'Resumo Filial',
        paging: false,
        searching: false,
        info: false,
        lengthChange: false,
        deferRender: true,
        fixedHeader: true,
        scrollCollapse: true,
        scroller: true,
        scrollY: '48vh',
        language: {
            url: "<?= base_url(DATATABLES_PT_BR) ?>"
        },
        bPaginate: true,
        bProcessing: true,
        serverSide: false,
        ajax: {
            url: "<?= base_url('gerencial/resumo_filial') ?>",
            type: "POST",
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
                d.produto = $('#produto').val();
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
                $('#tblResumoFilial_processing').hide();
                $('#tblResumoFilial').DataTable().clear().draw();
            }
        },
        pageLength: 10,
        columns: [{
                data: 'nome_filial'
            },
            {
                data: 'volume_comprado',
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
                data: 'ticket_medio',
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
                data: 'clientes_ativos',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    return valorFormatado;
                }
            },
            {
                data: 'preco_medio',
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
                data: 'volume_puxar',
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
                data: 'tempo_puxar',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    return valorFormatado;
                }
            }

        ],
    });

    var tableTop10Cliente = $('#tblTop10Cliente').DataTable({
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
        },
        caption: 'Top 10 Clientes',
        paging: false,
        searching: false,
        info: false,
        lengthChange: false,
        deferRender: true,
        fixedHeader: true,
        scrollCollapse: true,
        scroller: true,
        scrollY: '48vh',
        language: {
            url: "<?= base_url(DATATABLES_PT_BR) ?>"
        },
        bPaginate: true,
        bProcessing: true,
        serverSide: false,
        columnDefs: [{
                targets: 0,
                width: '70%'
            }, // Define a largura da primeira coluna como 20%
            {
                targets: 1,
                width: '30%'
            }
        ],
        ajax: {
            url: "<?= base_url('gerencial/top10_cliente') ?>",
            type: "POST",
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
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
                $('#tblTop10Cliente_processing').hide();
                $('#tblTop10Cliente').DataTable().clear().draw();
            }
        },
        pageLength: 10,
        columns: [{
                data: 'nome'
            },
            {
                data: 'participacao',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    });

                    return type === 'display'
                    ? '<div class="progress" role="progressbar" aria-valuenow="' + data + '" aria-valuemin="0" aria-valuemax="100">' +
                            '<div class="progress-bar overflow-visible bg-warning progress-bar-striped" style="width: ' + data + '%"><strong>' + valorFormatado + ' %</strong></div>' +
                        '</div>'
                    : valorFormatado;
                }
            }

        ],
    });

    var tableTop10Regiao = $('#tblTop10Regiao').DataTable({
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
        },
        caption: 'Top 10 Regiões',
        paging: false,
        searching: false,
        info: false,
        lengthChange: false,
        deferRender: true,
        fixedHeader: true,
        scrollCollapse: true,
        scroller: true,
        scrollY: '48vh',
        language: {
            url: "<?= base_url(DATATABLES_PT_BR) ?>"
        },
        bPaginate: true,
        bProcessing: true,
        serverSide: false,
        columnDefs: [{
                targets: 0,
                width: '70%'
            }, // Define a largura da primeira coluna como 20%
            {
                targets: 1,
                width: '30%'
            }
        ],
        ajax: {
            url: "<?= base_url('gerencial/top10_regiao') ?>",
            type: "POST",
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
                d.produto = $('#produto').val();
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
                $('#tblTop10Cliente_processing').hide();
                $('#tblTop10Cliente').DataTable().clear().draw();
            }
        },
        pageLength: 10,
        columns: [{
                data: 'regiao'
            },
            {
                data: 'participacao',
                className: 'dt-right',
                render: function(data, type, row) {
                    var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    });

                    return type === 'display'
                    ? '<div class="progress" role="progressbar" aria-valuenow="' + data + '" aria-valuemin="0" aria-valuemax="100">' +
                            '<div class="progress-bar overflow-visible bg-warning progress-bar-striped" style="width: ' + data + '%"><strong>' + valorFormatado + ' %</strong></div>' +
                        '</div>'
                    : valorFormatado;
                }
            }

        ],
    });

    // Função para exibir mensagens de erro
    function showError(xhr, status, error) {
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
    }

    // Função para criar gráficos
    function createChart(chartVar, canvasId, data, options) {
        const areaChartCanvas = document.getElementById(canvasId).getContext('2d');
        if (chartVar) {
            chartVar.destroy();
        }
        return new Chart(areaChartCanvas, {
            type: 'bar',
            data: data,
            options: options
        });
    }

    // Função para carregar dados do gráfico
    function loadChartData(url, data, successCallback) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: data,
            success: successCallback,
            error: showError
        });
    }

    // Recarrega a tabela e os gráficos ao clicar no botão      
    $('#btnPesquisa').on('click', function() {
        // Valida se startDate, endDate e produto são válidos e se o período também é válido
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var produto = $('#produto').val();

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

        tableComprador.ajax.reload(null, false);
        tableFilial.ajax.reload(null, false);
        tableTop10Cliente.ajax.reload(null, false);
        tableTop10Regiao.ajax.reload(null, false);
        // Atualiza os gráficos
        loadChartDashboardFilialData();
        loadChartDashboardCompradorData();
        loadChartDashboardClassificacao();
        loadChartDashboardCategoria();
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

    $("#nomeProduto").on('change keyup', function() {
        if ($(this).val() === '') {
            $("#produto").val(''); // Limpa o campo #produto
        }
    });

    let chartClassificacao;
    // Charts
    function loadChartClassificacaoData() {
        $.ajax({
            url: "<?= base_url('gerencial/resumo_classificacao') ?>",
            type: "POST",
            dataType: 'json',
            data: {
                endDate: $('#endDate').val(),
                produto: $('#produto').val(),
                filial: $('#filial').val()
            },
            success: function(data) {
                showChartClassificacao(data);
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
            }
        });
    }

    function showChartClassificacao(data) {
        const areaChartCanvas = document.getElementById('chartResumoClassificacao').getContext('2d');
        var stackedBarChartData = $.extend(true, {}, data)

        var areaChartOptions = {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Volume de Compras por Classificação no Período'
                },
                autocolors: {
                    mode: 'data'
                },
                legend: {
                    position: 'left',
                },
              
            },
            interaction: {
                intersect: false
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            },
        }

        chartClassificacao = new Chart(areaChartCanvas, {
            type: 'bar',
            data: data,
            options: areaChartOptions // Registrando o plugin manualmente
        }); 
    }


    let chartDashboardFilial;
    function loadChartDashboardFilialData() {
        loadChartData("<?= base_url('gerencial/dashboard_filiais') ?>", {
            endDate: $('#endDate').val(),
            produto: $('#produto').val()
        }, function(data) {
            chartDashboardFilial = createChart(chartDashboardFilial, 'chartDashboardFilial', data, {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Volume de Compras por Filial no Período'
                    },
                    autocolors: {
                        mode: 'data'
                    },
                    legend: {
                        position: 'left',
                    },
                },
                interaction: {
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                },
            });
        });
    }

    let chartDashboardComprador;
    function loadChartDashboardCompradorData() {
        loadChartData("<?= base_url('gerencial/dashboard_comprador') ?>", {
            endDate: $('#endDate').val(),
            produto: $('#produto').val()
        }, function(data) {
            chartDashboardComprador = createChart(chartDashboardComprador, 'chartDashboardComprador', data, {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Volume de Compras por Comprador no Período'
                    },
                    autocolors: {
                        mode: 'data'
                    },
                    legend: {
                        position: 'left',
                    },
                },
                interaction: {
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                },
            });
        });
    }

    let chartDashboardClassificacao;
    function loadChartDashboardClassificacao() {
        loadChartData("<?= base_url('gerencial/dashboard_classificacao') ?>", {
            endDate: $('#endDate').val(),
            produto: $('#produto').val(),
            filial: $('#filial').val()
        }, function(data) {
            chartDashboardClassificacao = createChart(chartDashboardClassificacao, 'chartDashboardClassificacao', data, {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Volume de Compras por Classificação no Período'
                    },
                    autocolors: {
                        mode: 'data'
                    },
                    legend: {
                        position: 'left',
                    },
                },
                interaction: {
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                },
            });
        });
    }

    let chartDashboardCategoria;
    function loadChartDashboardCategoria() {
        loadChartData("<?= base_url('gerencial/dashboard_categoria') ?>", {
            endDate: $('#endDate').val(),
            produto: $('#produto').val(),
            filial: $('#filial').val()
        }, function(data) {
            chartDashboardCategoria = createChart(chartDashboardCategoria, 'chartDashboardCategoria', data, {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Volume de Compras por Categoria no Período'
                    },
                    autocolors: {
                        mode: 'data'
                    },
                    legend: {
                        position: 'left',
                    },
                },
                interaction: {
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: false
                    }
                },
            });
        });
    }

    

</script>

<?= $this->endSection() ?>