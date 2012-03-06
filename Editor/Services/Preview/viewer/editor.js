op.Editor = {
	$ready : function() {
		var ctrl = this.getToolbarController();
		if (ctrl) { // May not be loaded yet
			ctrl.pageDidLoad(op.page.id);
		}
		if (hui.location.hasHash('edit')) {
			if (templateController!==undefined) {
				templateController.edit();
			}
		}
	},
	getToolbarController : function() {
		try {
			return window.parent.controller;
		} catch (e) {
			hui.log('Unable to find toolbar controller');
		}
	},
	$partWasMoved : function(info) {
		var data = hui.string.fromJSON(info.dragged.getAttribute('data'));
		var p = {
			sectionId : data.id,
			rowIndex : info.rowIndex,
			columnIndex : info.columnIndex,
			sectionIndex : info.partIndex
		}
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/MoveSection.php',
			parameters : p,
			onSuccess : function() {
				info.onSuccess();
				this._signalChange();
			}.bind(this)
		})
	},
	$partChanged : function() {
		this._signalChange();
	},
	_signalChange : function() {
		var ctrl = this.getToolbarController();
		if (ctrl) {
			ctrl.pageDidChange();
		}
	},
	
	editProperties : function() {
		if (!this.propertiesWindow) {
			var win = this.propertiesWindow = hui.ui.Window.create({width:300,title:'Info',icon:'common/info',padding:10,variant:'dark'});
			var form = this.propertiesFormula = hui.ui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'TextField',options:{label:'Titel:',key:'title'}},
				{type:'TextField',options:{label:'Beskrivelse:',key:'description',multiline:true}},
				{type:'TextField',options:{label:'Nøgelord:',key:'keywords'}},
				{type:'TextField',options:{label:'Sti:',key:'path'}},
				{type:'DropDown',options:{
					label:'Sprog:',
					key:'language',
					items:[{value:'',title:'Intet'},{value:'DA',title:'Dansk'},{value:'EN',title:'Engelsk'},{value:'DE',title:'Tysk'}]
				}}
			]);
			var buttons = group.createButtons();
			var more = hui.ui.Button.create({text:'Mere...'});
			more.click(this.moreProperties.bind(this));
			buttons.add(more);

			var update = hui.ui.Button.create({text:'Opdater',highlighted:true});
			update.click(this.saveProperties.bind(this));
			buttons.add(update);
			win.add(form);
		}
		hui.ui.request({
			url:'data/LoadPageProperties.php',
			parameters:{id:op.page.id},
			message : {start:'Henter sidens info...',delay:300},
			onJSON:function(obj) {
				this.propertiesFormula.setValues(obj);
				this.propertiesWindow.show();
			}.bind(this)
		})
	},
	saveProperties : function() {
		var values = this.propertiesFormula.getValues();
		values.id = op.page.id;
		hui.ui.request({
			url:'data/SavePageProperties.php',
			parameters:values,
			message : {start:'Gemmer sidens info...',delay:300},
			onSuccess:function() {
				this.propertiesFormula.reset();
				this.propertiesWindow.hide();
			}.bind(this)
		});
	},
	moreProperties : function() {
		if (!window.parent) {
			hui.log('The window has no parent! '+window.location);
			return;
		}
		window.parent.location='../../../Tools/Sites/?pageInfo='+op.page.id;
	},
	
	
	editDesign : function() {
		if (!this.designWindow) {
			hui.ui.request({
				url : 'data/LoadDesignInfo.php',
				parameters : {id:op.page.id},
				message : {start:'Henter design info...',delay:300},
				onJSON : function(parameters) {
					if (parameters.length>0) {
						this._buildDesignForm(parameters);
						this.designWindow.show();
					} else {
						hui.ui.showMessage({text:'Dette design har ingen indstillinger',duration:3000})
					}
				}.bind(this)
			})
		} else {
			this.designWindow.show();
		}
	},
	
	_buildDesignForm : function(parameters) {
		var win = this.designWindow = hui.ui.Window.create({width:300,title:'Design',icon:'common/info',padding:10,variant:'dark'});
		var form = this.designFormula = hui.ui.Formula.create();
		form.listen({
			$submit : function() {
				var values = form.getValues();
				hui.ui.request({
					url : 'data/SaveDesignParameters.php',
					parameters : {id:op.page.id,parameters:hui.string.toJSON(values)},
					onSuccess : function() {
						hui.ui.showMessage({text:'Indstillingerne er gemt', duration:3000});
						document.location.reload();
					}
				})
			}
		})
		this.designGroup = this.designFormula.createGroup();
		
		var group = this.designFormula.createGroup();
		var buttons = group.createButtons();
		var btn = hui.ui.Button.create({text:'Opdater',submit:true});
		buttons.add(btn);
		
		win.add(form);
		
		for (var i=0; i < parameters.length; i++) {
			var parm = parameters[i];
			if (parm.type=='text' || parm.type=='color') {
				var field = hui.ui.TextField.create({key:parm.key,label:parm.label,value:parm.value});
				this.designGroup.add(field);
			}
			if (parm.type=='selection') {
				parm.options.unshift({});
				var field = hui.ui.DropDown.create({key:parm.key,label:parm.label,value:parm.value,items:parm.options});
				this.designGroup.add(field);
			}
		};
	}
}

hui.ui.listen(op.Editor);