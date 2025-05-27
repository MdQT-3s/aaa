<body>
	<div id="colorlib-page">
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
				<?= $menu->createHtml() ?>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="contact-section px-md-2  pt-5">
				<div class="container">
					<div class="row d-flex contact-info">
						<div class="col-md-12 mb-1">
							<h2 class="h3">Авторизация</h2>
						</div>
					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">
							<form action="login.php" method="post" class="bg-light p-5 contact-form">
								<div class="form-group">
									<input type="login"
										class="form-control <?= !empty($user->validation_login) ? 'is-invalid' : '' ?>"
										placeholder="Your Login" name="login"
										value="<?= $user->login ?? '' ?>">
									<?php if (!empty($user->validation_login)): ?>
										<div class="invalid-feedback">
											<?= $user->validation_login ?>
										</div>
									<?php endif; ?>
								</div>


								<div class="form-group">
									<input type="password"
										class="form-control <?= !empty($user->validation_password) ? 'is-invalid' : '' ?>"
										placeholder="Пароль"
										name="password">
									<?php if (!empty($user->validation_password)): ?>
										<div class="invalid-feedback">
											<?= $user->validation_password ?>
										</div>
									<?php endif; ?>
								</div>
								<div class="form-group">
									<input type="submit" value="Вход" class="btn btn-primary py-3 px-5">
								</div>
							</form>
						</div>
					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<!-- loader -->

</body>

</html>