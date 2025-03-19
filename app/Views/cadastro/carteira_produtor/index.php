<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('/cadastro/carteira_produtor_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <?php if (empty($carteiraProdutores)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem carteiras de produtores cadastradas.</h4>
                            <span>Clique <a href="<?= site_url('/cadastro/carteira_produtor_cria') ?>">aqui</a> para incluir a primeiro carteira de produtores.</span>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1">
                            <div class="row">
                                <table class="display compact" id="tblCarteiraProdutor" style="width: 100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nome da Carteira de Produtores</th>
                                            <th class="text-center">Nome do Comprador</th>
                                            <th class="text-center">Ação</th>
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

<!-- Datatables de Carteira de Produtores -->
<script>
    $(document).ready(() => {
        $('#tblCarteiraProdutor').DataTable({
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
                topStart: ['pageLength'],
                topEnd: ['search', 'buttons']
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
            data: <?= json_encode($carteiraProdutores) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'id',
                    visible: false
                },
                {
                    data: 'nome_carteira_produtor'
                },
                {
                    data: 'nome_completo'
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href=<?= site_url('/cadastro/carteira_produtor_edita/') ?>" + data.id + '><i class="fa-regular fa-pen-to-square shadow-sm"></i></a>' +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiCarteiraProdutor' data-router='/cadastro/carteira_produtor_exclui/" + data.id + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";
                    }
                }
            ]
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblCarteiraProdutor',
    buttonName: 'btnExcluiCarteiraProdutor',
    title: 'Exclusão de Carteira de Produtores',
    message: 'Confirma exclusão da Carteira de Produtores?',
    route: '/cadastro/carteira_produtor_exclui/'
) ?>

<?= $this->endSection() ?>