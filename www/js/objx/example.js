var Man = OClass({
    name: OProperty,
    toy: OProperty,

    onSay: OEvent,

    init: function( options ){
    },

    say: function( what ){
        this.onSay( what );
    }
});


$(document).ready(function(){
    var man = Man.fromPojo({
        name: 'Морфеус',
        toy: 'Тёмные очки',
        age: 50
    });
    man.onSay = function( what ){
        console.log( man.name() + ': ' + what );
    };
    man.say( 'Выбирай таблетку, Нео' );
});