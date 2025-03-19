<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">
                    <?php if (empty($compradores)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem compradores cadastrados.</h4>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1 ">
                            <div class="row">
                                <table class="display compact" id="tblCompradores" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Usuário</th>
                                            <th class="text-center">Nome do Comprador</th>
                                            <th class="text-center">E-mail</th>
                                            <th class="text-center">Celular</th>
                                            <th class="text-center">Situação</th>
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


<!-- Datatables -->
<script>
    $(document).ready(() => {
        $('#tblCompradores').DataTable({
            deferRender: true,
            fixedHeader: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: '60vh',
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
            data: <?= json_encode($compradores) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'username',
                },
                {
                    data: 'nome_completo'
                },
                {
                    data: 'email_principal'
                },
                {
                    data: 'celular'
                },
                {
                    data: 'situacao',
                    className: 'text-center',
                    "render": function(data, type, row) {
                        if (data === 'I') {
                            return '<span class="badge badge-warning">INATIVO</span>';
                        } else if (data === 'A') {
                            return '<span class="badge badge-success">ATIVO</span>';
                        }
                        return data;
                    }
                }
            ]
        });


    })
</script>

<?= $this->endSection() ?>