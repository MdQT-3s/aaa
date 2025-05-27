<?php include_once 'Helper/scripts/patternScript.php'; ?>
<script>
		$(document).ready(function () {
			$('#rules').click(function (e) {
				$(this).toggleClass('is-invalid');
				$(this).toggleClass('is-valid');
				$('#rulesFeedback').toggleClass('d-none');
			})
		})

	</script>