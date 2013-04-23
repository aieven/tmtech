define( 'Page.Admin.Bots', [ 'Form', 'Page', 'Template' ], function( Form , Page, Template ){

    return OClass( Page, {

        name: 'Admin.Bots',

        onLoad: function(){
            var $editBotNav = $('#editBotNav'),
                $editBotTab = $('#editBotTab'),
                $editBotForm = $('#editBotForm'),
                $editBotName = $('#editBotName'),
                $checkbox = $('#editBotTypes').find('input'),

                initBotEdit = function initBotEdit( i, link ){
                    var $link = $(link),
                        $tr = $link.closest( 'tr' ),
                        href = $link.attr('href');
                    $link.click(function(){
                        $editBotNav.show();
                        $editBotTab.show();
                        $editBotNav.find('span').html( $tr.attr('id').replace( /^\D+/ ,'' ));
                        $editBotName.val( $tr.find('.-name-').html());
                        var $types = $tr.find('.-types-').attr('value');
                        $types = $types.toString().split(',');
                        $checkbox.removeAttr('checked');
                        for( var i = 0; i < $types.length; i++ ){
                            $('#editBotTypes' + $types[i]).attr( 'checked', 'checked' );
                        }
                        $editBotForm.attr( 'action', href );
                        return false;
                    });
                };

            $('.-edit-').each( initBotEdit );

            $('#editBotCancel').click( function(){
                $editBotNav.hide();
                $editBotTab.hide();
                return false;
            });

            new Form( $editBotForm, {
                fields :{
                    'bot_name': { validators: [{ name: 'NotEmpty', msg: 'Назови меня!'}]}
                },
                success: function( json ){
                    var $tr = $('#bot_' + json.bot.instagram_id );
                    $tr.find('.-name-').html( json.bot.bot_name );
                    $tr.find('.-types-').attr( 'value', json.bot.types );
                    $editBotNav.hide();
                    $editBotTab.hide();
                }
            });
        }
    });

});