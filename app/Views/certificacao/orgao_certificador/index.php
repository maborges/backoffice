<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <div class="mb-0">
                            <a href="<?= site_url('/certificacao/orgao_certificador_cria') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm">
                                <i class="fa-regular fa-file"></i> Incluir
                            </a>
                        </div>
                    </div>

                    <?php if (empty($orgaos)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem orgãos certificadores cadastrados.</h4>
                            <span>Clique <a href="<?= site_url('/certificacao/orgao_certificador_cria') ?>">aqui</a> para incluir o primeiro orgão certificador.</span>
                        </div>
                    <?php else : ?>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($orgaos as $orgao) : ?>
                                    <?= view('partials/orgao_certificador_card', ['orgao' => $orgao]) ?>
                                <?php endforeach; ?>
                             </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
</section>

<!--
    Mensagens
-->
<?php 
    if (!empty($server_success)) {
        sweetToast('success',$server_success,$title);
    } elseif (!empty($server_warning)) {
        sweetToast('warning',$server_warning,$title);
    }
?>

<?= $this->endSection() ?>