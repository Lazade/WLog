$(function(){
    if (window.location.pathname !== '/' && window.location.pathname !== '/isopixel/') {
      $('.info').remove()
    }
  })
  
  $().ready(function(){
  
    $('#menu_cont .cover .slide-up').click(function(){
      $('#menu_cont').hide(100).css('top', '-100%')
    })
  
    $('footer .totop').click(function(){
      $("html,body").animate({scrollTop:0},300)
    })
  
    $(this).scroll(function(){
      if($(window).scrollTop() > 100){
        $('footer .totop').show()
      }else{
        $('footer .totop').hide()
      }
    })
  
    $(this).bind('click',function(e){
      var target = $(e.target)
      if(target.closest('#menubtn').length == 1){
        $('#menu_cont').show(100).css('top', '0%')
      }else if($('#menu_cont').css('display') == 'block'){
        if(target.closest('#menu_cont').length == 0){
          $('#menu_cont').css('top', '-100%').hide(100)
        }
      }
    });
  })
  