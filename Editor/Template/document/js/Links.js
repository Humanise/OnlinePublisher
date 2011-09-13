hui.ui.listen({
	$ready : function() {
		
	},
	$click$close : function() {
		window.parent.location='Edit.php';
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='deleteLink') {
			hui.ui.confirmOverlay({
				element : info.node,
				text : 'Er du sikker?',
				okText : 'Ja, fjern',
				cancelText : 'Nej',
				onOk : function() {
					hui.ui.request({
						url : 'data/DeleteLink.php',
						parameters : {id:info.row.id},
						onSuccess : function() {
							list.refresh();
						}
					});
				}
			});
		}
		else if (info.data.action=='pageInfo') {
			parent.location='../../Tools/Sites/?pageInfo='+info.data.id;
		}
		else if (info.data.action=='editPage') {
			parent.location='../../Template/Edit.php?id='+info.data.id;
		}
		else if (info.data.action=='viewPage') {
			parent.location='../../Services/Preview/?id='+info.data.id;
		}
		else if (info.data.action=='fileInfo') {
			parent.location='../../Tools/Files/?fileInfo='+info.data.id;
		}
		else if (info.data.action=='visitUrl') {
			window.open(info.data.url);
		}
	}
})