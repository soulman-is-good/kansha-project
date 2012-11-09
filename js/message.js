/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$.fn.message = function(html,options,tim){
    if(html==null) html = 'Ok!';
    var self = this;
    var ops = {
        'left':'10px',
        'top':'10px',
        'opacity':0
    };
    tim = (tim==null)?3000:tim;
    ops = $.extend({},ops,options);
    var msg = '<div class="gray_box"><div class="gray_minibox" onclick="$(\'#x3-modal-overlay\').click();return false;"><div class="gray_minibox_inside"></div></div><div class="gray_box_inside"><span></span><div class="gray_box_inside_text"></div></div></div></div>';
    msg = $(msg).css(ops);
    msg.find('.gray_box_inside_text').append(html);
    this.body = msg;
    $(this).append(this.body);
    this.body.animate({
        'opacity':1
    },function(){
        if(tim>0)
        setTimeout(function(){
            self.body.animate({
                'opacity':0
            },function(){
                self.body.remove()
                })
            },tim)
        })
    return this;
}

$.fn.success = function(html,ops,tim){
    if(html == null) html = '';
    html = '<span class="successful"><!-- --></span>' + html;
    return $(this).message(html,ops,tim);
}

$.fn.modal = function(html,ops){
    if(typeof ops == 'undefined' || ops == null)
        ops = {};
    ops['z-index'] = 6001;
    var ofX = this.offset().left;
    var ofY = this.offset().top;    
    if(ops.left == null) ops.left = 10;
    else if(typeof ops.left == 'string' && ops.left.indexOf('%')!=-1){
        ops.left = parseInt(ops.left);
        ops.left = this.width()*ops.left/100;
    }
    if(ops.top == null) ops.top = 10;
    else if(typeof ops.top == 'string' && ops.top.indexOf('%')>0){
        ops.top = parseInt(ops.top);
        ops.top = this.height()*ops.top/100;
    }
    ops.left = (parseInt(ops.left) + ofX)+'px';
    ops.top = (parseInt(ops.top) + ofY)+'px';
    /*if(typeof html == 'string')
        html = '<a onclick="$(\'#x3-modal-overlay\').click();return false;" title="Закрыть" href="#" class="x-closed"><!-- --></a>'+html;
    else
        $(html).append('<a onclick="$(\'#x3-modal-overlay\').click();return false;" title="Закрыть" href="#" class="x-closed"><!-- --></a>');*/
    var msg = $('body').message(html,ops,-1);
    $('body').prepend($('<div/>').css({
        'position':'absolute',
        'left':'0px',
        'top':'0px',
        'width':$(document).width()+'px',
        'height':$(document).height()+'px',
        'opacity':'0',
        'z-index':'6000'
    }).attr('id','x3-modal-overlay').click(function(){$(this).remove();msg.body.fadeOut(function(){$(this).remove()})}));
    return msg;
}

$.fn.dialog=function(html,ops){
    if(typeof ops == 'undefined' || ops == null)
        ops = {};
    var ofX = this.offset().left;
    var ofY = this.offset().top;
    if(ops.left == null) ops.left = 10;
    if(ops.top == null) ops.top = 10;
    ops.left = (parseInt(ops.left) + ofX)+'px';
    ops.top = (parseInt(ops.top) + ofY)+'px';
    dialog(html,ops);
};

var dialog = function(html,ops){
    var d = $('<div/>').css({'opacity':'0','position':'absolute','left':'-10000px'}).append(html);
    $('body').append(d);
    var ww = d.width();
    d.remove();
    if((typeof ops == "string" && ops == 'center') || ops==null || (typeof ops.position !='undefined' && ops.position == 'center')){
        if(typeof ops.position !='undefined') delete ops['position'];
        ops = $.extend({},ops,{
            'left':(($(window).width()-ww)/2 + $(document).scrollLeft())+'px',
            'top':(($(window).height()-d.height())/2-38 + $(document).scrollTop())+'px'
        });
    }
    if(typeof ops.buttons == 'undefined'){
        ops.buttons = {'Ok':function(){return true;},'Отмена':function(){return false;}}
    }
    var width = 0;
    var div = $('<div/>').css({'text-align':'center'});
    for(var i in ops.buttons){
        var but = $('<button class="button" style="margin:0 5px" type="submit">'+i+'</button>');
        div.append(but);
        but.bind('click',{'callback':ops.buttons[i]},function(e){$(this).parent().parent().parent().remove();return e.data.callback.call(this,e);})
        width+=103;
    }
    var margin = 0;
    if(ww>width){
        margin = (ww - width)/2;
        width=ww;
    }
    delete ops.buttons;
    if(ops.width == null)
        ops.width = width+'px';
    div.css({'margin':'20px '+margin+'px 0'});
    var vVv = $('<div/>').append(html).append(div);
    $('body').message(vVv,ops,-1);
}


