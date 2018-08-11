hui.on(['hui'],function(hui) {

  var SearchField = function(options) {
    this.element = options.element;
    if (!this.element) {
      return;
    }
    this.nodes = hui.collect(this.nodes,this.element);
    this._attach();
  }

  SearchField.prototype = {
    nodes : {
      icon : '.layout_search_icon',
      text : '.layout_search_text'
    },
    _attach : function() {
      hui.on(this.nodes.icon,'tap',this._toggle.bind(this));
      hui.listen(this.nodes.text,'focus',this._focus.bind(this));
      hui.listen(this.nodes.text,'blur',this._blur.bind(this));
      // Dont remember why - some browser sets focus on svgs
      this.nodes.icon.setAttribute("focusable","false");
    },
    _toggle : function(e) {
      e.preventDefault();
      hui.cls.toggle(this.element,'layout_search_active');
      try {
        this.nodes.text.focus();
      } catch (e) {
        hui.log(e)
      }
    },
    _focus : function() {
      hui.cls.add(this.element,'layout_search_active');
    },
    _blur : function() {
      var self = this;
      window.setTimeout(function() {
        hui.cls.remove(self.element,'layout_search_active');
      },200)
    }
  }
  new SearchField({element:hui.find('.layout_search')});

});
