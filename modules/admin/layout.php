
<div class="grid grid-row">
	<div class="grid-col grid-col-3-vResponsive">
		<div class="sidebar sidebar-vLeft sidebar-vPanel">
			<ul class="menu-list">
				<li class="menu-item"><a class="menu-link <?= $method == 'upload' ? ' is-active' : '' ?>"
					href="{{MAIN_URL}}/<?= $modulename ?>/upload"><?= __('upload') ?></a></li>

				<li class="menu-item"><a class="menu-link <?= $method == 'news' ? ' is-active' : '' ?>"
					href="{{MAIN_URL}}/<?= $modulename ?>/news"><?= __('news') ?></a></li>

				<li class="menu-item"><a class="menu-link <?= $method == 'settings' ? ' is-active' : '' ?>"
					href="{{MAIN_URL}}/<?= $modulename ?>/settings"><?= __('settings') ?></a></li>

				<li class="menu-item"><a class="menu-link <?= $method == 'users' ? ' is-active' : '' ?>"
					href="{{MAIN_URL}}/<?= $modulename ?>/users"><?= __('users') ?></a></li>

			</ul>
		</div>
	</div>
	<div class="grid-col">
		<div class="mainPanel">
			<h1 class="headline">{{headlinePrefix}}{{headline}}{{headlineSuffix}}</h1>
			{{messages}}
			{{text}}
			{{main}}
		</div>
	</div>
</div>

