<!-- Main Footer -->
<footer class="main-footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6 col-sm-4 text-sm-start text-left">
                <strong>Copyright &copy; 2024 <a class="app-primary-color" href="http://grancafe.com.br/">GRANCAFÉ</a>.</strong>
                Todos os direitos reservados.
            </div>

            <div class="col-2 col-sm-4 text-center">
                <?php
                $ambiente = DB_AMBIENTE;
                ?>
                <span class="badge badge-<?= ($ambiente == 'Homologação') ? 'warning' : 'secondary' ?>"><?= htmlspecialchars($ambiente) ?></span>
            </div>

            <div class="col-4 col-sm-4 text-sm-end text-right">
                <b>Versão</b> 1.1.0
            </div>
        </div>
    </div>
</footer>