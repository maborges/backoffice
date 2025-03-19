<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?= form_open("#", ['novalidate' => true]) ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">

                    <div class="card-body mt-1 pt-1">
                        <div class="row">
                            <table class="display compact" id="tblLimiteCompras" style="width: 100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Código</th>
                                        <th class="text-center">Nome do Produtor</th>
                                        <th class="text-center">Descrição do Produto</th>
                                        <th class="text-center">Quantidade Limite (kg)</th>
                                        <th class="text-center">Quantidade Utilizada (Kg)</th>
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

<!-- Datatables de Limite de Crédito -->
<script>
    $(document).ready(() => {
        $('#tblLimiteCompras').DataTable({
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
            data: <?= json_encode($limiteCompras) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'id',
                    visible: false
                },
                {
                    data: 'nome_produtor'
                },
                {
                    data: 'descricao_produto'
                },
                {
                    data: 'quantidade_limite',
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
                    data: 'quantidade_utilizada',
                    className: 'dt-right',
                    render: function(data, type, row) {
                        var valorFormatado = parseFloat(data).toLocaleString("pt-BR", {
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 3
                        });
                        return valorFormatado;
                    }
                }
            ]
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblLimiteCompras',
    buttonName: 'btnExcluiLimiteCompras',
    title: 'Exclusão de Limite de Compras',
    message: 'Confirma exclusão do limite de compras?',
    route: '/gerencial/limite_compra_exclui/'
) ?>

<?= $this->endSection() ?>