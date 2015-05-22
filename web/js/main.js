jQuery(document).ready(function() {
    App.init();
    App.initSliders();
    Index.initParallaxSlider();

    for(var i=0; i<sourcesCss.length; i++) {
    	loadCSS(sourcesCss[i]);
    }
});

document.addEventListener('DOMContentLoaded', function(event) {
  cookieChoices.showCookieConsentBar('Les cookies assurent le bon fonctionnement de nos services. En utilisant ces derniers, vous acceptez l\'utilisation des cookies.',
    'OK', 'En savoir plus', "{{ path('_baseCguIndex') }}");
});