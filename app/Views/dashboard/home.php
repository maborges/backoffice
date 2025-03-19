<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="row">
        <?php foreach ($userData['totalEstoque'] as $estoque) : ?>
            <div class="col-lg-4 col-6">
                <div class="info-box bg-gradient-white callout callout-info elevation-1">
                    <div class="info-box-content">
                        <h5 class="info-box-text"><?= $estoque['produto'] ?></h5>
                        <span class="info-box-number">
                            <h5><?= number_format($estoque['total'], 3, ",", ".") . ' ' . $estoque['unidade'] ?></h5>
                        </span>
                    </div>
                    <span class="info-box-icon bg-gradient-cyan"><img src="<?= base_url('assets/images/' . $estoque['imagem'] . '.png') ?>"></span>
                </div>
            </div>
        <?php endforeach ?>
    </div> 

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-white callout callout-info elevation-1">
                <div class="inner">
                    <h5><?= $userData['activeUsersCount'] ?></h5>
                    <p>Usuários cadastrados (<?= $userData['inactiveUsersCount'] ?> inativos).</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add text-cyan"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-white callout callout-info elevation-1">
                <div class="inner">
                    <h5><?= $userData['quantidadeCompras'] ?></h5>
                    <p>Compras cadastradas em <?= $userData['periodoInicial'] ?>.</p>
                </div>
                <div class="icon ">
                    <i class="ion ion-bag text-cyan"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class=" small-box bg-gradient-white callout callout-info elevation-1">
                <div class="inner">    
                    <h5><?= $userData['favorecidosAtivos'] ?></h5>
                    <p>Favorecidos cadastrados (<?= $userData['favorecidosInativos'] ?> inativos).</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash text-cyan"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>