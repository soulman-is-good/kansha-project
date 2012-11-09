window.x3LeftButtonPressed = false;
window.x3TimeoutButtonPass = false;
$(document).mousedown(function(e){
    if(e.which==1){
        window.x3LeftButtonPressed=true;
        I = setTimeout(function(){if(window.x3LeftButtonPressed)window.x3TimeoutButtonPass=true},2000)
    }    
});
$(document).mouseup(function(e){
    window.x3LeftButtonPressed=false;
    clearTimeout(I);
});

$(document).click(function(e){
    clearTimeout(I);
    window.x3LeftButtonPressed=false;
    if(window.x3TimeoutButtonPass){
        window.x3TimeoutButtonPass=false;
        initMenu(e);
        return false;
    }
});

function initMenu(e){
    if(typeof e != 'undefined'){
        //console.log(e);
        //$(e.target).css({'border':'1px solid red'})
    }
}