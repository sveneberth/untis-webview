<section class="section">
	<h1 class="headline">{{headlinePrefix}}{{headline}}{{headlineSuffix}}</h1>
	{{messages}}
	<p><?= __('here can you edit your profile') ?></p>

	<div class="form">
		{{form}}
	</div>
</section>
<section class="section">
	<h3 class="subline"><?= __('last confirmed') ?></h3>
	{{messageConfirm}}
	<p>Dein Account wurde am {{datelastConfirmed}} bestätigt.
		In {{remainingDays}} Tagen muss es erneut bestätigt werden.<br>
		Bestätige ihn hier für weitere {{confirmValidity}} Tage.</p>
	<a class="btn btn-vDanger" href="{{MAIN_URL}}/profil?task=confirmRequest">Jetzt bestätigen</a>
</section>
