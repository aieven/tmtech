define( 'Page.Admin.Gallery', [ 'Page' , 'Form', 'Template' ], function( Page , Form, Template ){

    var CATEGORY_TPL =
        '<div id="order-{{gallery_id}}" href="/{{gallery_id}}/" class="category" style=" background-image: url({{icon}})" data-id="{{gallery_id}}" data-name="{{name}}">'+
            '<a href="/admin/gallery/{{gallery_id}}/">' +
                '<span style="font-weight: bold">{{name}}</span>' +
                '<i class="icon-share-alt"></i>' +
            '</a>' +
            '<span class="pull-right">'+
                '<a class="-public-"> Опубликовать <i class="icon-plus"></i></a>'+
                '<a class="-edit-"> Редактировать <i class="icon-edit"></i></a>'+
                '<a class="-delete-"> Удалить <i class="icon-remove"></i></a>' +
            '</span>'+
        '</div>';

    var PUBLIC_TPL = '<a class="-public-"> Опубликовать <i class="icon-plus"></i></a>',
        UNPUBLIC_TPL = '<a class="-unpublic-"> Снять с публикации <i class="icon-minus"></i></a>';

    return OClass( Page, {
        name: 'Admin.Gallery',

        onLoad: function(){
            var $addForm = $('#addForm'),
                $editForm = $('#editForm'),
                $gallery = $('#gallery');

            var deleteGallery = function deleteGallery(){
                    var $item = $(this).closest( 'div.category');
                    $.ajax({
                        type: 'post',
                        url: '/admin/gallery/category/delete/',
                        dataType: 'json',
                        data: { gallery_id: $item.data('id') },
                        success: function( content ){
                            if( content.done )
                                $item.remove();
                        }
                    });
                    return false;
                },

                editGallery = function editGallery(){
                    var $item = $(this).closest('div.category');
                    $addForm.hide();
                    $editForm.show();
                    $editForm.find('input[name=gallery_id]').val( $item.data('id'));
                    $editForm.find('input[name=name]').val( $item.data('name'));
                    return false;
                },

                publicGallery = function publicGallery(){
                    var $item = $(this).closest( 'div.category');
                    $.ajax({
                        type: 'post',
                        url: '/admin/gallery/category/public/',
                        dataType: 'json',
                        data: { gallery_id: $item.data('id'), published: 1 },
                        success: function( content ){
                            if( content.done ){
                                var $unpublic =  $( Template.parse( UNPUBLIC_TPL ));
                                $item.find('.-public-').replaceWith( $unpublic );
                                $unpublic.click( unpublicGallery );
                            }
                        }
                    });
                    return false;
                },

                unpublicGallery = function unpublicGallery(){
                    var $item = $(this).closest( 'div.category');
                    $.ajax({
                        type: 'post',
                        url: '/admin/gallery/category/public/',
                        dataType: 'json',
                        data: { gallery_id: $item.data('id'), published: 0 },
                        success: function( content ){
                            if( content.done ){
                                var $public =  $(Template.parse( PUBLIC_TPL ));
                                $item.find('.-unpublic-').replaceWith( $public );
                                $public.click( publicGallery );
                            }
                        }
                    });
                    return false;
                };

            new Form( $addForm, {
                fields: {
                    name:{
                        validators : [{ name: 'NotEmpty', msg: I18n.get( 'forms.name.notEmpty' ) }]
                    },
                    img:{
                        validators:[
                            { name: 'NotEmpty', msg: I18n.get( 'forms.image.notEmpty' ) },
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.upload.unexpectedType' ) }
                        ]
                    }
                },
                success: function( json ){
                    var $li = $(Template.parse( CATEGORY_TPL, json.category ));
                    $gallery.append( $li );
                    $li.find('.-delete-').click( deleteGallery );
                    $li.find('.-public-').click( publicGallery );
                    $li.find('.-edit-').click( editGallery );
                    $addForm[0].reset();
                }
            });

            new Form( $editForm, {
                fields: {
                    name:{
                        validators : [{ name: 'NotEmpty', msg: I18n.get( 'forms.name.notEmpty' ) }]
                    },
                    img:{
                        validators:[
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.upload.unexpectedType' ) }
                        ]
                    }
                },
                success: function( json ){
                    var $divs = $('div.category'),
                        $edited = $(Template.parse( CATEGORY_TPL, json.category )),
                        $item,
                        i = 0, l = $divs.length;
                    for(; i < l; i++ ){
                        $item = $($divs[i]);
                        if( $item.data('id') == json.category.gallery_id )
                            break;
                    }
                    $item.replaceWith( $edited );
                    $edited.find('.-delete-').click( deleteGallery );
                    $edited.find('.-public-').click( publicGallery );
                    $edited.find('.-edit-').click( editGallery );
                    $editForm[0].reset();
                    $editForm.hide();
                    $addForm.show();
               }
            });

            $gallery.sortable().disableSelection();
            var initOrder = $gallery.sortable( 'serialize'),

                checkCategoriesOrder = function checkCategoriesOrder(){
                    var newOrder = $gallery.sortable( 'serialize' );
                    if( newOrder !== initOrder ){
                        $.ajax({
                            type: 'post',
                            url: '/admin/gallery/categories/reorder/',
                            dataType: 'json',
                            data: newOrder,
                            success: function( content ){
                                if( content.done ){
                                    initOrder = newOrder;
                                    checkCategoriesOrder();
                                }
                                else{
                                    $gallery.sortable('cancel');
                                }
                            }
                        });
                    }
                };

            $gallery.sortable({ update: checkCategoriesOrder });

            $('.-delete-').click( deleteGallery );
            $('.-edit-').click( editGallery );
            $('.-public-').click( publicGallery );
            $('.-unpublic-').click( unpublicGallery );

            $('#editCancel').click( function(){
                $editForm[0].reset();
                $editForm.hide();
                $addForm.show();
                return false;
            });
        }
    });

});