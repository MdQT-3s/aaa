<body>
	<div id="colorlib-page">
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
				<?= $menu->createHtml() ?>
			</nav>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="contact-section px-md-4 pt-5">
				<div class="container">
					<div class="row block-9">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 mb-1">
									<h3 class="h3">Пользователи</h3>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mb-1">
									<table class="table table-striped">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Name</th>
												<th scope="col">Surname</th>
												<th scope="col">Login</th>

												<th scope="col">E-mail</th>
												<th scope="col">Temp block</th>
												<th scope="col">Permanent block</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($users)):
												$counter = 1; ?>
												<?php foreach ($users as $userItem): ?>
													<tr>
														<th scope="row"><?= $counter++ ?></th>
														<td><?= $userItem['name'] ?></td>
														<td><?= $userItem['surname'] ?></td>
														<td><?= $userItem['login'] ?></td>
														<td><?= $userItem['email'] ?></td>
														<td>
															<?php if (!$userItem['isBlocked']): ?>
																<a href="temp-block.php?id=<?= $userItem['id'] ?>&token=<?= urlencode($user->token) ?>"
																	class="btn btn-outline-warning px-4">⏳ Block</a>
															<?php else: ?>
																<?php if ($userItem['isPermanentlyBlocked']): ?>
																	<span class="text-muted">Заблокирован навсегда</span>
																<?php else: ?>
																	<?php if (!empty($userItem['blockDate'])): ?>
																		<span class="text-muted">Заблокирован до <?= $userItem['blockDate']?></span>
																	<?php else: ?>
																		<span class="text-muted">Заблокирован (дата не указана)</span>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endif; ?>
														</td>
														<td>
															<?php if (!$userItem['isBlocked']): ?>
																<form method="POST"
																	action="perm-block.php?token=<?= urlencode($user->token) ?>"
																	style="display:inline;">
																	<input type="hidden" name="id" value="<?= $userItem['id'] ?>">
																	<button type="submit" class="btn btn-outline-danger px-4">📌
																		Block</button>
																</form>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="7" class="text-center">Пользователи не найдены</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
			</section>
		</div>
	</div>
</body>