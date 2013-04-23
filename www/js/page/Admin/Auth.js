define( 'Page.Admin.Auth', [ 'Page' , 'Form' ], function( Page , Form ){

    return OClass( Page, {
        name: 'Admin.Auth',

        onLoad: function(){
            new Form( $('#authForm'), {
                fields: {
                    email: {
                        validators: [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.email.notEmpty' ) }]
                    },
                    password: {
                        validators: [{ name: 'NotEmpty', msg: I18n.get( 'auth.forms.password.notEmpty' ) }]
                    }
                },
                success: function(){
                    window.location.reload();
                }
            });
        }
    });

});
