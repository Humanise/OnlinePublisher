hui.ui.listen({
  $ready : function() {
    hui.ui.tellContainers('changeSelection','service:start');
  },
  $clickIcon$newsList : function(info) {
    window.open(info.data.url);
  },
  $clickIcon$taskList : function(info) {
    if (info.data.action=='edit') {
      document.location='../../Template/Edit.php?id='+info.data.id;
    }
    if (info.data.action=='view') {
      document.location='../../Services/Preview/?id='+info.data.id;
    }
  },

  $clickIcon : function(info) {
    if (info.key=="expand") {
      info.tile.toggleFullScreen();
    }
  },

  $clickIcon$taskTile : function(info) {
    if (info.key=="expand") {
      info.tile.toggleFullScreen();
      issuePages.expand();
    }
  },
  $clickIcon$developmentTile : function(info) {
    if (info.key=="expand") {
      info.tile.toggleFullScreen();
      developmentPages.expand();
    }
    else if (info.key=="next") {
      developmentPages.next();
    } else if (info.key=="previous") {
      developmentPages.previous();
    }
  },

  $click$userSettings : function() {
    settingsPanel.position(userSettings);
    settingsPanel.show();
  },
  $click$saveSettings : function() {
    settingsPanel.hide();
  },
  $submit$feedbackForm : function() {
    var values = feedbackForm.getValues();
    hui.ui.msg({text:'Sender besked...',busy:true});
    sendFeedback.disable();
    hui.ui.request({
      url : 'data/SendFeedback.php',
      parameters : values,
      $failure : function() {
        hui.ui.msg.fail({text:'Det lykkedes desværre ikke at sende beskeden'})
        sendFeedback.enable();
      },
      $success : function() {
        feedbackForm.reset();
        hui.ui.hideMessage();
        sendFeedback.enable();
        feedbackPages.next();
      }
    })
  },


  $clickIcon$helpTile : function(info) {
    info.tile.toggleFullScreen();
    userManual.setSize(info.tile.isFullScreen() ? 128 : 64);
    contact.setSize(info.tile.isFullScreen() ? 128 : 64);
  },
  $click$userManual : function() {
    window.open('http://www.in2isoft.dk/support/onlinepublisher/');
  },
  $click$contact : function() {
    window.open('http://www.in2isoft.dk/kontakt/');
  },

  // Password...

  $click$changePassword : function() {
    settingsPanel.hide();
    passwordBox.show();
    passwordFormula.focus();
  },
  $click$cancelPassword : function() {
    passwordFormula.reset();
    passwordBox.hide();
  },
  $submit$passwordFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.old) || hui.isBlank(values.password) || hui.isBlank(values.password2)) {
      hui.ui.msg.fail({text:'Alle felter skal udfyldes'});
      passwordFormula.focus();
      return;
    }
    if (values.password!==values.password2) {
      hui.ui.msg.fail({text:'De to kodeord er ikke ens'});
      passwordFormula.focus();
      return;
    }
    submitPassword.disable();
    hui.ui.msg({text:'Ændrer kodeord...',busy:true});
    hui.ui.request({
      url : 'data/ChangePassword.php',
      parameters : values,
      $failure : function() {
        hui.ui.msg.fail({text:'Det lykkedes desværre ikke at ændre kodeordet'})
        submitPassword.enable();
      },
      $success : function() {
        hui.ui.msg.success({text:'Kodeordet er nu ændret'})
        passwordFormula.reset();
        submitPassword.enable();
        passwordBox.hide();
      }
    })
  },

  // Setttings...

  $click$saveSettings : function() {
    var values = settingsFormula.getValues();
    hui.ui.request({
      url : 'data/UpdateSettings.php',
      parameters : values,
      $success : function() {
        if (window.parent) {
          window.parent.location.reload();
        } else {
          window.location.reload();
        }
      }
    })
  }
})