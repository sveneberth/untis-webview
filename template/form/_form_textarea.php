<div class="inputGroup inputGroup-{{name}}">
	#if(showLabel):
		<label class="label" for="{{name}}">{{label}}</label>
	#endif
	<textarea name="{{name}}" id="{{name}}" class="textarea textarea-vBlock {{class}}" placeholder="{{label}}" style="{{style}}" {{required}}>{{value}}</textarea>
</div>
