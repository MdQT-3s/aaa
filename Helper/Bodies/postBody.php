<body>
	<div id="colorlib-page">
		<a href="post.php" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
				<?= $menu->createHtml() ?>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->

		<div id="colorlib-main" style="margin-left: 0; padding-left: 0;">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container" style="max-width: 100%; padding-left: 20px;">
					<div class="row">
						<div class="col-lg-12 px-md-3 py-5" style="text-align: left; margin-left: 0; padding-left: 0;">
							<?php if (isset($post) && $post->id): ?>
								<!-- Блок с кнопками редактирования и удаления -->
								<div class="mb-3 d-flex gap-3 align-items-center">
									<?php if (!$user->isGuest && ($user->id == ($post->author->id ?? 0) )): ?>
										<a href="<?= $response->getLink('post-create.php', ['id' => $post->id]) ?>"
											class="text-warning mr-3" style="font-size: 1.8em;" title="Редактировать">🖍</a>
									<?php endif; ?>
								</div>
								<div class="mb-3 d-flex gap-3 align-items-center">
									<?php if (($user->id == $post->author->id && empty($comments)) || $user->isAdmin): ?>
										<a href="<?= $response->getLink('post.php', ['id' => $post->id, 'action' => 'delete_post']) ?>"
											class="text-danger" style="font-size: 1.8em;" title="Удалить"
											onclick="return confirm('Вы уверены?')">🗑</a>
									<?php endif; ?>
								</div>

								<!-- Содержимое поста -->
								<div class="post" style="text-align: left; margin-left: 0;">
									<h1 class="mb-3" style="text-align: left;"><?= $post->author->login ?></h1>
									<div class="meta-wrap" style="text-align: left;">
										<p class="meta">
											<span>Автор: <?= $post->author->login ?></span>
											<span>Дата: <?= $post->formatPostDate($post->created_at) ?></span>
											<span>Комментарии: <?= count($comments ?? []) ?></span>
										</p>
									</div>
									<p style="text-align: left;">
										<?= nl2br($post->content ?? 'Содержимое поста отсутствует') ?>
									</p>

									<!-- Блок комментариев -->
									<div class="comments pt-5 mt-5" style="text-align: left; margin-left: 0;">
										<h5 class="mb-5 font-weight-bold" style="text-align: left;"><?= count($comments ?? []) ?> комментариев</h5>
										<?php if (!empty($comments)): ?>
											<?php $commentClass->renderCommentsTree($comments, $post, $user, $response, $canDeleteComment); ?>
										<?php endif; ?>
									</div>

									<!-- Форма комментария -->
									<div class="comment-form-wrap pt-5" style="text-align: left; margin-left: 0;">
										<?php if (!$user->isGuest && !$user->isAdmin): ?>
											<h3 class="mb-5" style="text-align: left;">Оставьте комментарий</h3>
											<form action="<?= $response->getLink('post.php', ['id' => $post->id]) ?>" method="POST"
												class="p-3 p-md-5 bg-light" style="text-align: left;">
												<input type="hidden" name="token" value="<?= $user->token ?>">
												<div class="form-group">
													<label for="message">Сообщение</label>
													<textarea name="comment_text" id="message" cols="30" rows="5" class="form-control"
														required style="text-align: left;"></textarea>
												</div>
												<div class="form-group">
													<input type="submit" name="add_comment" value="Отправить" class="btn py-3 px-4 btn-primary">
												</div>
											</form>
										<?php elseif ($user->isAdmin): ?>
											<div class="alert alert-warning" style="text-align: left;">Администратор не может оставлять комментарии.</div>
										<?php else: ?>
											<div class="alert alert-info pt-5" style="text-align: left;">
												Чтобы оставить комментарий, <a href="<?= $response->getLink('login.php') ?>">войдите
													в аккаунт</a>.
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php else: ?>
								<div class="alert alert-danger" style="text-align: left;">Пост не найден или был удален</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</body>