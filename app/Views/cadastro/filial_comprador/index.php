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
                            <a href="<?= site_url('/cadastro/filial_comprador_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <?php if (empty($filiaisCompradores)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não existem registros cadastrados.</h4>
                            <span>Clique <a href="<?= site_url('/cadastro/filial_comprador_cria') ?>">aqui</a> para incluir o primeiro registro.</span>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1">
                            <div class="row">
                                <table class="display compact" id="tblFilialComprador" style="width: 100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Filial</th>
                                            <th class="text-center">Comprador</th>
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

<!-- Datatables de FilialComprador -->
<script>
    $(document).ready(() => {
        $('#tblFilialComprador').DataTable({
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
                    text: '<i class="fa-solid fa-copy"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-solid fa-file-csv"></i>',
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                }
            ],
            initComplete: function() {
                $('.dt-input').addClass('form-control-sm');
            },
            data: <?= json_encode($filiaisCompradores) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'apelido'
                },
                {
                    data: 'primeiro_nome'
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                     //       "<a class='btn text-primary ms-0 p-0' href=<?= site_url('/cadastro/filial_comprador_edita/') ?>" + data.filial + '/' + data.comprador + '><i class="fa-regular fa-pen-to-square shadow-sm"></i></a>' +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiFilialComprador' data-router='/cadastro/filial_comprador_exclui/" + data.filial + '/' + data.comprador + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";
                    }
                }
            ]
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblFilialComprador',
    buttonName: 'btnExcluiFilialComprador',
    title: 'Exclusão de Filial x Comprador',
    message: 'Confirma exclusão do registro?',
    route: '/cadastro/filial_comprador_exclui/'
) ?>

<?= $this->endSection() ?> 