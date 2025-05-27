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
							<h2 class="h3"><?= $post->id ? 'Редактирование поста' : 'Создание поста' ?></h2>
						</div>

					</div>
					<div class="row block-9">
						<div class="col-lg-6 d-flex">

							<form action="<?= $response->getLink('post-create.php', ['id' => $post->id]) ?>"
								method="POST" class="bg-light p-5 contact-form">

								<div class="form-group">
									<input type="text"
										class="form-control <?= !empty($post->validation_title) ? 'is-invalid' : '' ?>"
										placeholder="Post title" name="title" value="<?= $post->title ?? '' ?>">
									<?php if (!empty($post->validation_title)): ?>
										<div class="invalid-feedback"><?= $post->validation_title ?></div>
									<?php endif; ?>
								</div>


								<div class="form-group">
									<input type="text"
										class="form-control <?= !empty($post->validation_preview) ? 'is-invalid' : '' ?>"
										placeholder="Post preview" name="preview" value="<?= $post->preview ?? '' ?>">
									<?php if (!empty($post->validation_preview)): ?>
										<div class="invalid-feedback"><?= $post->validation_preview ?></div>
									<?php endif; ?>
								</div>


								<div class="form-group">
									<textarea name="content" cols="0" rows="10"
										class="form-control <?= !empty($post->validation_content) ? 'is-invalid' : '' ?>"
										placeholder="Post content"><?= PostClass::convert_br($post->content ?? '') ?></textarea>
									<?php if (!empty($post->validation_content)): ?>
										<div class="invalid-feedback"><?= $post->validation_content ?></div>
									<?php endif; ?>
								</div>

								<div class="form-group">
									<input type="submit"
										value="<?= $post->id ? 'Сохранить изменения' : 'Создать пост' ?>"
										class="btn btn-primary py-3 px-5">

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