hui.ui.listen({
	$ready : function() {
		group.setValue(partToolbar.partForm.group.value);
		height.setValue(partToolbar.partForm.height.value);
		showTitle.setValue(partToolbar.partForm.showTitle.value);
		framed.setValue(partToolbar.partForm.framed.value);
		variant.setValue(partToolbar.partForm.variant.value);
	},
	$valueChanged$height : function() {
		this.update();
	},
	$valueChanged$group : function() {
		this.update();
	},
	$valueChanged$showTitle : function() {
		this.update();
	},
	$valueChanged$framed : function() {
		this.update();
	},
	$valueChanged$variant : function() {
		this.update();
	},
	update : function() {
		partToolbar.partForm.height.value=height.getValue();
		partToolbar.partForm.showTitle.value=showTitle.getValue();
		partToolbar.partForm.framed.value=framed.getValue();
		partToolbar.partForm.group.value=group.getValue();
		partToolbar.partForm.variant.value=variant.getValue();
		partToolbar.preview();
	}
});