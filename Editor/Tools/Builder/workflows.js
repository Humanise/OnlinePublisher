hui.ui.listen({
  workflowId : null,

  $open$selector : function(item) {
    if (item.kind=='workflow') {
      this.editWorkflow(item.value);
    }
  },
  $select$selector : function(item) {
    if (item && item.kind=='workflow') {
      pages.goTo('workflows');
      this.openWorflow(item.value);
    } else {
      pages.goTo('list')
    }
  },

  openWorflow : function(id) {
    this.workflowId = id;
    hui.ui.request({
      message : {start:{en:'Loading...',da:'Henter...'},delay:300},
      parameters : {id:id},
      url : '../../Services/Model/LoadObject.php',
      $object : function(source) {
        workflowRecipe.setValue(source.recipe);
      }.bind(this)
    });
  },
  $click$runWorkflow : function() {
    hui.ui.request({
      url : 'actions/RunWorkflow.php',
      message : {start:{en:'Running...',da:'Afvikler...'},delay:300},
      parameters : {recipe : workflowRecipe.getValue()},
      $object : function(obj) {
        workflowResult.setValue(JSON.stringify(obj,2,2));
        hui.log(obj);
      }
    });
  },

  editWorkflow : function(id) {
    workflowFormula.reset();
    hui.ui.request({
      message : {start:{en:'Loading source...',da:'Henter kilde...'},delay:300},
      parameters : {id:id},
      url : '../../Services/Model/LoadObject.php',
      $object : function(source) {
        this.workflowId = source.id;
        workflowFormula.setValues(source);
        deleteWorkflow.setEnabled(true);
        workflowWindow.show();
      }.bind(this)
    });
  },
  $click$saveWorkflowRecipe : function(button) {
    var recipe = hui.ui.get('workflowRecipe');
    button.setEnabled(false);
    hui.ui.request({
      url : 'actions/SaveWorkflow.php',
      json : { data: {id : this.workflowId, recipe : recipe.getValue()} },
      $success : function() {
        button.setEnabled(true);
      }
    })
  },

  // Workflow
  $click$cancelWorkflow : function() {
    workflowFormula.reset();
    workflowWindow.hide();
  },
  $click$saveWorkflow : function() {
    var data = workflowFormula.getValues();
    data.id = this.workflowId;
    hui.ui.request({
      url : 'actions/SaveWorkflow.php',
      json : {data:data},
      $success : function() {
        this.workflowId = null;
        workflowFormula.reset();
        workflowWindow.hide();
        selectorSource.refresh();
      }.bind(this)
    });
  },
  $click$deleteWorkflow : function() {
    hui.ui.request({
      url : '../../Services/Model/DeleteObject.php',
      parameters : {id : this.workflowId},
      $success : function() {
        this.workflowId = null;
        workflowFormula.reset();
        workflowWindow.hide();
        selectorSource.refresh();
      }.bind(this)
    });
  },

  $click$newWorkflow : function() {
    this.workflowId = null;
    workflowFormula.reset();
    workflowWindow.show();
    deleteWorkflow.setEnabled(false);
    workflowFormula.focus();
  }
});