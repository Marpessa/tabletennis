ttp = {};
ttp.main = {};

ttp.main.init_partners_list = function() {
   var widthPartnersList = 0;
   $.each( $('#scrolling-partners ul li'), function(){
        widthPartnersList += $(this).width() + 20;
   });

   $('#scrolling-partners .rolling-partners').append($('#scrolling-partners ul').clone());
   $('#scrolling-partners ul').css('width', (widthPartnersList) + 'px' );
   $('#scrolling-partners ul:eq(1)').css('left', widthPartnersList + 'px' );
   ttp.main.rotate_partners( widthPartnersList );
};

ttp.main.rotate_partners = function(widthPartnersList) {
    $('#scrolling-partners .rolling-partners').animate({
        left: '-='+ widthPartnersList
    }, 35000, 'linear', function() {
        //$('#scrolling-partners .rolling-partners').append( $('#scrolling-partners ul:eq(0)') );
        $('#scrolling-partners .rolling-partners').css('left', '0px');
        ttp.main.rotate_partners( widthPartnersList );
    });
};

ttp.main.init_focus_calendar = function() {
  
};

$(document).ready(function() {
    ttp.main.init_partners_list();
    ttp.main.init_focus_calendar();
});