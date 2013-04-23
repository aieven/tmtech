define( 'Page.Admin.PeopleAndBrands', [ 'Page' , 'Form', 'Template' ], function( Page , Form, Template ){

    var
        TR_SUBCATEGORY = [
            '<tr><td><img class="img-rounded" src="/{{subcat_icon}}" alt="" /></td>',
                '<td><span style="font:bold;">{{subcat_name}}</span><span class="label" style="margin-left: 10px;">0</span></td>',
                '<td data-subcat-id="{{subcat_id}}" style="text-align: right; padding-right: 60px;">',
                    '<button class="btn btn-mini btn-info -subcatView-" type="button" href="{{subcat_id}}/">Просмотр</button>',
                    '<button class="btn btn-mini btn-warning -subcatEdit-" type="button">Редактировать</button>',
                    '<button class="btn btn-mini btn-danger -subcatDel-" type="button">Удалить</button>',
                '</td>',
            '</tr>'
        ].join('');


    return OClass( Page, {
        name: 'Admin.PeopleAndBrands',

        onLoad: function(){
            var $partition = $('.breadcrumb').attr( 'data-partition' ),
                $subcatView = $('.-subcatView-'),
                $catView = $('.-catView-'),
                $subcatEdit = $('.-subcatEdit-'),
                $subcatEditCancel = $('#subcatEditCancel'),
                $subcatDel = $('.-subcatDel-'),
                $editSubcatName = $('#editSubcatName'),
                $editSubcatId = $('#editSubcatId'),
                $editSubcatCategory = $('#editSubcatCategory'),
                $formEditLegend = $('#formEditSubcat legend'),
                $formAddSubcat = $('#formAddSubcat'),
                $formEditSubcat = $('#formEditSubcat'),

                subcatView = function subcatView(){
                    window.location.href = location.pathname + $(this).attr( 'href' );
                },

                subcatEdit = function subcatEdit(){
                    var $subcatNameValue = $(this).parent().prev().children( 'span:first' ).text();
                    $formAddSubcat.hide();
                    $formEditSubcat.show();
                    $editSubcatName.focus().val( $subcatNameValue );
                    $editSubcatId.val( $(this).parent().attr( 'data-subcat-id' ));
                    $formEditLegend.text( 'Редиктировать ' + $subcatNameValue );
                    $editSubcatCategory.val( $(this).closest( 'tbody').attr( 'id' ));
                },

                subcatEditCancel = function subcatEditCancel(){
                    $formAddSubcat.show();
                    $formEditSubcat.hide();
                },

                subcatDel = function subcatDel(){
                    $.ajax({
                        url:'/admin/' + $partition + '/subcat/delete/',
                        dataType: 'json',
                        type: 'POST',
                        data: { subcat_id : $(this).parent().attr( 'data-subcat-id' ) },
                        success: function( data ){
                            $('[data-subcat-id = ' + data.subcat_id +']').closest( 'tr' ).remove();
                        }
                    });
                    return false;
                };

            $subcatDel.live( 'click', subcatDel );
            $subcatView.live( 'click', subcatView );
            $catView.live( 'click', subcatView );
            $subcatEdit.live( 'click', subcatEdit );
            $subcatEditCancel.live( 'click', subcatEditCancel );

            new Form( $formAddSubcat, {
                fields: {
                    subcat_name: { validators : [
                        { name: 'NotEmpty', msg: I18n.get( 'forms.name.notEmpty' ) }
                    ] },
                    img:{
                        validators:[
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.unexpectedType' ) },
                        ]
                    }
                },
                success: function( data ){
                    $( Template.parse( TR_SUBCATEGORY, data )).prependTo( '#' + data.cat_id );
                    $formAddSubcat[0].reset();

                }
            });
            new Form( $formEditSubcat, {
                fields: {
                    subcat_name: { validators : [
                        { name: 'NotEmpty', msg: I18n.get( 'forms.image.notEmpty' ) }
                    ] },
                    img:{
                        validators:[
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.unexpectedType' ) },
                        ]
                    }
                },
                success: function( data ){
                    subcatEditCancel();
                    $('[data-subcat-id = ' + data.subcat_id +']').closest( 'tr' ).remove();
                    $( Template.parse( TR_SUBCATEGORY, data )).prependTo( '#' + data.cat_id );
                    $formEditSubcat[0].reset();
                    $('.-error-').text(data.error);
                }
            });
        }
    });

});
