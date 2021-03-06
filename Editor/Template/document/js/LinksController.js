var linkController = {
  $ready : function() {
    var editLink = hui.location.getInt('editLink');
    if (editLink) {
      this._loadLink(editLink);
    }
    hui.listen(document.body,'mouseup',function(e) {
      e = hui.event(e);
      var section = e.findByClass('editor_section');
      if (section) {
        this.selectedTextInfo = hui.string.fromJSON(section.getAttribute('data'));
      } else {
        this.selectedTextInfo = null;
      }
    }.bind(this));
  },

  linkId : null,

  newLink : function(options) {
    options = options || {};
    this.linkId = null;
    if (this.selectedTextInfo) {
      this.linkPartId = this.selectedTextInfo.part;
      this._highlightPart(this.linkPartId);
    } else {
      this.linkPartId = null;
    }
    linkScope.setEnabled(this.selectedTextInfo!=null);
    linkFormula.reset();
    linkFormula.setValues({
      text : hui.selection.getText(),
      scope : this.linkPartId ? 'part' : 'page',
      url : options.url
    });
    linkWindow.show();
    deleteLink.disable();
    saveLink.setText({en:'Create',da:'Opret'});
    linkFormula.focus();
  },
  $click$cancelLink : function() {
    linkFormula.reset();
    linkWindow.hide();
    this._clearPartHighlight();
  },
  _highlightPart : function(id) {
    this._clearPartHighlight();
    var node = hui.get('part'+id);
    if (node) {
      hui.window.scrollTo({element:node});
      hui.cls.add(node,'editor_part_highlighted');
      this._highlightedPart = node;
    }
  },
  _clearPartHighlight : function() {
    if (this._highlightedPart) {
      hui.cls.remove(this._highlightedPart,'editor_part_highlighted');
    }
    this._highlightedPart = null;
  },
  $userClosedWindow$linkWindow : function() {
    this._clearPartHighlight();
  },
  $valueChanged$linkPage : function() {
    linkUrl.reset();
    linkFile.reset();
    linkEmail.reset()
  },
  $valueChanged$linkFile : function() {
    linkUrl.reset();
    linkPage.reset();
    linkEmail.reset()
  },
  $valueChanged$linkUrl : function() {
    linkFile.reset();
    linkPage.reset();
    linkEmail.reset()
  },
  $valueChanged$linkEmail : function() {
    linkFile.reset();
    linkPage.reset();
    linkUrl.reset()
  },
  $submit$linkFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.text)) {
      hui.ui.msg.fail({text:{en:'Please fill in the text to link from',da:'Skriv den tekst hvor der skal linkes fra'}});
      form.focus();
      return;
    }
    if (hui.isBlank(values.email) && hui.isBlank(values.url) && values.page==null && values.file==null) {
      hui.ui.msg.fail({text:{en:'Please select the target of the link',da:'Du skal vælge et mål for linket'}});
      form.focus();
      return;
    }
    var p = {text : values.text, description : values.description, id : this.linkId};
    if (values.page) {
      p.type = 'page';
      p.value = values.page;
    } else if (values.file) {
      p.type = 'file';
      p.value = values.file;
    } else if (!hui.isBlank(values.url)) {
      p.type = 'url';
      p.value = values.url;
    } else if (!hui.isBlank(values.email)) {
      p.type = 'email';
      p.value = values.email;
    }
    if (values.scope=='part' && this.linkPartId) {
      p.partId = this.linkPartId;
    }
    hui.ui.request({
      url : 'data/SaveLink.php',
      parameters : p,
      message : {start:{en:'Inserting link...',da:'Indsætter link...'},delay:300},
      $success : function() {
        document.location='Editor.php';
      }
    });
    linkFormula.reset();
    linkWindow.hide();
  },
  _loadLink : function(id) {
    this.linkId = null;
    linkFormula.reset();
    hui.ui.request({
      url : 'data/LoadLink.php',
      parameters : {id:id},
      message : {start:{en:'Loading link...',da:'Henter link...'},delay:300},
      $object : function(obj) {
        this.linkId = obj.id;
        if (obj.partId) {
          this.linkPartId = obj.partId;
          hui.log('Using partId from data');
        } else if (this.selectedTextInfo) {
          this.linkPartId = this.selectedTextInfo.part;
          hui.log('Using partId from selected text');
        } else {
          hui.log('Part id is undefined');
        }
        if (this.linkPartId) {
          this._highlightPart(this.linkPartId);
        }
        hui.log('load link: Part id is: '+this.linkPartId);
        linkFormula.setValues(obj);
        linkWindow.show();
        deleteLink.enable();
        saveLink.setText({en:'Update',da:'Opdater'});
      }.bind(this)
    });
  },

  clickedLink : null,
  panelLinkInfo : null,

  _clearLinkFocus : function() {
    if (this.clickedLinkInfo) {
      hui.cls.remove(this.clickedLinkInfo.node,'editor_link_highlighted');
    }
  },

  linkWasClicked : function(info) {
    this._clearLinkFocus();
    this.clickedLinkInfo = info;
    var section = hui.closest('.editor_section', info.node);
    if (section) {
      this.selectedTextInfo = hui.string.fromJSON(section.getAttribute('data'));
    } else {
      hui.log('Section not found');
      this.selectedTextInfo = null;
    }
    if (this.selectedTextInfo) {
      this.linkPartId = this.selectedTextInfo.part;
      hui.log('link click: Part id is: '+this.linkPartId);
    }
    hui.cls.add(info.node,'editor_link_highlighted');
    hui.ui.request({
      url : 'data/LoadLinkInfo.php',
      parameters : {id:info.id},
      $object : function(obj) {
        this.panelLinkInfo = obj;
        linkInfo.setContent(obj.rendering);
        visitLink.setEnabled(obj.type=='page' || obj.type=='url');
        linkPanel.show({target: info.node});
      }.bind(this)
    })
  },
  linkMenu : function(info) {
    linkMenu.showAtPointer(info.event);
  },
  $click$cancelLinkPanel : function() {
    this._clearLinkFocus();
    linkPanel.hide();
    this.clickedLinkInfo = null;
  },
  $click$editLink : function() {
    this._clearLinkFocus();
    linkPanel.hide();
    this._loadLink(this.clickedLinkInfo.id);
  },
  $click$visitLink : function() {
    if (this.panelLinkInfo.type=='page') {
      parent.location='../Edit.php?id='+this.panelLinkInfo.targetId;
    }
    if (this.panelLinkInfo.type=='url') {
      alert(this.panelLinkInfo.targetValue)
      window.open(this.panelLinkInfo.targetValue);
    }
  },
  $click$limitLinkToPart : function() {
    hui.ui.request({
      url : 'data/BindLinkToPart.php',
      parameters : {linkId:this.clickedLinkInfo.id,partId:this.clickedLinkInfo.part},
      message : {start:{en:'Saving link...',da:'Gemmer link...'},delay:300},
      $success : function() {
        document.location.reload();
      }
    });
  },
  $click$deleteLinkPanel : function() {
    linkPanel.hide();
    this._deleteLink(this.clickedLinkInfo.id);
  },
  $click$deleteLink : function() {
    this._deleteLink(this.linkId);
    this.linkId = null;
    linkFormula.reset();
    linkWindow.hide();
  },
  _deleteLink : function(id) {
    hui.ui.request({
      url : 'data/DeleteLink.php',
      parameters : {id:id},
      message : {start:{en:'Deleting link...',da:'Sletter link...'},delay:300},
      $success : function() {
        document.location.reload();
      }
    });
  }
};

hui.ui.listen(linkController);