String.prototype.sprintf = function(){
 
    if (typeof arguments == "undefined") {
        return null;
    }
    if (arguments.length < 1) {
        return null;
    }
    //if (typeof arguments[0] != "string") { return null; }
    if (typeof RegExp == "undefined") {
        return null;
    }

    var string = this.toString();
    var exp = new RegExp(/(%([%]|(\-)?(\+|\x20)?(0)?(\d+)?(\.(\d)?)?([bcdfosxX])))/g);
    var matches = new Array();
    var strings = new Array();
    var convCount = 0;
    var stringPosStart = 0;
    var stringPosEnd = 0;
    var matchPosEnd = 0;
    var newString = '';
    var match = null;

    while (match = exp.exec(string)) {
        if (match[9]) {
            convCount += 1;
        }

        stringPosStart = matchPosEnd;
        stringPosEnd = exp.lastIndex - match[0].length;
        strings[strings.length] = string.substring(stringPosStart, stringPosEnd);

        matchPosEnd = exp.lastIndex;
        matches[matches.length] = {
            match: match[0],
            left: match[3] ? true : false,
            sign: match[4] || '',
            pad: match[5] || ' ',
            min: match[6] || 0,
            precision: match[8],
            code: match[9] || '%',
            negative: parseInt(arguments[convCount-1]) < 0 ? true : false,
            argument: String(arguments[convCount-1])
        };
    }
    strings[strings.length] = string.substring(matchPosEnd);
    if (matches.length == 0) {
        return string;
    }
    if (arguments.length < convCount) {
        return null;
    }

    var code = null;
    var match = null;
    var i = null;
    for (i=0; i<matches.length; i++) {

        if (matches[i].code == '%') {
            substitution = '%'
        }
        else if (matches[i].code == 'b') {
            matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(2));
            substitution = convert(matches[i], true);
        }
        else if (matches[i].code == 'c') {
            matches[i].argument = String(String.fromCharCode(parseInt(Math.abs(parseInt(matches[i].argument)))));
            substitution = convert(matches[i], true);
        }
        else if (matches[i].code == 'd') {
            matches[i].argument = String(Math.abs(parseInt(matches[i].argument)));
            substitution = convert(matches[i]);
        }
        else if (matches[i].code == 'f') {
            matches[i].argument = String(Math.abs(parseFloat(matches[i].argument)).toFixed(matches[i].precision ? matches[i].precision : 6));
            substitution = convert(matches[i]);
        }
        else if (matches[i].code == 'o') {
            matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(8));
            substitution = convert(matches[i]);
        }
        else if (matches[i].code == 's') {
            matches[i].argument = matches[i].argument.substring(0, matches[i].precision ? matches[i].precision : matches[i].argument.length)
            substitution = convert(matches[i], true);
        }
        else if (matches[i].code == 'x') {
            matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
            substitution = convert(matches[i]);
        }
        else if (matches[i].code == 'X') {
            matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
            substitution = convert(matches[i]).toUpperCase();
        }
        else {
            substitution = matches[i].match;
        }

        newString += strings[i];
        newString += substitution;

    }
    newString += strings[i];

    return newString;

 
    function convert(match, nosign){
        if (nosign) {
            match.sign = '';
        } else {
            match.sign = match.negative ? '-' : match.sign;
        }
        var l = match.min - match.argument.length + 1 - match.sign.length;
        var pad = new Array(l < 0 ? 0 : l).join(match.pad);
        if (!match.left) {
            if (match.pad == "0" || nosign) {
                return match.sign + pad + match.argument;
            } else {
                return pad + match.sign + match.argument;
            }
        } else {
            if (match.pad == "0" || nosign) {
                return match.sign + match.argument + pad.replace(/0/g, ' ');
            } else {
                return match.sign + match.argument + pad;
            }
        }
    }
}

/**
 *
 * Resize row logic
 *
 */
function get_class(obj) {	// Returns the name of the class of an object
	//
	// +   original by: Ates Goral (http://magnetiq.com)
	// +   improved by: David James

	if (obj instanceof Object && !(obj instanceof Array) &&
		!(obj instanceof Function) && obj.constructor) {
		var arr = obj.constructor.toString().match(/function\s*(\w+)/);

		if (arr && arr.length == 2) {
			return arr[1];
		}
	}

	return false;
}
function mouseX(evt) {
    if (evt.pageX) return evt.pageX;
    else if (evt.clientX)
        return evt.clientX + (document.documentElement.scrollLeft ?
            document.documentElement.scrollLeft :
            document.body.scrollLeft);
    else return null;
}
function mouseY(evt) {
    if (evt.pageY) return evt.pageY;
    else if (evt.clientY)
        return evt.clientY + (document.documentElement.scrollTop ?
            document.documentElement.scrollTop :
            document.body.scrollTop);
    else return null;
}

