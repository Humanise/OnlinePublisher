hui.onReady(['op'], function() {
  op.CalendarTemplate = function() {
    this.days = [];
    this.arrows = [];
    this.maxDayEvents = [0,0,0,0,0,0,0];
    this.analyze();
    this.createArrows();
    this.windowWasScrolled();

    var self = this;
    hui.listen(window,'scroll',
      function(e) {
        self.windowWasScrolled();
      }
    );
    hui.listen(window,'resize',
      function(e) {
        self.windowWasScrolled();
      }
    );
  }

  op.CalendarTemplate.prototype.windowWasScrolled = function() {
    var top = hui.window.getScrollTop();
    var height = hui.window.getViewHeight();
    var bottom = top+height;
    for (var i=0;i<this.days.length;i++) {
      if (this.maxDayEvents[i]>bottom) {
        var left = hui.position.getLeft(this.days[i]);
        this.arrows[i].style.top=(bottom-20)+'px';
        this.arrows[i].style.left=(left)+'px';
        this.arrows[i].style.display='block';
      } else {
        this.arrows[i].style.display='none';
      }
    }
  }

  op.CalendarTemplate.prototype.createArrows = function() {
    for (var i=0; i < this.days.length; i++) {
      this.arrows[i] = hui.build('div',{'class':'calendar_arrow',parent:document.body});
    };
  }

  op.CalendarTemplate.prototype.analyze = function() {
    this.days = hui.get.byClass(document.body,'day');
    for (var i=0;i<this.days.length;i++) {
      var events = hui.get.byClass(this.days[i],'event');
      for (var j=0;j<events.length;j++) {
        var top = hui.position.getTop(events[j]);
        if (top>this.maxDayEvents[i]) {
          this.maxDayEvents[i]=top;
        }
      }
    }
  }

  new op.CalendarTemplate();
});