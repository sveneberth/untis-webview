<div class="inputGroup inputGroup-{{name}}">
	#if(showLabel):
		<label class="label" for="{{name}}">{{label}}</label>
	#endif
	<input type="password" name="{{name}}" id="{{name}}" class="input {{class}}" placeholder="{{label}}" style="{{style}}" {{required}}>
</div>
