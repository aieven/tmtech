define( 'Utils', [ 'Globals' ], function( Globals ){

    var Utils = {
        autoInputSelection: function( input ){
            return function(){
                input.focus();
                input.selectionStart = 0;
                input.selectionEnd = input.value.length;
                return false;
            };
        }
    };
    return Utils;
});
