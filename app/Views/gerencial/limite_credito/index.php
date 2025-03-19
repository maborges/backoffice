<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 ">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('/gerencial/limite_credito_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <?php if (empty($limiteCreditos)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem limites de crédito cadastrados.</h4>
                            <span>Clique <a href="<?= site_url('/gerencial/limite_credito_cria') ?>">aqui</a> para incluir o primeiro limite de crédito.</span>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1">
                            <div class="row">
                                <table class="display compact" id="tblLimiteCreditos" style="width: 100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nome do Produtor</th>
                                            <th class="text-center">Valor do Limite</th>
                                            <th class="text-center">Valor Utilizado</th>
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

<!-- Datatables de Limite de Crédito -->
<script>
    $(document).ready(() => {
        $('#tblLimiteCreditos').DataTable({
            deferRender: true,
            language: {
                url: "<?= base_url(DATATABLES_PT_BR) ?>"
            },
            data: <?= json_encode($limiteCreditos) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 10,
            columns: [{
                    data: 'id',
                    visible: false
                },
                {
                    data: 'nome_produtor'
                },
                {
                    data: 'valor_limite'
                },
                {
                    data: 'valor_utilizado'
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href=<?= site_url('/gerencial/limite_credito_edita/') ?>" + data.id + '><i class="fa-regular fa-pen-to-square shadow-sm"></i></a>' +
                            "<a class='btn text-danger ms-0 p-0 btnExcluiLimiteCreditos' data-router='/gerencial/limite_credito_exclui/" + data.id + "'><i class='fa-regular fa-trash-can shadow-sm'></i></a>" +
                            "</div>";
                    }
                }
            ]
        });
    })
</script>

<!-- Botão de exclusão - Monta JS -->
<?= confirmDelete(
    tableName: 'tblLimiteCreditos',
    buttonName: 'btnExcluiLimiteCreditos',
    title: 'Exclusão de Limite de Crédito',
    message: 'Confirma exclusão do limite de crédito?',
    route: '/gerencial/limite_credito_exclui/'
) ?>

<?= $this->endSection() ?>