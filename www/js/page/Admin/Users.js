define( 'Page.Admin.Users', [ 'Page' , 'Form', 'Template' ], function( Page , Form, Template ){

    var
        TR_USER = [
            '<tr><td>{{admin_id}}</td>',
                '<td>{{email}}</td>',
                '<td>&nbsp;</td></tr>'
        ].join('')
    ;


    return OClass( Page, {
        name: 'Admin.Users',

        onLoad: function(){
            var $users = $('#users'),

                $privilegesEditForm = $('#privilegesEditForm'),
                $privilegesEditUserName = $('#privilegesEditUserName'),
                $privilegesEditModal = $('#privilegesEditModal'),

                $formAddUser = $('#formAddUser'),

                loadPrivilegesForm = function loadPrivilegesForm( user, href ){
                    $privilegesEditForm.attr('action', href );
                    $privilegesEditForm.find('.checkbox input').removeAttr('checked');
                    $privilegesEditUserName.html( user.email );
                    for( var i = 0, l = user.privileges.length; i < l; i++ )
                        $('#privileges' + user.privileges[i]).attr('checked', 'checked');

                    $privilegesEditModal.modal('show');
                },

                initPrivilegesEdit = function initPrivilegesEdit( i, link ){
                        var $link = $(link),
                            href = $link.attr('href'),
                            user;
                        $link.click(function(){
                            if( user ){
                                loadPrivilegesForm( user, href );
                            }
                            else{
                                $.ajax({
                                    url: link.href,
                                    dataType: 'json',
                                    type: 'GET',
                                    success: function( data ){
                                        user = data.user;
                                        loadPrivilegesForm( user, href );
                                    }
                                });
                            }
                            return false;
                        });
                };

            $('.-privileges-').each( initPrivilegesEdit );

            new Form( $privilegesEditForm, {
                success: function(){
                    $privilegesEditModal.modal('hide');
                }
            });

            new Form( $formAddUser, {
                fields: {
                    email: { validators : [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.email.notEmpty' ) }] }
                },
                success: function( data ){
                    $( Template.parse( TR_USER, data )).prependTo( $users );
                    $formAddUser[0].reset();
                }
            });
        }
    });

});
