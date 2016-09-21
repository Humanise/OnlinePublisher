hui.ui.listen({
	//dragDrop : [
	//	{drag:'event',drop:'calendar'}
	//],

	//////// List ////////

	$open$list : function(item) {
		if (item.kind=='event') {
			this.loadEvent(item.id);
		}
	},
	$select$list : function(value) {
		var enabled = value && value.kind=='event';
		deleteItem.setEnabled(enabled);
		editItem.setEnabled(enabled);
	},
	$click$editItem : function() {
		var obj  = list.getFirstSelection();
		if (obj && obj.kind=='event') {
			this.loadEvent(obj.id);
		}
	},
	$click$deleteItem : function() {
		var obj  = list.getFirstSelection();
		if (obj && obj.kind=='event') {
			this.deleteEvent(obj.id);
		}
	},

	///////////// Other ///////////

	loadEvent : function(id) {
		hui.ui.request({parameters:{id:id},url:'data/LoadEvent.php',$success:'loadEvent'});
	},

	$drop$event$calendar : function(dragged,dropped) {
		//alert(Object.toJSON(dragged));
		//alert(Object.toJSON(dropped));
	},
	$success$loadEvent : function(data) {
		this.eventId = data.event.id;
		eventFormula.setValues(data.event);
		eventCalendars.setValue(data.calendars);
		deleteEvent.setEnabled(true);
		eventWindow.show();
		eventFormula.focus();
	},

	$click$cancelEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
	},
	$submit$eventFormula : function() {
		var data = eventFormula.getValues();
		data.id = this.eventId;
		if (data.startdate) {
			data.startdate=Math.round(data.startdate.getTime()/1000);
		} else {
			hui.ui.showMessage({text:{en:'The start date is required',da:'Startdatoen skal udfyldes'},duration:2000});
			eventFormula.focus();
			return;
		}
		if (data.enddate) {
			data.enddate=Math.round(data.enddate.getTime()/1000);
		} else {
			hui.ui.showMessage({text:{en:'The end date is required',da:'Slutdatoen skal udfyldes'},duration:2000});
			eventFormula.focus();
			return;
		}
		if (data.calendars.length<1) {
			hui.ui.showMessage({text:{en:'At least one kalendar must be selected',da:'Mindst een kalender skal vælges'},duration:2000});
			eventFormula.focus();
			return;
		}
		hui.ui.request({url:'actions/SaveEvent.php',$success:'saveEvent',json:{data:data}});
	},
	$success$saveEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
		list.refresh();
	},

	$click$newEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		var selection = selector.getValue();
		if (selection.kind=='calendar') {
			eventCalendars.setValue([selection.value]);
		}
		eventWindow.show();
		deleteEvent.setEnabled(false);
		eventFormula.focus();
	},
	$click$deleteEvent : function() {
		this.deleteEvent(this.eventId);
	},
	deleteEvent : function(id) {
		hui.ui.request({url:'actions/DeleteEvent.php',$success:'deleteEvent',parameters:{id:id}});
	},
	$success$deleteEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
		list.refresh();
	}

});