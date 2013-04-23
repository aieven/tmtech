var I18n = {
    set: function( loclist ){
        this.loclist = loclist;
    },

    pick: function(){
        if(!arguments.length )
            throw new Error( 'I18n.get have to pass at least 1 argument' );

        var pick = Array.prototype.slice.call( arguments ).reverse(),
            value = this.loclist,
            item;

        while( item = pick.pop()){
            value = value[item];
        }
        return value;
    },

    get: function(){
        if(!arguments.length )
            throw new Error( 'I18n.get have to pass at least 1 argument' );
        var args = Array.prototype.slice.call( arguments ).reverse(),
            string = args.pop(),
            i = 0,
            arg, compare, value;

        try {
            value = this.pick.apply( this, string.split('.'));
        }
        catch(e){
            value = null;
        }
        if( typeof value !== 'string' )
            throw new Error( 'Locale variable ' + string + ' does not exist' );

        while( arg = args.pop()){
            i++;
            compare = value.replace( new RegExp( '%' + i + '\\\\\\$(\\d+(\\.\\d+)?)?[dufs]', 'g' ), arg );
            if( compare == value ){
                compare = value.replace( /%(\d+(\.\d+)?)?[dufs]/, arg );
            }
            if( compare == value )
                break;
            value = compare;
        }

        return value;
    }
};