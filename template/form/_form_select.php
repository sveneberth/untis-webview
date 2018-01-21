<div class="inputGroup inputGroup-{{name}}">
		#if(showLabel):
		<label class="label" for="{{name}}">{{label}}</label>
	#endif
	<select name="{{name}}" id="{{name}}" class="select {{class}}" style="{{style}}" size="1">
		{{options}}
	</select>
</div>
