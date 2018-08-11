hui.on(['hui'],function(hui) {

  var paths = {
    '/' : 'top',
    '/cv/' : 'about',
    '' : 'theater',
    '/fotografier/' : 'photos',
    '/kommunikation/' : 'communication',
    '/film/' : 'movies',


    '/en/' : 'top',
    '/en/cv/' : 'about',
    '' : 'theater',
    '/en/photos/' : 'photos',
    '/en/communication-training/' : 'communication',
    '/en/movie-clips/' : 'movies'
  };

  var nav = hui.find('nav');
  hui.listen(nav,'click',function(e) {
    e = hui.event(e);
    var a = e.findByTag('a');
    if (a) {
      var hash = paths[a.getAttribute('data-path')];
      if (!hash) {
        return;
      }
      var links = hui.findAll('a');
      for (var i = 0; i < links.length; i++) {
        if (hash == links[i].getAttribute('name')) {
          e.stop();
          hui.window.scrollTo({
            element : links[i].parentNode,
            duration : 1000,
            top : hash=='theater' ? 40 : 140
          });
          return;
        }
      }
    }
  });

});