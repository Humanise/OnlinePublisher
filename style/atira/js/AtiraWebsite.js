if (!Atira) {var Atira = {};}
if (!Atira.Website) {Atira.Website={};}

Atira.Website.Poster = function(options) {
	this.options = options || {random:false};
	this.poster = n2i.firstByClass(document.body,'placard');
	this.inner = n2i.firstByClass(document.body,'inner_placard');
	this.num = 1;
	this.count = n2i.byClass(this.poster,'poster').length;
	this.duration = 6000;
	this.active = false;
	this.paused = false;
	this.addBehavior();
	this.ignite();
	this.next();
}

Atira.Website.Poster.prototype = {
	ignite : function() {
		if (this.options.random) {
			this.num = Math.round(Math.random()*(this.count-1))+1;			
		}
		this.poster.scrollLeft=(this.num-1)*898;
		this.inner.style.visibility='visible';
	},
	addBehavior : function() {
		n2i.listen(this.poster,'mouseover',this.pause.bind(this));
		n2i.listen(this.poster,'mouseout',this.resume.bind(this));
	},
	next : function() {
		var self = this;
		window.clearTimeout(this.timer);
		this.timer = window.setTimeout(function() {if (self.paused) return;self.render()},this.duration);
	},
	render : function() {
		if (this.num==this.count) {
			this.num=1;
		} else {
			this.num++;
		}
		var self = this;
		this.active = true;
			n2i.animate(this.poster,'scrollLeft',(this.num-1)*898,1500,{ease:n2i.ease.slowFastSlow,onComplete:function() {
				self.active = false;
				self.next()
			}});
	},
	pause : function() {
		this.paused = true;
		window.clearTimeout(this.timer);
	},
	resume : function() {
		this.paused = false;
		if (!this.active) {
			this.next();
		}
	}
}


Atira.Website.Ticker = function() {
	this.items = [];
	this.activeItem = -1;
	this.interval = 4000;
	this.addBehavior();
}

Atira.Website.Ticker.prototype = {
	addBehavior : function() {
		this.base = n2i.firstByClass(document.body,'ticker');
		if (!this.base) return false;
		this.item = n2i.firstByClass(this.base,'item');
		this.previousArrow = n2i.firstByClass(this.base,'previous');
		this.nextArrow = n2i.firstByClass(this.base,'next');
		var self = this;
		this.base.onmouseover = function() {
			self.pause();
		}
		this.base.onmouseout = function() {
			self.proceed();
		}
		this.nextArrow.onmousedown = this.nextArrow.ondblclick = function() {
			self.showNext();
		}
		this.previousArrow.onmousedown = this.previousArrow.ondblclick = function() {
			self.showPrevious();
		}
		return true;
	},

	addItem : function(title,url,variant) {
		this.items[this.items.length] = {title:title,url:url,variant:variant};
	},

	start : function() {
		this._showNext();
	},

	showNext : function() {
		this.activeItem++;
		if (this.activeItem>=this.items.length) {
			this.activeItem = 0;
		}
		this.updateUI();
	},

	showPrevious : function() {
		this.activeItem--;
		if (this.activeItem<0) this.activeItem = this.items.length-1;
		this.updateUI();
	},

	updateUI : function() {
		var item = this.items[this.activeItem];
		if (!item) return;
		this.item.innerHTML = item.title;
		this.item.className = 'item '+item.variant;
		this.item.href = item.url;
	},

	_showNext : function() {
		this.pause();
		this.showNext();
		this.proceed();
	},

	proceed : function() {
		var self = this;
		this.timer = window.setTimeout(function() {
			self._showNext();
		},this.interval
		);
	},

	pause : function() {
		if (this.timer) {
			window.clearTimeout(this.timer);
		}
	}
}