define( 'Router', [ 'Globals' ], function( Globals ){

var
    Module = this,

    page = null;

    $(document).ready(function(){
        var getController = Globals.get( 'controller');
        if(getController){
            var Page = Module.getModule( 'Page.' + getController);
            page = new Page();
        }
    });

    return {};
});
