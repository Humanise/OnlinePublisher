hui.onReady(function() {
  var blocks = hui.get.byTag(document.body,'blockquote');
  if (!blocks.length) {
    return;
  }
  var timer;
  var index = -1;
  timer = function() {
    if (index>-1) {
      blocks[index].style.display='';
    }
    index++;
    if (index>blocks.length-1) {
      index = 0;
    }
    blocks[index].style.display='block';
    window.setTimeout(timer,6000);
  }
  timer();
})