$(function () {

    $('#soundPlay')[0].volume=.3;


    var playerNow='x.png';
    var playerCount=0;//store number of play to check if player not win
    function testConsole(d='no data'){
        console.log(d);
    }
   /*   resize main*/
     $('main').height($('body').height()-$('header').outerHeight(true)-$('footer').outerHeight(true));
     $('main div.col').height($('main').height()/3);
     $(window).resize(function () {
        $('main').height($('body').height()-$('header').outerHeight(true)-$('footer').outerHeight(true));
         $('main div.col').height($('main').height()/3);
         $('main div.col img').height($('main').height()/3-10);

         if ($('div.position-fixed').css('display')=='block'){
             $('div.position-fixed').css('display','block').animate({
                 top:($('body').height()/2-30)
             },1000);
         }

     });
     $('main').on('click','div',function () {
         $('main div.col img').height($('main').height()/3-10);

     });
    /* start play*/
    col=$('main div.col');
    function  start_play() {
        col.click(function () {
            $('main').css('background','white');
            $('body').css('background','url("img/bg.jpg")');
            if ($(this).is("[data-value='']") && ($('div.position-fixed').css('display'))=='none'){
                playerCount++;
                $(this).attr('data-value',playerNow);
                $(this).append("<img src='img/"+playerNow+"'/>");
                $('#soundClick')[0].currentTime=0;
                $('#soundClick')[0].play();
                playerNow=(playerNow=="x.png"?'o.jpg':'x.png');
                $('header label span').html(playerNow[0]);
                $('span.position-fixed').html(playerNow[0]);
                for (i=0;i<9;i+=3){
                  checkWin(i,i - - 1,i - -2);
                }
                for (i=0;i<3;i++){
                    checkWin(i,i- -3,i - -6);
                }
            }

        });
    }
    /*function checkWin*/
    function checkWin(item1,item2,item3){
        x="[data-value='x.png']";
        o="[data-value='o.jpg']";
         if (col.eq(item1).is(x) && col.eq(item2).is(x)&&col.eq(item3).is(x)){
             endOfGame('X');
         } else if (col.eq(item1).is(o) && col.eq(item2).is(o)&&col.eq(item3).is(o)) {
             endOfGame('O');
         }else if (playerCount==9){
             endOfGame('noWin');
         }

    }
    /*  show endOfGame*/
    function endOfGame(player){
       if (player!='noWin'){
           $('header label').html(player+' '+ 'مبروك فوز الاعب ' );
           $('div.position-fixed code').html(' '+player+' ');
       } else {
           $('header label').html(' انتهت المبارة بالتعادل ');
           $('div.position-fixed p').html(' انتهت المبارة بالتعادل ');
       }
        $('button').eq(1).css('display','inline');
        $('div.position-fixed').css('display','block').animate({
            top:($('body').height()/2-30)
        },1000);
        $('#soundGameFinish')[0].play();
    }
    start_play();
    /*new game*/
    $('button').eq(1).click(function () {
        location.reload();
    });

    /*full screen*/
    $('button').eq(0).click(function () {
        $('main').enterFullscreen();
    });



    /*show player name with mouse*/
    $('main').hover(function () {
        $('span.position-fixed').css('display','block');
    },function () {
        $('span.position-fixed').css('display','none');
    }).mousemove(function (e) {
       $('span.position-fixed').css({
           left:e.clientX - - 15,
           top:e.clientY -15,
       });
    });
});