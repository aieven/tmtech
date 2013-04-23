define( 'Page.Admin.Gallery.Publics', [ 'Page' , 'Form', 'Template' ], function( Page , Form, Template ){

    var TR_PUBLIC = [
        '<tr style="vertical-align: middle" data-id="{{public_id}}" id="public_{{public_id}}">' +
            '<td><img class="img-rounded" src="http://images.instagram.com/profiles/anonymousUser.jpg" alt="" /></td>' +
            '<td>{{username}}</td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td><button class="btn btn-mini btn-danger -delete-" type="button">Удалить</button></td>' +
        '</tr>'
    ].join('');


    return OClass( Page, {
        name: 'Admin.Gallery.Publics',

        onLoad: function(){
            var $publics = $('#publics'),
                $formAddPublic = $('#formAddPublic'),
                $formEditPublic = $('#formEditPublic'),

                publicDelete = function publicDelete(){
                    var $btn = $(this);
                    $.ajax({
                        url:'/admin/gallery/public/delete/',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            public_id : $btn.closest( 'tr' ).data( 'id' )
                        },
                        success: function(){
                            $btn.closest( 'tr' ).remove();
                        }
                    });
                    return false;
                },

                publicRestore = function publicRestore(){
                    var $btn = $(this);
                    $.ajax({
                        url:'/admin/gallery/public/restore/',
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            public_id : $btn.closest( 'tr' ).data( 'id' )
                        },
                        success: function(){
                            $btn.remove();
                        }
                    });
                    return false;
                },

                publicEdit = function publicEdit(){
                    var $tr = $(this).closest('tr');
                    $formAddPublic.hide();
                    $formEditPublic.find('[name=public_id]').val( $tr.data('id'));
                    $formEditPublic.find('[name=full_name]').val( $tr.find('.-full-name-').html());
                    $formEditPublic.show();
                    return false;
                };

            new Form( $formAddPublic, {
                fields: {
                    username: { validators : [{ name: 'NotEmpty', msg: I18n.get( 'forms.name.notEmpty' ) }] }
                },
                success: function( json ){
                    var $tr = $( Template.parse( TR_PUBLIC, json ));
                    $tr.prependTo( $publics );
                    $formAddPublic[0].reset();
                    $tr.find('.-delete-').click( publicDelete );
                }
            });

            new Form( $formEditPublic, {
                fields: {
                    full_name: { validators : [{ name: 'NotEmpty', msg: I18n.get( 'forms.name.notEmpty' ) }] }
                },
                success: function( json ){
                    $publics.find('#public_' + json.public_id + ' .-full-name-').html( json.full_name );
                    $formEditPublic.hide();
                    $formAddPublic.show();
                }
            });

            $publics.find('.-edit-').click( publicEdit );
            $publics.find('.-delete-').click( publicDelete );
            $publics.find('.-restore-').click( publicRestore );
        }
    });

});
