Array.prototype.next = function(){
    if(typeof this._iterator == 'undefined'){
        this._iterator = 0;
        return this[this._iterator];
    }else if(typeof this[++this._iterator]!='undefined')
        return this[this._iterator];
    return false;
}

Array.prototype.prev = function(){
    if(typeof this._iterator == 'undefined')
        this._iterator = 0;
    if(typeof this[--this._iterator]!='undefined')
        return this[this._iterator];
    return false;
}

Array.prototype.end = function(){
    if(typeof this._iterator == 'undefined')
        this._iterator = 0;
    this._iterator = this.length-1;
    return this[this._iterator];
}

Array.prototype.reset = function(){
    if(typeof this._iterator == 'undefined')
        this._iterator = 0;
    this._iterator = 0;
    return this[this._iterator];
}

function ucfirst(str){
    return str.charAt(0).toUpperCase() + str.substr(1);
}

function lcfirst(str){
    return str.charAt(0).toLowerCase() + str.substr(1);
}

var debug = {
    callerFunction:function(args){
        return args.callee.caller.toString().match(/function ([^(]+)?.*/i).pop();
    }
}