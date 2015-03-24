 $(document).ready(function(){
      $('.menu li').hover(function () {
         clearTimeout($.data(this,'timer'));
         $('ul',this).stop(true,true).slideDown(400);
      }, function () {
        $.data(this,'timer', setTimeout($.proxy(function() {
          $('ul',this).stop(true,true).slideUp(40);
        }, this), 1000));
      });
 });