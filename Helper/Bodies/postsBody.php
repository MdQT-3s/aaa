<body>

	<div id="colorlib-page">
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
				<?= $menu->createHtml() ?>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="ftco-no-pt ftco-no-pb">
				<div class="container">
					<div class="row d-flex">
						<div class="col-xl-8 col-md-8 py-5 px-md-2">
							<div class="row">
								<div class="col-md-12 col-lg-12">
									<?php if (!$user->isGuest && !$user->isAdmin): ?>
										<div>
											<a href="<?= $response->getLink('post-create.php') ?>" class="btn btn-outline-success">
												📝 Создать пост
											</a>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="row pt-md-4">
                                <?php if (!empty($posts)): ?>
                                    <?php foreach ($posts as $postItem): ?>
									<!-- один пост/превью -->
									<div class="col-md-12 col-xl-12">
										<div class="blog-entry ftco-animate d-md-flex">
											<!-- 
											изображение для поста 
											<a href="single.html" class="img img-2"
											style="background-image: url(images/image_1.jpg);"></a> 
										-->
											<div class="text text-2 pl-md-4">
												<h3 class="mb-2"><a href="<?= $response->getLink('post.php', ['id' => $postItem->id]) ?>">
														<?= $postItem->title ?></a></h3>
												<div class="meta-wrap">
													<p class="meta">
														<!-- <img src='avatar.jpg' /> -->
														<span class="text text-3"><?= $postItem->user->login ?></span>
														<span><?= $post->formatPostDate($postItem->created_at) ?></span>
													</p>
												</div>
												<p class="mb-4"><?= $postItem->preview ?? '' ?></p>
												<div class="d-flex pt-1  justify-content-between">
													<div>
                                                            <a href="<?= $response->getLink('post.php', ['id' => $postItem->id]) ?>" 
                                                               class="btn-custom">
                                                               Подробнее...
                                                            </a>
                                                        </div>
                                                        <?php if (!$user->isGuest && isset($postItem->author_id) && $user->id == $postItem->author_id):?>
                                                            <div>
                                                                <a href="<?= $response->getLink('post-create.php', ['id' => $postItem->id]) ?>"
                                                                    class="text-warning"
                                                                    title="Редактировать">🖍</a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-md-12">
                                        <div class="alert alert-info">Нет доступных постов</div>
                                    </div>
                                <?php endif; ?>
                            </div> <!-- END row pt-md-4 -->
                        </div>
                    </div>
                </div>
            </section>
        </div> <!-- END COLORLIB-MAIN -->
    </div> <!-- END COLORLIB-PAGE -->
</body>
</html>