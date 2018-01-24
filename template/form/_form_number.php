<div class="inputGroup inputGroup-{{name}}">
	#if(showLabel):
		<label class="label" for="{{name}}">{{label}}</label>
	#endif
	<input type="number" name="{{name}}" id="{{name}}" class="input {{class}}" min="{{min_number}}" max="{{max_number}}" value="{{value}}" placeholder="{{label}}" style="{{style}}" {{required}}>
</div>
