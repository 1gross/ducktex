BX.saleOrderAjax = { // bad solution, actually, a singleton at the page

	BXCallAllowed: false,

	options: {},
	indexCache: {},
	controls: {},

	modes: {},
	properties: {},

	// called once, on component load
	init: function(options)
	{
		var ctx = this;
		this.options = options;

		window.submitFormProxy = BX.proxy(function(){
			ctx.submitFormProxy.apply(ctx, arguments);
		}, this);

		BX(function(){
			ctx.initDeferredControl();
		});
		BX(function(){
			ctx.BXCallAllowed = true; // unlock form refresher
		});

		this.controls.scope = BX('order_form_div');

		// user presses "add location" when he cannot find location in popup mode
		BX.bindDelegate(this.controls.scope, 'click', {className: '-bx-popup-set-mode-add-loc'}, function(){

			var input = BX.create('input', {
				attrs: {
					type: 'hidden',
					name: 'PERMANENT_MODE_STEPS',
					value: '1'
				}
			});

			BX.prepend(input, BX('ORDER_FORM'));

			ctx.BXCallAllowed = false;
			submitForm();
		});
	},

	cleanUp: function(){

		for(var k in this.properties)
		{
			if (this.properties.hasOwnProperty(k))
			{
				if(typeof this.properties[k].input != 'undefined')
				{
					BX.unbindAll(this.properties[k].input);
					this.properties[k].input = null;
				}

				if(typeof this.properties[k].control != 'undefined')
					BX.unbindAll(this.properties[k].control);
			}
		}

		this.properties = {};
	},

	addPropertyDesc: function(desc){
		this.properties[desc.id] = desc.attributes;
		this.properties[desc.id].id = desc.id;
	},

	// called each time form refreshes
	initDeferredControl: function()
	{
		var ctx = this,
			k,
			row,
			input,
			locPropId,
			m,
			control,
			code,
			townInputFlag,
			adapter;

		// first, init all controls
		if(typeof window.BX.locationsDeferred != 'undefined'){

			this.BXCallAllowed = false;

			for(k in window.BX.locationsDeferred){

				window.BX.locationsDeferred[k].call(this);
				window.BX.locationsDeferred[k] = null;
				delete(window.BX.locationsDeferred[k]);

				this.properties[k].control = window.BX.locationSelectors[k];
				delete(window.BX.locationSelectors[k]);
			}
		}

		for(k in this.properties){

			// zip input handling
			if(this.properties[k].isZip){
				row = this.controls.scope.querySelector('[data-property-id-row="'+k+'"]');
				if(BX.type.isElementNode(row)){

					input = row.querySelector('input[type="text"]');
					if(BX.type.isElementNode(input)){
						this.properties[k].input = input;

						// set value for the first "location" property met
						locPropId = false;
						for(m in this.properties){
							if(this.properties[m].type == 'LOCATION'){
								locPropId = m;
								break;
							}
						}

						if(locPropId !== false){
							BX.bindDebouncedChange(input, function(value){

								input = null;
								row = null;

								if(BX.type.isNotEmptyString(value) && /^\s*\d+\s*$/.test(value) && value.length > 3){

									ctx.getLocationByZip(value, function(locationId){
										ctx.properties[locPropId].control.setValueByLocationId(locationId);
									}, function(){
										try{
											ctx.properties[locPropId].control.clearSelected(locationId);
										}catch(e){}
									});
								}
							});
						}
					}
				}
			}

			// location handling, town property, etc...
			if(this.properties[k].type == 'LOCATION')
			{

				if(typeof this.properties[k].control != 'undefined'){

					control = this.properties[k].control; // reference to sale.location.selector.*
					code = control.getSysCode();

					// we have town property (alternative location)
					if(typeof this.properties[k].altLocationPropId != 'undefined')
					{
						if(code == 'sls') // for sale.location.selector.search
						{
							// replace default boring "nothing found" label for popup with "-bx-popup-set-mode-add-loc" inside
							control.replaceTemplate('nothing-found', this.options.messages.notFoundPrompt);
						}

						if(code == 'slst')  // for sale.location.selector.steps
						{
							(function(k, control){

								// control can have "select other location" option
								control.setOption('pseudoValues', ['other']);

								// insert "other location" option to popup
								control.bindEvent('control-before-display-page', function(adapter){

									control = null;

									var parentValue = adapter.getParentValue();

									// you can choose "other" location only if parentNode is not root and is selectable
									if(parentValue == this.getOption('rootNodeValue') || !this.checkCanSelectItem(parentValue))
										return;

									var controlInApater = adapter.getControl();

									if(typeof controlInApater.vars.cache.nodes['other'] == 'undefined')
									{
										controlInApater.fillCache([{
											CODE:		'other', 
											DISPLAY:	ctx.options.messages.otherLocation, 
											IS_PARENT:	false,
											VALUE:		'other'
										}], {
											modifyOrigin:			true,
											modifyOriginPosition:	'prepend'
										});
									}
								});

								townInputFlag = BX('LOCATION_ALT_PROP_DISPLAY_MANUAL['+parseInt(k)+']');

								control.bindEvent('after-select-real-value', function(){

									// some location chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});
								control.bindEvent('after-select-pseudo-value', function(){

									// option "other location" chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '1';
								});

								// when user click at default location or call .setValueByLocation*()
								control.bindEvent('before-set-value', function(){
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});

								// restore "other location" label on the last control
								if(BX.type.isDomNode(townInputFlag) && townInputFlag.value == '1'){

									// a little hack: set "other location" text display
									adapter = control.getAdapterAtPosition(control.getStackSize() - 1);

									if(typeof adapter != 'undefined' && adapter !== null)
										adapter.setValuePair('other', ctx.options.messages.otherLocation);
								}

							})(k, control);
						}
					}
				}
			}
		}

		this.BXCallAllowed = true;
	},

	checkMode: function(propId, mode){

		//if(typeof this.modes[propId] == 'undefined')
		//	this.modes[propId] = {};

		//if(typeof this.modes[propId] != 'undefined' && this.modes[propId][mode])
		//	return true;

		if(mode == 'altLocationChoosen'){

			if(this.checkAbility(propId, 'canHaveAltLocation')){

				var input = this.getInputByPropId(this.properties[propId].altLocationPropId);
				var altPropId = this.properties[propId].altLocationPropId;

				if(input !== false && input.value.length > 0 && !input.disabled && this.properties[altPropId].valueSource != 'default'){

					//this.modes[propId][mode] = true;
					return true;
				}
			}
		}

		return false;
	},

	checkAbility: function(propId, ability){

		if(typeof this.properties[propId] == 'undefined')
			this.properties[propId] = {};

		if(typeof this.properties[propId].abilities == 'undefined')
			this.properties[propId].abilities = {};

		if(typeof this.properties[propId].abilities != 'undefined' && this.properties[propId].abilities[ability])
			return true;

		if(ability == 'canHaveAltLocation'){

			if(this.properties[propId].type == 'LOCATION'){

				// try to find corresponding alternate location prop
				if(typeof this.properties[propId].altLocationPropId != 'undefined' && typeof this.properties[this.properties[propId].altLocationPropId]){

					var altLocPropId = this.properties[propId].altLocationPropId;

					if(typeof this.properties[propId].control != 'undefined' && this.properties[propId].control.getSysCode() == 'slst'){

						if(this.getInputByPropId(altLocPropId) !== false){
							this.properties[propId].abilities[ability] = true;
							return true;
						}
					}
				}
			}

		}

		return false;
	},

	getInputByPropId: function(propId){
		if(typeof this.properties[propId].input != 'undefined')
			return this.properties[propId].input;

		var row = this.getRowByPropId(propId);
		if(BX.type.isElementNode(row)){
			var input = row.querySelector('input[type="text"]');
			if(BX.type.isElementNode(input)){
				this.properties[propId].input = input;
				return input;
			}
		}

		return false;
	},

	getRowByPropId: function(propId){

		if(typeof this.properties[propId].row != 'undefined')
			return this.properties[propId].row;

		var row = this.controls.scope.querySelector('[data-property-id-row="'+propId+'"]');
		if(BX.type.isElementNode(row)){
			this.properties[propId].row = row;
			return row;
		}

		return false;
	},

	getAltLocPropByRealLocProp: function(propId){
		if(typeof this.properties[propId].altLocationPropId != 'undefined')
			return this.properties[this.properties[propId].altLocationPropId];

		return false;
	},

	toggleProperty: function(propId, way, dontModifyRow){

		var prop = this.properties[propId];

		if(typeof prop.row == 'undefined')
			prop.row = this.getRowByPropId(propId);

		if(typeof prop.input == 'undefined')
			prop.input = this.getInputByPropId(propId);

		if(!way){
			if(!dontModifyRow)
				BX.hide(prop.row);
			prop.input.disabled = true;
		}else{
			if(!dontModifyRow)
				BX.show(prop.row);
			prop.input.disabled = false;
		}
	},

	submitFormProxy: function(item, control)
	{
		var propId = false;
		for(var k in this.properties){
			if(typeof this.properties[k].control != 'undefined' && this.properties[k].control == control){
				propId = k;
				break;
			}
		}

		// turning LOCATION_ALT_PROP_DISPLAY_MANUAL on\off

		if(item != 'other'){

			if(this.BXCallAllowed){

				this.BXCallAllowed = false;
				submitForm();
			}

		}
	},

	getPreviousAdapterSelectedNode: function(control, adapter){

		var index = adapter.getIndex();
		var prevAdapter = control.getAdapterAtPosition(index - 1);

		if(typeof prevAdapter !== 'undefined' && prevAdapter != null){
			var prevValue = prevAdapter.getControl().getValue();

			if(typeof prevValue != 'undefined'){
				var node = control.getNodeByValue(prevValue);

				if(typeof node != 'undefined')
					return node;

				return false;
			}
		}

		return false;
	},
	getLocationByZip: function(value, successCallback, notFoundCallback)
	{
		if(typeof this.indexCache[value] != 'undefined')
		{
			successCallback.apply(this, [this.indexCache[value]]);
			return;
		}

		ShowWaitWindow();

		var ctx = this;

		BX.ajax({

			url: this.options.source,
			method: 'post',
			dataType: 'json',
			async: true,
			processData: true,
			emulateOnload: true,
			start: true,
			data: {'ACT': 'GET_LOC_BY_ZIP', 'ZIP': value},
			//cache: true,
			onsuccess: function(result){

				CloseWaitWindow();
				if(result.result){

					ctx.indexCache[value] = result.data.ID;

					successCallback.apply(ctx, [result.data.ID]);

				}else
					notFoundCallback.call(ctx);

			},
			onfailure: function(type, e){

				CloseWaitWindow();
				// on error do nothing
			}

		});
	}

}

$(document).on('change', 'input, textarea', function () {
	checkRequiredOrderFields($(this));
});

function checkRequiredOrderFields(el) {
	let order = $('.order-block'),
		orderSubmit = $('.order-submit'),
		form = $('#ORDER_FORM'),
		fieldName = el.attr('name');
	if (form.attr('data-order') === 'y') {
		form.removeAttr('data-order');
		form.append('<input type="hidden" name="validate_field" value="y">');
		return false;
	}
	if (order.length > 0) {
		let errors = false;
		$('.inp-field').each(function () {
			let value = $(this).val(),
				error = false,
				type = $(this).attr('data-type'),
				fName = $(this).attr('name'),
				parent = $(this).parent(),
				required = $(this).hasClass('required'),
				bonusSum = $('.bonus-order-sum');

			switch (type) {
				case 'tel':
					let phone = value.replace(/[^.\d]+/g,"");
					if (phone.length !== 11 && required === true) {
						error = true;
					}
					break;
				case 'email':
					let pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,6}\.)?[a-z]{2,6}$/i;
					if (pattern.test(value) === false && required === true) {
						error = true;
					}
					break;
				case 'location':
					if (value.length === 0 && required === true && String(fName).indexOf('PROP') >= 0) {
						error = true;
					}
					break;
				case 'checkbox':
					let code = $(this).attr('data-code');

					if (code === 'IS_BONUS_SYSTEM') {
						if ($(this).is(':checked')) {
							bonusSum.css('display', 'flex');
						} else {
							bonusSum.css('display', 'none');
						}
					}
					break;
				case 'textarea':
					parent = parent.parent();
					if (value.length <= 6 && required === true) {
						error = true;
					}
					break;
			}
			if (error === true && required === true) {
				errors = true;
			}
			if (error === true && fName === fieldName && type !== 'location') {
				parent.addClass('error-field');
			} else {
				if (parent.hasClass('error-field')) {
					parent.removeClass('error-field');
				}
			}
		});
		if (errors !== true) {
			orderSubmit.removeAttr('disabled');
		} else {
			orderSubmit.attr('disabled', 'disabled');
		}
	}
}