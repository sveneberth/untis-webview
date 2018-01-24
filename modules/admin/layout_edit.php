#if(new):
	<p><?= __('here can you add a new user') ?></p>
#else:
	<?php
		echo '<p>' . __('here can you edit the user') . '</p>';
	?>
#endif

<div class="form">
	{{form}}
</div>
