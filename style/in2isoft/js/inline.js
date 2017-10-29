hui.onReady(function() {
  if (hui.browser.windows) {
    hui.cls.add(document.body,'windows');
  }
  if (hui.browser.msie) {
    hui.cls.add(document.body,'msie');
  }
  if (hui.browser.webkit) {
    hui.cls.add(document.body,'webkit');
  }
  var search = hui.get('search');
  if (search) {
    hui.onReady(['hui.ui.SearchField'],function() {
      new hui.ui.SearchField({element:search,expandedWidth:200});
    })
  }
})
if (window.devicePixelRatio > 1) {
  document.body.className+=' retina';
}
