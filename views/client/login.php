<div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Đăng nhập</h3>
                        <form action="<?= BASE_URL ?>/login" method="POST">
                            <input type="hidden" name="csrf_token"
                                value='<?= ($_SESSION["csrf_token"] ?? "") ?>'>
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" name="username"
                                    value="<?= ($username ?? '') ?>" class="form-control">
                                <?php if (isset($errors["username"])): ?>
                                    <div class="text-danger">
                                        <?= $errors["username"] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="password"
                                    value="<?= ($password ?? '') ?>" class="form-control">
                                <?php if (isset($errors["password"])): ?>
                                    <div class="text-danger">
                                        <?= $errors["password"] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input"
                                    id="remember">
                                <label class="form-check-label" for="remember"> Ghi nhớ đăng nhập</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>