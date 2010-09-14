var partController = {
	$interfaceIsReady : function() {
		this.buildWindow();
	},
	buildWindow : function() {
		var form = document.forms.PartForm;
		var win = ui.Window.create({title:'Vælg person',width:400,close:false});
		var toolbar = ui.Toolbar.create({labels:false});
		var searchField = ui.SearchField.create({name:'search',adaptive:true});
		toolbar.add(searchField);
		win.add(toolbar);
		var list = ui.List.create({name:'list',maxHeight:300});
		list.listen({$selectionChanged:function(obj) {
			document.forms.PartForm.personId.value=obj.id;
			partController.preview();
		}})
		searchField.listen({$valueChanged:function() {list.resetState()}});
		win.add(list);
		win.show();
		var src = new ui.Source({
			url:'../../Services/Model/ListPersons.php?windowSize=10',
			parameters:[
				{key:'query',value:'@search.value'},
				{key:'windowPage',value:'@list.window.page'},
				{key:'direction',value:'@list.sort.direction'},
				{key:'sort',value:'@list.sort.key'}
			]
		});
		list.setSource(src);
	},
	preview : function() {
		op.part.utils.updatePreview({
			node:$('part_person_container'),
			form:$(document.forms.PartForm),
			type:'person'
		});
	}
}

ui.listen(partController);