<nav class="main-header navbar navbar-expand navbar-white navbar-light glass-menu elevation-1">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <p class="badge bg-warning justify-content-center fw-semibold"><?= session()->user['selectedBranchNickname'] ?></p>
            </a>

            <div class="dropdown-menu dropdown-menu-right glass-menu">
                <?php foreach(session()->filiais as $filial):?>
                    <a href="<?=base_url('/changeBranch/' . $filial->codigo )?>" class="dropdown-item">
                        <div class="media">
                            <div class="media-body">
                                <p class="dropdown-item-title"><?=$filial->apelido?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" role="button" href="<?= site_url('/auth/logout') ?>">
                <i class="fas fa-door-open"></i>
            </a>
        </li>
    </ul>
</nav>