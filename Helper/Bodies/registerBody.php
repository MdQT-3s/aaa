<body>
    <div id="colorlib-page">
        <aside id="colorlib-aside" role="complementary" class="js-fullheight">
            <nav id="colorlib-main-menu" role="navigation">
                <?= $menu->createHtml() ?>
            </nav>
        </aside> <!-- END COLORLIB-ASIDE -->
        <div id="colorlib-main">
            <section class="contact-section px-md-2 pt-5">
                <div class="container">
                    <div class="row d-flex contact-info">
                        <div class="col-md-12 mb-1">
                            <h2 class="h3">Регистрация</h2>
                        </div>

                    </div>
                    <div class="row block-9">
                        <div class="col-lg-6 d-flex">

                            <form action="register.php" method="post" class="bg-light p-5 contact-form">
                                <div class="form-group">
                                    <input type="text" class="form-control <?= isset($user->validation_name) ? 'is-invalid' : '' ?>"
                                        placeholder="Name" name="name" value="<?= $old['name'] ?? '' ?>">
                                    <?php if (isset($user->validation_name)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_name ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control <?= !empty($user->validation_surname) ? 'is-invalid' : '' ?>"
                                        placeholder="Surname" name="surname" value="<?= $old['surname'] ?? ''?>">
                                    <?php if (!empty($user->validation_surname)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_surname ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control <?= !empty($user->validation_patronymic) ? 'is-invalid' : '' ?>"
                                        placeholder="patronymic if there is " name="patronymic" value="<?= $old['patronymic'] ?? '' ?>">
                                    <?php if (!empty($user->validation_patronymic)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_patronymic ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control <?= !empty($user->validation_login) ? 'is-invalid' : '' ?>"
                                        placeholder="login" name="login" value="<?= $old['login'] ?? ''?>">
                                    <?php if (!empty($user->validation_login)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_login ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control <?= !empty($user->validation_email) ? 'is-invalid' : '' ?>"
                                        placeholder="Email" name="email" value="<?= $old['email'] ?? ''?>">
                                    <?php if (!empty($user->validation_email)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_email ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control <?= !empty($user->validation_password) ? 'is-invalid' : '' ?>"
                                        placeholder="password" name="password">
                                    <?php if (!empty($user->validation_password)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_password ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control <?= !empty($user->validation_passwordrepeat) ? 'is-invalid' : '' ?>"
                                        placeholder="password_repeat" name="password_repeat">
                                    <?php if (!empty($user->validation_passwordrepeat)): ?>
                                        <div class="invalid-feedback">
                                            <?= $user->validation_password_repeat ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input<?= empty($old['rules']) ? 'is-invalid' : '' ?>" type="checkbox" value="1" id="rules"
                                            aria-describedby="invalidCheck3Feedback" required>
                                        <label class="form-check-label" for="rules">
                                            Rules
                                        </label>
                                        <div id="rulesFeedback" class="invalid-feedback">
                                            Необходимо согласиться с правилами регистрации.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Регистрация" class="btn btn-primary py-3 px-5">
                                </div>
                            </form>

                        </div>


                    </div>
                </div>
            </section>
        </div>
    </div>


</body>

</html>