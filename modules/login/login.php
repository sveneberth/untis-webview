#if(logout):
	<p class="logout-message box-shadow"><?= __('logout successful') ?></p>
#endif

<div class="login-wrapper">

	<?php if($action == 'login'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			{{message}}
			{{form}}

		</div>
		<p><a class="forgotpassword" href="{{MAIN_URL}}/login?task=forgotpassword"><?= __('forgotPassword') ?>?</a>
		<?php if(parse_bool($app->getOption('users_can_register'))): ?>
			| <a class="center register" href="{{MAIN_URL}}/login?task=register"><?= __('register') ?></a>
		<?php endif; ?>
	<?php endif; ?>


	<?php if($action == 'register'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			{{message}}
			{{form}}
		</div>
	<?php endif; ?>


	<?php if($action == 'forgotpassword'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			<p class="info"><?= __('forgotPassword info') ?></p>

			{{message}}
			{{form}}
		</div>
	<?php endif; ?>


	<?php if($action == 'resetpassword'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			<p class="info"><?= __('forgotPassword action info') ?></p>

			{{message}}
			{{form}}
		</div>
	<?php endif; ?>

	<?php if($action == 'setpassword'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			<p class="info"><?= __('setPassword info') ?></p>

			{{message}}
			{{form}}
		</div>
	<?php endif; ?>

	<?php if($action == 'confirm'): ?>
		<div class="login-box box-shadow">
			<h2>{{page_name}}</h2>

			#if(confirmSuccessful):
				<p class="info"><?= __('account confirmation successful') ?></p>
			#else:
				<p class="info"><?= __('account confirmation failed') ?></p>
			#endif
			{{message}}
			{{form}}
		</div>
	<?php endif; ?>

</div>
