define( 'Page', [], function(){

return OClass({
    name: 'Undefined',

    init: function (){
        this.onLoad();
        console.log( this.name + ' page loaded' );
    },

    unload: function(){
        this.onUnload();
    },

    onLoad: function(){},

    onUnload: function(){}
});

});
