ui.listen({
	mailinglistId : 0,
	groupId : 0,
	personId : 0,
	dragDrop : [
		{drag:'person',drop:'persongroup'}
	],
	
	$interfaceIsReady : function() {
		sendEmail.setEnabled(false);
	},
	
	$resolveImageUrl : function(img,width,height) {
		return '../../../util/images/?id='+img.id+'&maxwidth='+width+'&maxheight='+height+'&format=jpg';
	},
	
	$selectionChanged$selector : function(obj) {
		if (obj.value=='mailinglist') {
			list.setSource(mailinglistListSource);
		} else if (obj.value=='persongroup') {
			list.setSource(groupListSource);
		} else {
			list.setSource(personListSource);
		}
		sendEmail.setEnabled(obj.kind=='mailinglist');
	},
	
	$drop$person$persongroup : function(dragged,target) {
		var data = {
			personId : dragged.id,
			personGroupId : target.value
		}
		In2iGui.json({data:data},'AddPersonToGroup.php','addPersonToGroup');
	},
	
	$listRowWasOpened$list : function(obj) {
		var data = {id:obj.id};
		if (obj.kind=='mailinglist') {
			mailinglistFormula.reset();
			deleteMailinglist.setEnabled(false);
			saveMailinglist.setEnabled(false);
			In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadMailinglist');
		} else if (obj.kind=='persongroup') {
			groupFormula.reset();
			deleteGroup.setEnabled(false);
			saveGroup.setEnabled(false);
			saveGroup.setEnabled(false);
			In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadGroup');
		} else if (obj.kind=='person') {
			personFormula.reset();
			deletePerson.setEnabled(false);
			savePerson.setEnabled(false);
			In2iGui.json({data:data},'LoadPerson.php','loadPerson');
		}
	},
	
	///////////////////////////////////// Person /////////////////////////////
	
	$click$newPerson : function() {
		this.personId = 0;
		personFormula.reset();
		personEmails.reset();
		personPhones.reset();
		personEditor.show();
		deletePerson.setEnabled(false);
		personFirstname.focus();
	},
	$click$newMailinglist : function() {
		this.mailinglistId = 0;
		mailinglistFormula.reset();
		mailinglistEditor.show();
		deleteMailinglist.setEnabled(false);
		mailinglistTitle.focus();
	},
	$click$newGroup : function() {
		this.groupId = 0;
		groupFormula.reset();
		groupEditor.show();
		deleteGroup.setEnabled(false);
		groupTitle.focus();
	},
	
	$click$saveMailinglist : function() {
		var title = mailinglistTitle.getValue();
		var note = mailinglistNote.getValue();
		if (title.length==0) {
			mailinglistTitle.focus();
			return;
		}
		var data = {id:this.mailinglistId,title:title,note:note};
		In2iGui.json({data:data},'SaveMailinglist.php','saveMailinglist');
	},
	$success$saveMailinglist : function(t) {
		mailinglistEditor.hide();
		mailinglistSource.refresh();
		list.refresh();
	},
	
	$click$saveGroup : function() {
		var title = groupTitle.getValue();
		var note = groupNote.getValue();
		if (title.length==0) {
			groupTitle.focus();
			return;
		}
		var data = {id:this.groupId,title:title,note:note};
		In2iGui.json({data:data},'SaveGroup.php','saveGroup');
	},
	$success$saveGroup : function(t) {
		groupEditor.hide();
		personGroupSource.refresh();
		list.refresh();
		personGroups.refresh();
	},
	
	
	$click$savePerson : function() {
		var person = {
			id : this.personId,
			firstname : personFirstname.getValue(),
			middlename : personMiddlename.getValue(),
			surname : personSurname.getValue(),
			note : personNote.getValue(),
			jobtitle : personJobtitle.getValue(),
			nickname : personNickname.getValue(),
			initials : personInitials.getValue(),
			streetname : personStreetname.getValue(),
			zipcode : personZipcode.getValue(),
			city : personCity.getValue(),
			country : personCountry.getValue(),
			webaddress : personWebaddress.getValue(),
			searchable : personSearchable.getValue(),
			sex : personSex.getValue(),
			image_id : (personImage.getObject() ? personImage.getObject().id : null)
		}
		var emails = personEmails.getObjects();
		var phones = personPhones.getObjects();
		var groups = personGroups.getValues();
		var mailinglists = personMailinglists.getValues();
		var data = {person:person,emails:emails,phones:phones,groups:groups,mailinglists:mailinglists};
		In2iGui.json({data:data},'SavePerson.php','savePerson');
	},
	$success$savePerson : function(t) {
		personEditor.hide();
		list.refresh();
	},
	
	$submit$mailinglistFormula : function() {
		this.click$saveMailinglist();
	},
	$success$loadMailinglist : function(data) {
		this.mailinglistId = data.id;
		mailinglistTitle.setValue(data.title);
		mailinglistNote.setValue(data.note);
		mailinglistEditor.show();
		deleteMailinglist.setEnabled(true);
		saveMailinglist.setEnabled(true);
		mailinglistTitle.focus();
	},
	$success$loadGroup : function(data) {
		this.groupId = data.id;
		groupTitle.setValue(data.title);
		groupNote.setValue(data.note);
		groupEditor.show();
		deleteGroup.setEnabled(true);
		saveGroup.setEnabled(true);
		groupTitle.focus();
	},
	$success$loadPerson : function(data) {
		this.personId = data.person.id;
		personFirstname.setValue(data.person.firstname);
		personMiddlename.setValue(data.person.middlename);
		personSurname.setValue(data.person.surname);
		personNote.setValue(data.person.note);
		personNickname.setValue(data.person.nickname);
		personJobtitle.setValue(data.person.jobtitle);
		personInitials.setValue(data.person.initials);
		personStreetname.setValue(data.person.streetname);
		personZipcode.setValue(data.person.zipcode);
		personCity.setValue(data.person.city);
		personCountry.setValue(data.person.country);
		personSearchable.setValue(data.person.searchable);
		personWebaddress.setValue(data.person.webaddress);
		personSex.setValue(data.person.sex);
		personImage.setObject(data.person.image_id>0 ? {id:data.person.image_id} : null);
		personEmails.setObjects(data.emails);
		personPhones.setObjects(data.phones);
		personMailinglists.setValues(data.mailinglists);
		personGroups.setValues(data.groups);
		
		deletePerson.setEnabled(true);
		savePerson.setEnabled(true);
		
		personEditor.show();
		personFirstname.focus();
	},
	getImageUrl$personImage : function(picker) {
		var obj = picker.getObject();
		return '../../../util/images/?id='+obj.id+'&maxwidth=48&maxheight=48&format=jpg';
	},
	
	$click$deleteMailinglist : function() {
		In2iGui.json({data:{id:this.mailinglistId}},'../../Services/Model/DeleteObject.php','deleteMailinglist');
	},
	$success$deleteMailinglist : function(t) {
		mailinglistEditor.hide();
		mailinglistFormula.reset();
		mailinglistSource.refresh();
		list.refresh();
	},
	
	$click$deleteGroup : function() {
		In2iGui.json({data:{id:this.groupId}},'../../Services/Model/DeleteObject.php','deleteGroup');
	},
	$success$deleteGroup : function(t) {
		groupEditor.hide();
		groupFormula.reset();
		personGroupSource.refresh();
		list.refresh();
	},
	
	$click$deletePerson : function() {
		In2iGui.json({data:{id:this.personId}},'../../Services/Model/DeleteObject.php','deletePerson');
	},
	$success$deletePerson : function() {
		personEditor.hide();
		personFormula.reset();
		list.refresh();
	},
	
	$click$cancelMailinglist : function() {
		this.mailinglistId = 0;
		mailinglistFormula.reset();
		mailinglistEditor.hide();
	},
	
	$click$cancelGroup : function() {
		this.groupId = 0;
		groupFormula.reset();
		groupEditor.hide();
	},
	
	$click$cancelPerson : function() {
		this.personId = 0;
		personFormula.reset();
		personEditor.hide();
	},
	
	////////////////////////// Send email ////////////////////////
	
	$click$sendEmail : function() {
		var data = {id:selector.getValue().value};
		In2iGui.json({data:data},'GetMailinglistEmails.php','sendEmail');
	},
	
	$success$sendEmail : function(data) {
		var mails = []
		for (var i=0; i < data.length; i++) {
			mails.push(data[i].address);
		};
		if (mails.length>0) {
			document.location.href='mailto:?bcc='+mails.join(',');
		}
	}
});