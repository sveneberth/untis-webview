<!DOCTYPE html>
<html lang="<?= translator::getLanguage() ?>" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow, noarchive">

	<title>{{page_title}} &ndash; Untis Web View</title>

	<meta name="description" content="{{meta_desc}}">
	<meta name="keywords" content="{{meta_keys}}">
	<meta name="auhor" content="{{meta_author}}">
	<meta name="publisher" content="{{meta_author}}">
	<meta name="copyright" content="{{meta_author}}">

	<link rel="shortcut icon" href="{{TEMPLATE_URL}}/img/favicon_48.png">

	<!-- css -->
	<link rel="stylesheet" href="{{TEMPLATE_URL}}/static/css/style.min.css" media="all">
	{{CSS-FILES}}

	<!-- links -->
	<link rel="canonical" href="{{canonical_URL}}">
	#if(prev_URL):<link rel="prev" href="{{prev_URL}}">#endif
	#if(next_URL):<link rel="next" href="{{next_URL}}">#endif
</head>
<body id="top">

	<article class="wrap">
		<input type="checkbox" id="nav-trigger" class="nav-trigger">
		<label for="nav-trigger" class="nav-trigger-btn"><?= file_get_contents(TEMPLATE_PATH . '/static/img/burger.svg') ?></label>
		<nav class="bar">
			<div class="barGroup barGroup-vCenter barGroup-vNav">
				<a class="bar-link <?= $app->site == 'home' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/">Home</a>
				<a class="bar-link <?= $app->site == 'ueber' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/ueber"><?= __('about') ?></a>
				<?php
					if (!$app->user->isLogin())
					{
						?>
							<a class="bar-link <?= $app->site == 'login' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/login">Login</a>
						<?php
					}
					else
					{
						?>
							<a class="bar-link <?= $app->site == 'vertretungsplan' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/vertretungsplan"><?= __('substituteplan') ?></a>
							<a class="bar-link <?= $app->site == 'profil' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/profil"><?= __('profile') ?></a>
							<a class="bar-link <?= $app->site == 'admin' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/admin"><?= __('admin') ?></a>
							<a class="bar-link <?= $app->site == 'login' && @_GET['task'] == 'logout' ? 'is-active' : '' ?>" href="{{MAIN_URL}}/login?task=logout"><?= __('logout') ?></a>
						<?php
					}
				?>
			</div>
		</nav>

		<div class="bind">
			<div class="content">
				{{page_content}}
			</div>
		</div>
	</article>


	<!-- js -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script src="{{TEMPLATE_URL}}/static/js/jquery-3.2.1.min.js"></script>
	<script src="{{MAIN_URL}}/core/static/js/xenux.min.js"></script>
	<script src="{{MAIN_URL}}/js/template/static/js/script.js"></script>
	{{JS-FILES}}
</body>
</html>
