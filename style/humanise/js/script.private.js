/* ["style\/humanise\/js\/poster.js","style\/humanise\/js\/layout.js"] */
if(!op)var op={}
op.preview=!1,op.page=op.page||{id:null,path:null,template:null},op.ignite=function(){if(this.preview||(document.onkeydown=function(e){if(e=hui.event(e),e.returnKey&&e.shiftKey){e.stop()
var i
i=function(e){e=hui.event(e),e.returnKey&&(hui.unListen(document,"keyup",i),hui.browser.msie||op.user.internal?window.location=op.page.path+"Editor/index.php?page="+op.page.id:(e.stop(),op.showLogin()))},hui.listen(document,"keyup",i)}return!0},hui.request({url:op.context+"services/statistics/",parameters:{page:op.page.id,referrer:document.referrer,uri:document.location.href}})),hui.browser.msie7&&hui.onReady(function(){hui.cls.add(document.body.parentNode,"msie7")}),hui.browser.msie7||hui.browser.msie6){for(var e=hui.get.byClass(document.body,"shared_frame"),i=0;i<e.length;i++)e[i].style.width=e[i].clientWidth+"px",e[i].style.display="block"
for(var t=hui.get.byClass(document.body,"document_row"),i=t.length-1;i>=0;i--){for(var o=hui.build("table",{"class":t[i].className,style:t[i].style.cssText}),s=hui.build("tbody",{parent:o}),n=hui.build("tr",{parent:s}),a=hui.get.byClass(t[i],"document_column"),r=0;r<a.length;r++)for(var h=a[r],l=hui.build("td",{"class":h.className,parent:n,style:h.style.cssText});h.firstChild;)l.appendChild(h.firstChild)
t[i].parentNode.insertBefore(o,t[i]),hui.dom.remove(t[i])}}},op.showLogin=function(){if(this.loginBox)this.loginBox.show(),this.loginForm.focus()
else{if(this.loadingLogin)return void hui.log("Aborting, the box is loading")
this.loadingLogin=!0,hui.ui.showMessage({text:{en:"Loading...",da:"Indlæser..."},busy:!0,delay:300}),hui.ui.require(["Formula","Button","TextField"],function(){hui.ui.hideMessage()
var e=this.loginBox=hui.ui.Box.create({width:300,title:{en:"Access control",da:"Adgangskontrol"},modal:!0,absolute:!0,closable:!0,curtainCloses:!0,padding:10})
this.loginBox.addToDocument()
var i=this.loginForm=hui.ui.Formula.create()
i.listen({$submit:function(){if(e.isVisible()){var t=i.getValues()
op.login(t.username,t.password)}}})
var t=i.buildGroup(null,[{type:"TextField",options:{label:{en:"Username",da:"Brugernavn"},key:"username"}},{type:"TextField",options:{label:{en:"Password",da:"Kodeord"},key:"password",secret:!0}}]),o=t.createButtons(),s=hui.ui.Button.create({text:{en:"Forgot password?",da:"Glemt kode?"}})
s.listen({$click:function(){document.location=op.context+"Editor/Authentication.php?forgot=true"}}),o.add(s)
var n=hui.ui.Button.create({text:{en:"Cancel",da:"Annuller"}})
n.listen({$click:function(){i.reset(),e.hide(),document.body.focus()}}),o.add(n),o.add(hui.ui.Button.create({text:{en:"Log in",da:"Log ind"},highlighted:!0,submit:!0})),this.loginBox.add(i),this.loginBox.show(),window.setTimeout(function(){i.focus()},100),this.loadingLogin=!1,op.startListening()
var a=new hui.Preloader({context:hui.ui.context+"hui/icons/"})
a.addImages("common/success24.png"),a.load()}.bind(this))}},op.startListening=function(){hui.listen(window,"keyup",function(e){if(e=hui.event(e),e.escapeKey&&this.loginBox.isVisible()){this.loginBox.hide()
var i=hui.get.firstByTag(document.body,"a")
i&&(i.focus(),i.blur()),document.body.blur()}}.bind(this))},op.login=function(e,i){return hui.isBlank(e)||hui.isBlank(i)?(hui.ui.showMessage({text:{en:"Please fill in both fields",da:"Udfyld venligst begge felter"},duration:3e3}),void this.loginForm.focus()):void hui.ui.request({message:{start:{en:"Logging in...",da:"Logger ind..."},delay:300},url:op.context+"Editor/Services/Core/Authentication.php",parameters:{username:e,password:i},$object:function(e){e.success?(hui.ui.showMessage({text:{en:"You are now logged in",da:"Du er nu logget ind"},icon:"common/success",duration:4e3}),op.igniteEditor()):hui.ui.showMessage({text:{en:"The user was not found",da:"Brugeren blev ikke fundet"},icon:"common/warning",duration:4e3})},$failure:function(){hui.ui.showMessage({text:{en:"An internal error occurred",da:"Der skete en fejl internt i systemet"},icon:"common/warning",duration:4e3})}})},op.igniteEditor=function(){window.location=op.page.path+"Editor/index.php?page="+op.page.id},op.showImage=function(e){this.imageViewer||(this.imageViewer=hui.ui.ImageViewer.create({maxWidth:2e3,maxHeight:2e3,perimeter:40,sizeSnap:10}),this.imageViewer.listen(op.imageViewerDelegate)),this.imageViewer.clearImages(),this.imageViewer.addImage(e),this.imageViewer.show()},op.registerImageViewer=function(e,i){hui.get(e).onclick=function(){return op.showImage(i),!1}},op.imageViewerDelegate={$resolveImageUrl:function(e,i,t){var o=e.width?Math.min(i,e.width):i,s=e.height?Math.min(t,e.height):t
return op.page.path+"services/images/?id="+e.id+"&width="+o+"&height="+s+"&format=jpg&quality=100"}},void 0===op.part&&(op.part={}),op.feedback=function(e){hui.require(op.page.path+"style/basic/js/Feedback.js",function(){op.feedback.Controller.init(e)})},window.define&&define("op"),op.part.Formula=function(e){this.element=hui.get(e.element),this.id=e.id,this.inputs=e.inputs,hui.listen(this.element,"submit",this._send.bind(this))},op.part.Formula.prototype={_send:function(e){hui.stop(e)
for(var i=[],t=0;t<this.inputs.length;t++){var o=this.inputs[t],s=hui.get(o.id),n=o.validation
if(n.required&&hui.isBlank(s.value))return hui.ui.showMessage({text:n.message,duration:2e3}),void s.focus()
if("email"==n.syntax&&!hui.isBlank(s.value)){var a=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\\n".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA\n-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
if(!a.test(s.value))return hui.ui.showMessage({text:n.message,duration:2e3}),void s.focus()}i.push({label:o.label,value:s.value})}var r=op.page.path+"services/parts/formula/",h={id:this.id,fields:i}
hui.ui.showMessage({text:"Sender besked...",busy:!0}),hui.ui.request({url:r,json:{data:h},$success:this._success.bind(this),$failure:this._failure.bind(this)})},_success:function(){hui.ui.showMessage({text:"Beskeden er nu sendt",icon:"common/success",duration:2e3}),this.element.reset()},_failure:function(){hui.ui.showMessage({text:"Beskeden kunne desværre ikke afleveres",duration:5e3})}},window.define&&define("op.part.Formula"),op.part.Image=function(e){var i=this.element=hui.get(e.element),t=(i.src,i.parentNode)
t.style.position="relative",t.style.display="block"
var o=hui.build("img",{src:i.src+"&contrast=-20&brightness=80&blur=30",style:"position: absolute; left: 0; top: 0;",parent:t})
hui.animate({node:o,duration:1e3,delay:1e3,ease:hui.ease.flicker,css:{opacity:0}}),hui.listen(o,"mouseover",function(){hui.animate({node:o,duration:500,delay:0,ease:hui.ease.fastSlow,css:{opacity:1}})}),hui.listen(o,"mouseout",function(){hui.animate({node:o,duration:1e3,delay:1e3,ease:hui.ease.flicker,css:{opacity:0}})})},window.define&&define("op.part.Image"),op.part.Poster=function(e){this.options=hui.override({duration:1500,interval:5e3,delay:0},e),this.name=e.name,this.element=hui.get(e.element),this.container=hui.get.firstByClass(this.element,"part_poster_pages"),this.pages=hui.get.byClass(this.element,"part_poster_page"),this.index=0,this.indicators=[],this._buildNavigator(),this.options.editmode||window.setTimeout(this._callNext.bind(this),this.options.delay),hui.listen(this.element,"click",this._onClick.bind(this)),hui.ui.extend(this)},op.part.Poster.prototype={_buildNavigator:function(){this.navigator=hui.build("div",{"class":"part_poster_navigator",parent:this.element})
for(var e=0;e<this.pages.length;e++)this.indicators.push(hui.build("a",{parent:this.navigator,data:e,href:"javascript://","class":0==e?"part_poster_current":""}))},next:function(){var e=this.index+1
e>=this.pages.length&&(e=0),this.goToPage(e)},previous:function(){var e=this.index-1
0>e&&(e=this.pages.length-1),this.goToPage(e)},setPage:function(e){if(!(null===e||void 0===e||e==this.index||this.pages.length-1<e)){this.pages[this.index].style.display="none",this.pages[e].style.display="block",this.index=e
for(var i=0;i<this.indicators.length;i++)hui.cls.set(this.indicators[i],"part_poster_current",i==e)}},goToPage:function(e){if(e!=this.index){window.clearTimeout(this.timer)
var i={container:this.container,duration:this.options.duration}
i.hide={element:this.pages[this.index],effect:"slideLeft"},hui.cls.remove(this.indicators[this.index],"part_poster_current"),this.index=e,i.show={element:this.pages[this.index],effect:"slideRight"},hui.cls.add(this.indicators[this.index],"part_poster_current"),hui.transition(i),this.options.editmode||this._callNext(),this.fire("pageChanged",e)}},_callNext:function(){this.timer=window.setTimeout(this.next.bind(this),this.options.interval)},_onClick:function(e){e=hui.event(e)
var i=e.findByTag("a")
i&&hui.cls.has(i.parentNode,"part_poster_navigator")&&this.goToPage(parseInt(i.getAttribute("data")))}},window.define&&define("op.part.Poster"),op.part.Map=function(e){this.options=hui.override({maptype:"roadmap",zoom:8},e),this.container=hui.get(e.element),hui.ui.onReady(this.initialize.bind(this))},op.part.Map.defered=[],op.part.Map.onReady=function(e){hui.log("onReady... loaded:"+this.loaded),this.loaded?e():this.defered.push(e),void 0===this.loaded&&(this.loaded=!1,window.opMapReady=function(){hui.log("ready")
for(var e=0;e<this.defered.length;e++)this.defered[e]()
window.opMapReady=null,this.loaded=!0}.bind(this),hui.require("https://maps.googleapis.com/maps/api/js?sensor=false&callback=opMapReady"))},op.part.Map.types={roadmap:"ROADMAP",terrain:"TERRAIN"},op.part.Map.prototype={initialize:function(){hui.log("init"),op.part.Map.onReady(this.ready.bind(this))},ready:function(){{var e={zoom:this.options.zoom,center:new google.maps.LatLng(-34.397,150.644),mapTypeId:google.maps.MapTypeId[this.options.type.toUpperCase()],scrollwheel:!1}
this.options.markers}if(this.options.center&&(e.center=new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude)),this.map=new google.maps.Map(this.container,e),this.options.center){var i=new google.maps.Marker({position:new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude),map:this.map,icon:new google.maps.MarkerImage(op.context+"style/basic/gfx/part_map_pin.png",new google.maps.Size(29,30),new google.maps.Point(0,0),new google.maps.Point(8,26))}),t=hui.get.firstByClass(this.element,"part_map_text")
if(t){var o=new google.maps.InfoWindow({content:hui.build("div",{text:t.innerHTML,"class":"part_map_bubble"})})
o.open(this.map,i)}return
var i}}},window.define&&define("op.part.Map"),op.part.Movie=function(e){this.options=e,this.element=hui.get(e.element),this._attach()},op.part.Movie.prototype={_attach:function(){hui.listen(this.element,"click",this._activate.bind(this))
var e=hui.get.firstByClass(this.element,"part_movie_poster")
if(e){var i=e.getAttribute("data-id")
if(i)window.setTimeout(function(){var t=window.devicePixelRatio||1,o=op.context+"services/images/?id="+i+"&width="+e.clientWidth*t+"&height="+e.clientHeight*t
e.style.backgroundImage="url("+o+")"},500)
else{var t=e.getAttribute("data-vimeo-id")
t&&this._vimeo(t,e)}}},_activate:function(){var e=hui.get.firstByClass(this.element,"part_movie_body"),i=hui.get.firstByTag(this.element,"noscript")
i&&(e.innerHTML=hui.dom.getText(i)),e.style.background=""},_vimeo:function(e,i){var t="callback_"+e,o="http://vimeo.com/api/v2/video/"+e+".json?callback="+t
window[t]=function(e){i.style.backgroundImage="url("+e[0].thumbnail_large+")"}
hui.build("script",{type:"text/javascript",src:o,parent:document.head})}},window.define&&define("op.part.Movie"),hui.transition=function(e){var i=e.hide,t=e.show,o=hui.transition[t.effect],s=hui.transition[i.effect]
hui.style.set(e.container,{height:e.container.clientHeight+"px",position:"relative"}),hui.style.set(i.element,{width:e.container.clientWidth+"px",position:"absolute",boxSizing:"border-box"}),hui.style.set(t.element,{width:e.container.clientWidth+"px",position:"absolute",display:"block",visibility:"hidden",boxSizing:"border-box"}),hui.animate({node:e.container,css:{height:t.element.clientHeight+"px"},duration:e.duration+10,ease:hui.ease.slowFastSlow,onComplete:function(){hui.style.set(e.container,{height:"",position:""})}}),s.beforeHide(i.element),s.hide(i.element,e.duration,function(){hui.style.set(i.element,{display:"none",position:"static",width:""})}),o.beforeShow(t.element),hui.style.set(t.element,{display:"block",visibility:"visible"}),o.show(t.element,e.duration,function(){hui.style.set(t.element,{position:"static",width:""})})},hui.transition.css=function(e){this.options=e},hui.transition.css.prototype={beforeShow:function(e){hui.style.set(e,this.options.hidden)},show:function(e,i,t){hui.animate({node:e,css:this.options.visible,duration:i,ease:hui.ease.slowFastSlow,onComplete:t})},beforeHide:function(e){hui.style.set(e,this.options.visible)},hide:function(e,i,t){hui.animate({node:e,css:this.options.hidden,duration:i,ease:hui.ease.slowFastSlow,onComplete:function(){t(),hui.style.set(e,this.options.visible)}.bind(this)})}},hui.transition.dissolve=new hui.transition.css({visible:{opacity:1},hidden:{opacity:0}}),hui.transition.scale=new hui.transition.css({visible:{opacity:1,transform:"scale(1)"},hidden:{opacity:0,transform:"scale(.9)"}}),hui.transition.slideLeft=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"-100%"}}),hui.transition.slideRight=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"100%"}}),op.SearchField=function(e){e=this.options=hui.override({placeholderClass:"placeholder",placeholder:""},e),this.field=hui.get(e.element),this.field.onfocus=function(){this.field.value==e.placeholder?(this.field.value="",hui.cls.add(this.field,e.placeholderClass)):this.field.select()}.bind(this),this.field.onblur=function(){""==this.field.value&&(hui.cls.add(this.field,e.placeholderClass),this.field.value=e.placeholder)}.bind(this),this.field.onblur()},window.define&&define("op.SearchField"),op.Dissolver=function(e){e=this.options=hui.override({wait:4e3,transition:2e3,delay:0},e),this.pos=Math.floor(Math.random()*(e.elements.length-1e-5)),this.z=1,e.elements[this.pos].style.display="block",window.setTimeout(this.next.bind(this),e.wait+e.delay)},op.Dissolver.prototype={next:function(){this.pos++,this.z++
var e=this.options.elements
this.pos==e.length&&(this.pos=0)
var i=e[this.pos]
hui.style.setOpacity(i,0),hui.style.set(i,{display:"block",zIndex:this.z}),hui.animate(i,"opacity",1,this.options.transition,{ease:hui.ease.slowFastSlow,onComplete:function(){window.setTimeout(this.next.bind(this),this.options.wait)}.bind(this)})}},window.define&&define("op.Dissolver"),Poster=function(){this.poster=hui.get("poster"),this.left=hui.get("poster_left"),this.right=hui.get("poster_right"),this.progress=hui.get("poster_loader"),this.context="style/in2isoft/gfx/",this.leftImages=["poster_in2isoft_image.png","poster_publisher_image.png","poster_in2igui_image.png","poster_onlineobjects_image.png"],this.rightImages=["poster_in2isoft_text.png","poster_publisher_text.png","poster_in2igui_text.png","poster_onlineobjects_text.png"],this.leftImages=["poster_humanise_image.png","poster_editor_image.png","poster_hui_image.png","poster_onlineobjects_image.png"],this.rightImages=["poster_humanise_text.png","poster_editor_text.png","poster_hui_text.png","poster_onlineobjects_text.png"],this.links=["om/","produkter/onlinepublisher/","teknologi/in2iGui/","produkter/onlineobjects/"],this.leftPos=-1,this.rightPos=-1
var e=this
this.poster.onclick=function(){document.location=op.page.path+e.links[e.leftPos]},this.preload()},Poster.prototype.start=function(){this.left.scrollLeft=495
var e=this,i=op.page.path+this.context
new hui.animation.Loop([function(){e.leftPos++,e.leftPos>=e.leftImages.length&&(e.leftPos=0),hui.get("poster_left_inner").style.backgroundImage="url('"+i+e.leftImages[e.leftPos]+"')",e.rightPos++,e.rightPos>=e.rightImages.length&&(e.rightPos=0),hui.get("poster_right_inner").style.backgroundImage="url('"+i+e.rightImages[e.rightPos]+"')"},{duration:1e3},{element:this.left,property:"scrollLeft",value:"0",duration:1e3,ease:hui.ease.fastSlow,wait:500},{element:this.right,property:"scrollLeft",value:"495",duration:1e3,ease:hui.ease.fastSlow},{duration:4e3},{element:this.left,property:"scrollLeft",value:"495",duration:1e3,ease:hui.ease.quintIn,wait:500},{element:this.right,property:"scrollLeft",value:"0",duration:1e3,ease:hui.ease.quintIn}]).start()},Poster.prototype.preload=function(){var e=new hui.Preloader({context:op.page.path+this.context})
e.setDelegate({allImagesDidLoad:function(){this.progress.style.display="none",this.start()}.bind(this),imageDidLoad:function(e,i){this.progress.innerHTML=Math.round(e/i*100)+"%"}.bind(this)}),e.addImages(this.leftImages),e.addImages(this.rightImages),e.load()},define("Poster",Poster),require(["hui"],function(){var e=function(e){this.element=e.element,hui.collect(this.nodes,this.element),this._attach()}
e.prototype={nodes:{icon:"layout_search_icon",text:"layout_search_text"},_attach:function(){hui.listen(this.nodes.icon,"click",this._toggle.bind(this)),hui.listen(this.nodes.text,"focus",this._focus.bind(this)),hui.listen(this.nodes.text,"blur",this._blur.bind(this))},_toggle:function(){hui.cls.toggle(document.body,"layout_searching"),window.setTimeout(function(){try{this.nodes.text.focus()}catch(e){}}.bind(this),100)},_focus:function(){hui.cls.add(document.body,"layout_searching")},_blur:function(){hui.cls.remove(document.body,"layout_searching")}},new e({element:hui.find(".layout_search")})})