$.fn.resizerow = function(sel){
    var self = $(this);
    var row_left = self.prev();
    var row_right = self.next();
    var col_left = $(sel).children('.'+row_left.attr('class'));
    var col_right = $(sel).children('.'+row_right.attr('class'));
    self.css('cursor','w-resize');
    var trig = false;
    var x = 0;
    //var y = 0;
    self.mousedown(function(e){
        trig = true;
        x = mouseX(e);
        //y = mouseY(e);
        $(document).bind('mousemove',function(e){
            if(!trig) return false;
            var dX = mouseX(e)-x;
            var ldW = row_left.width()+dX;
            var rdW = row_right.width()-dX;
            row_left.width(ldW);
            row_right.width(rdW);
            col_left.width(ldW);
            col_right.width(rdW);
            x = mouseX(e);
        });
    });
    $(document).bind('mouseup',function(){
        trig = false;
        $(document).unbind('mousemove');
    //$(document).unbind('mouseup');
    });
}

$.fn.integer = function(){

    $(this).keydown(function(e){
        if ( e.keyCode == 46 || e.keyCode == 8 ) {
        // let it happen, don't do anything
        }
        else {
            // Ensure that it is a number and stop the keypress
            if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105 )) {
                e.preventDefault();
            }
        }

    })
}

jQuery.fn.sortElements = (function(){
 
    var sort = [].sort;
 
    return function(comparator, getSortable) {
 
        getSortable = getSortable || function(){return this;};
 
        var placements = this.map(function(){
 
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
 
                // Since the element itself will change position, we have
                // to have some way of storing its original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );
 
            return function() {
 
                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }
 
                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);
 
            };
 
        });        
        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });
 
    };
 
})();

function numeral(number, cases) {
    var tcase = [2, 0, 1, 1, 1, 2];
    var string = cases[ (number%100>4 && number%100<20)? 2 : tcase[Math.min(number%10, 5)] ].sprintf(number);
    return string;
}

/*Object.prototype.argumentCount = function(){
    var result = 0;
    for(var i in this){
        if(typeof this[i] != 'undefined' && this[i]!=null) result++;
    }
    return result;
}*/

Array.prototype.count = function(){
    var result = 0;
    for(var i in this){
        if(typeof this[i] != 'undefined' && typeof this[i] != 'function' && this[i]!=null) result++;
    }
    return result;
}

$.fn.dropbox = function(ops){
    var self = this;
    var wid = $(this).width();
    var cur = $(this).val();
    var txt = $(this).children(':selected').text();
    this.sel = $('<div />').css({'position':'relative','display':'inline'});
    this.drop = $('<div />').addClass('dropbox').css({'height':'100px','width':(wid+20)+'px','display':'none'});
    this.current = false;
    var count = $(this).children('option').length;
    self.drop.data('name',$(self).attr('name'));    
    self.drop.data('value',$(self).val());
    $(this).children('option').each(function(){
        var v = $(this).attr('value');
        var t = $(this).text();
        var a = $('<a />').attr('href','#'+v).html(t).click(function(){
            self.drop.fadeOut('fast');
            var val = $(this).data('value');
            self.drop.data('value',val);
            self.I.html($(this).text()).append($('<i />').append('<!-- -->'));
            $(self).val(val).change();
        });
        a.data('value',v);
        self.drop.append(a)
    })    
    self.drop.height(26*count);
    self.drop.width(self.width);
    var Acss = {'width':wid+'px'};
    if(ops!=null && ops.css!=null)
        Acss = $.extend({},Acss,ops.css)
    this.I = $('<a />').css(Acss).addClass('selector').append(txt).append($('<i />').append('<!-- -->'));
    this.sel.append(this.I).append(this.drop);
    $(this).css({'display':'none'});
    this.I.click(function(){
        if(!$(this).hasClass('active')){
            window.currentDropBox = self;
            self.current = true;
            $(document).one('mouseup',function(e){
                if(window.currentDropBox!=null && typeof e.target.current=="undefined" || (typeof e.target.current!="undefined" && !e.current))
                    window.currentDropBox.I.click();
            })
        }else
            self.current = false;
        $(this).toggleClass('active');
        self.drop.slideToggle();
    })
    this.sel.insertAfter(this);
    return this.sel;
}

//location hash
//$(window).bind('hashchange', function() {
    //location.href;
//});

