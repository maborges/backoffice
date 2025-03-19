<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('/cadastro/regiao_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <?php if (empty($regioes)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem regiões cadastradas.</h4>
                            <span>Clique <a href="<?= site_url('/cadastro/regiao_cria') ?>">aqui</a> para incluir a primeiro região.</span>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1">
                            <div class="row">
                                <table class="display compact" id="tblRegiao" style="width: 100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nome da Região</th>
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

<!-- Datatables de Regiao -->
<script>
    $(document).ready(() => {
        $('#tblRegiao').DataTable({
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
            initComplete: function() {
                $('.dt-input').addClass('form-control-sm '); // Adiciona classes Bootstrap a todos os botões
            },
            data: <?= json_encode($regioes) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'id',
                    visible: false
                },
                {
                    data: 'nome_regiao'
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href=<?= site_url('/cadastro/regiao_edita/') ?>" + data.id + '><i class="fa-regular fa-pen-to-square shadow-sm"></i></a>' +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiRegiao' data-router='/cadastro/regiao_exclui/" + data.id + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";
                    }
                }
            ]
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblRegiao',
    buttonName: 'btnExcluiRegiao',
    title: 'Exclusão de Região',
    message: 'Confirma exclusão da Região?',
    route: '/cadastro/regiao_exclui/'
) ?>

<?= $this->endSection() ?>