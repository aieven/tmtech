define( 'Globals', [], function( undefined ){

    var Globals = window.GLOBALS;
    if( Globals instanceof Array )
        Globals = {};

    delete window.GLOBALS;

    return {
        get: function( name ){
            if( Globals.hasOwnProperty( name ))
                return Globals[name];

            return undefined;
        }
    };
});
