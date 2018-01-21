<div class="inputGroup inputGroup-{{name}}">
	#if(showLabel):
		<label class="label" for="{{name}}">{{label}}</label>
	#endif
	<input type="text" name="{{name}}" id="{{name}}" class="input {{class}}" value="{{value}}" placeholder="{{label}}" style="{{style}}" {{required}}>
</div>
