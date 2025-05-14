<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline app-card-outline-top glass-card">
        <div class="card-header text-center">
            <img src="<?= base_url('assets/images/logo.webp') ?>" class="img-fluid" width="60" alt="Grancafé - Back Office">
            <spam class="h1 app-primary-color">
                <b>Back</b>
                <spam class="app-tertiary-color"> Office</spam>
            </spam>
        </div>

        <div class="card-body">
            <p class="login-box-msg">Informe suas credenciais de acesso</p>

            <?= form_open('auth/loginSubmit', ['novalidate' => true]) ?>
            <div class="input-group mb-0">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope app-primary-color"></span>
                    </div>
                </div>
                <input type="text" name='username' id='username' class="form-control" placeholder="Usuário" value="<?= old('username') ?>">
            </div>
            <?= displayError('username', $validation_errors) ?>

            <div class="input-group mt-3 mb-0 ">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock app-primary-color"></span>
                    </div>
                </div>
                <input type="password" name='password' id='password' class="form-control" placeholder="Senha" value="<?= old('password') ?>">
            </div>
            <?= displayError('password', $validation_errors) ?>

            <p class="mb-3 mt-2 text-right">
                <a href="forgot-password.html">Esqueci minha senha</a>
            </p>

            <div class="row">
                <button type="submit" class="mb-2 mr-2 btn-transition btn btn-block btn-outline-secondary btn-flat shadow-sm">Entrar</button>
            </div>

            <div class="row">
                <div class="check-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">
                        Lembrar credenciais
                    </label>
                </div>
            </div>

            <?= form_close() ?>

            <?php if (!empty($login_error)) : ?>
                <div class="alert alert-danger">
                    <div><?= $login_error ?></div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>

<?= $this->endSection() ?>