define( 'Page.Admin.Chart', [ 'Page' , 'Form' ], function( Page , Form ){

    return OClass( Page, {
        name: 'Admin.Chart',

        onLoad: function(){
            new Form( $('#switchMethod'), {
                fields: {
                    use_method: {
                    }
                },
                success: function( json ){
                    if(json.result == 'ok'){
                        $('#currentMethodName').html( json.use_method_name );
                    }else{
                        alert('Ошибка');
                    }
                }
            });
        }
    });

});
