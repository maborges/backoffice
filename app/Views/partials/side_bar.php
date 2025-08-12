<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-light elevation-2 glass-menu">
    <!-- Brand Logo -->
    <a href="<?= site_url('/') ?>" class="brand-link">
        <img src="<?= base_url('assets/images/logo.webp') ?>" alt="GBO Logo" class="brand-image img-circle elevation-1" style="opacity: .8">
        <!--
        <span class="brand-text font-weight-light">Back Office</span>
        -->
        <h4 class="brand-text app-primary-color">
            <spam class="font-weight-bold">Back</spam>
            <spam class="app-tertiary-color"> Office</spam>
        </h4>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/images/NoUserPicture.webp') ?>" class="img-circle elevation-1" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= session()->user['firstName'] ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Procurar..." aria-label="Search">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboards
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/gerencial/dashboard') ?>" class="nav-link">
                                <i class="fa-solid fa-user-tie"></i>
                                <p>Gerencial</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/') ?>" class="nav-link">
                                <i class="fa-solid fa-chalkboard"></i>
                                <p>Administrativo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <p>Operacional</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ESTOQUE -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-warehouse"></i>
                        <p>
                            Posição de Estoque
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/saldo-armazenado') ?>" class="nav-link">
                                <i class="fa-solid fa-database"></i>
                                <p>Saldo Armazenado Gerencial</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/cadastro/contrato_posicao_estoque') ?>" class="nav-link">
                                <i class="fa-solid fa-file-signature"></i>
                                <p>Contrato Posição Estoque</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/posicao_estoque/saldo_gerencial') ?>" class="nav-link">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <p>Saldo Gerencial</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/posicao_estoque/saldo_suif') ?>" class="nav-link">
                                <i class="fa-solid fa-cubes"></i>
                                <p>Saldo SUIF</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/posicao_estoque/saldo_fiscal') ?>" class="nav-link">
                                <i class=" fa-solid fa-layer-group"></i>
                                <p>Saldo Fiscal</p>
                            </a>
                        </li>
                    </ul>

                    <ul class=" nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/posicao_estoque/saldo_4c') ?>" class="nav-link">
                                <i class="fa-solid fa-medal"></i>
                                <p>Saldo 4C</p>
                            </a>
                        </li>
                    </ul>

                </li>

                <!-- CRM -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            CRM
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/cadastro/produtor') ?>" class="nav-link">
                                <i class="fa-solid fa-people-roof"></i>
                                <p>Cadastro de Produtores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/gerencial/limite_compra') ?>" class="nav-link">
                                <i class="fa-solid fa-money-bill-wheat"></i>
                                <p>Limite de Compras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/cadastro/regiao') ?>" class="nav-link">
                                <i class="fa-solid fa-map-location"></i>
                                <p>Cadastro de Regiões</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('cadastro/produtor_lista_situacao') ?>" class="nav-link">
                                <i class="fa-solid fa-person-rays"></i>
                                <p>Produtores Ativos/Inativos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/cadastro/comprador') ?>" class="nav-link">
                                <i class="fa-solid fa-users-viewfinder"></i>
                                <p>Cadastro de Compradores</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= site_url('/cadastro/filial_comprador') ?>" class="nav-link">
                                <i class="fa-solid fa-users-viewfinder"></i>
                                <p>Filial x Comprador</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= site_url('/gerencial/compra_preco_gerencial') ?>" class="nav-link">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <p>Preço Gerencial</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/gerencial/compra_gap_compra_entrega') ?>" class="nav-link">
                                <i class="fa-solid fa-truck-arrow-right"></i>
                                <p>Gap-Compra X Entrega</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/gerencial/compra_entrega_pendente') ?>" class="nav-link">
                                <i class="fa-solid fa-truck-fast"></i>
                                <p>Entregas Pendentes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Certificação -->
                <!--
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Certificações
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('/certificacao/orgao_certificador') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Orgão Certificador</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('/certificacao/questionario') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Questionários</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/boxed.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perguntas e Alternativas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pesquisas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->




</aside>