define( 'Page.Admin.SmmBot', [ 'Form', 'Page', 'Template' ], function( Form , Page, Template ){

    var TR_TPL = [
        '<tr id="query_{{query_id}}">',
            '<td>{{query_id}}</td>',
            '<td class="-tags-">{{tags}}</td>',
            '<td class="-untags-">{{untags}}</td>',
            '<td>0</td>',
            '<td><div class="pull-right"> ',
                '<a href="/{{edit_url}}" class="-edit-">изменить <i class="icon-edit"></i></a> ',
                '<a href="/{{delete_url}}" class="-delete-">удалить <i class="icon-remove"></i></a> ',
            '</div></td></tr>'
    ].join('');

    return OClass( Page, {

        name: 'Admin.SmmBot',

        onLoad: function(){
            var $queries = $('#queries'),
                $editBotNav = $('#editBotNav'),
                $editQueryNav = $('#editQueryNav'),
                $editQueryForm = $('#editQueryForm'),
                $editQueryTags = $('#editQueryTags'),
                $editQueryUntags = $('#editQueryUntags'),

                initQueryEdit = function initQueryEdit( i, link ){
                    var $link = $(link),
                        $tr = $link.closest( 'tr' ),
                        href = $link.attr('href');
                    $link.click(function(){
                        $editQueryNav.show();
                        $editQueryNav.find('a').tab('show');
                        $editQueryNav.find('span').html( $tr.attr('id').replace( /^\D+/ ,'' ));
                        $editQueryTags.val( $tr.find('.-tags-').html());
                        $editQueryUntags.val( $tr.find('.-untags-').html());
                        $editQueryForm.attr('action', href );
                        return false;
                    });
                },

                initQueryDelete = function initQueryDelete( i, link ){
                    var $link = $(link),
                        $tr = $link.closest( 'tr' ),
                        href = $link.attr('href');
                    $link.click(function(){
                        if( confirm('Точно удалить запрос из базы?'))
                            $.ajax({
                                type: 'delete',
                                url: href,
                                dataType: 'json',
                                success: function( json ){
                                    if( json.done )
                                        $tr.remove();
                                }
                            });
                        return false;
                    });
                };

            $('.-edit-').each( initQueryEdit );
            $('.-delete-').each( initQueryDelete );

            $('#editQueryCancel').click( function(){
                $editBotNav.find('a').tab('show');
                $editQueryNav.hide();
                return false;
            });

            new Form( $('#editBotForm'), {
                success: function( json ){
                }
            });
            new Form( $('#addQueryForm'), {
                fields: {
                    'tags': { validators : [{ name: 'NotEmpty', msg: 'Нужно больше тегов!' }] }
                },
                success: function( json ){
                    var $tr = $(Template.parse( TR_TPL, json.query )).prependTo( $queries );
                    $tr.find('.-edit-').each( initQueryEdit );
                    $tr.find('.-delete-').each( initQueryDelete );
                }
            });
            new Form( $editQueryForm, {
                fields: {
                    'tags': { validators : [{ name: 'NotEmpty', msg: 'Нужно больше тегов!' }] }
                },
                success: function( json ){
                    var $tr = $('#query_' + json.query.query_id );
                    $tr.find('.-tags-').html( json.query.tags );
                    $tr.find('.-untags-').html( json.query.untags );
                    $editBotNav.find('a').tab('show');
                    $editQueryNav.hide();
                }
            });
        }
    });

});