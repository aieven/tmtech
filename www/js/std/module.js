var define = (function(){

    var Scope = {},

        Module = {
            getModule: function( module, create ){
                var m = module.split('.').reverse(),
                    scope = Scope,
                    sub;

                while( sub = m.pop()){
                    if( sub === '_Module' )
                        throw new Error( 'Module name "_Module" is reserved.' );
                    if(!scope[sub] ){
                        if( create ){
                            scope[sub] = {};
                        }
                        else
                            throw new Error( 'Module "' + module + '" is not defined in scope.' );
                    }
                    if( create && !m.length ){
                        scope[sub]._Module = create;
                    }
                    scope = scope[sub];
                }
                return scope._Module;
            }
        };

    return function define( module, require, content ){
        var r = [], i;

        for( i = 0; i < require.length; i++ ){
            r.push( Module.getModule( require[i] ));
        }

        Module.getModule( module, content.apply( Module, r ));
    };
}());