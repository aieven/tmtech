define( 'Page.Admin.Snapshot', [ 'Page' , 'Form', 'Template', 'Globals' ], function( Page , Form, Template, Globals ){

    var BANNER_TPL =
            '<div data-id="{{order}}" class="banner" style="background: url({{domain}}{{img}})">' +
            '<a class="btn btn-info -edit-"><i class="icon-edit"></i></a>' +
            '<a class="btn btn-danger -delete-"><i class="icon-remove"></i></a></div>',

        TILE_TPL =
            '<div data-id="{{order}}" class="tile tile_{{tile_type}}" style="background: url({{domain}}{{img}})"></div>',

        CHART_TPL =
            '<div data-id="{{order}}" class="chart" style="background: url({{profile_picture}})">'+
            '<a class="btn btn-info -switch-"><i class="icon-refresh"></i></a></div>',

        snapshot = {
            snapshot_data: {
                banners: [],
                charts: [],
                tiles: []
            }
        },
        domain
    ;

    return OClass( Page, {
        name: 'Admin.Snapshot',

        onLoad: function(){
            var $publishSnapshot = $('#publishSnapshot'),
                $addTileForm = $('#addTileForm'),
                $addBannerForm = $('#addBannerForm'),
                $switchChartNav = $('#switchChartNav a'),
                $switchChartForm = $('#switchChartForm'),
                $banners = $('#banners'),
                $charts = $('#charts'),
                $tiles = $('#tiles'),
                $chartId = $('#chartId'),
                $instagramId = $('#instagramId'),
                $tileType = $('#tileType'),

                checkTileType = function checkTileType(){
                    $tileType.val( snapshot.snapshot_data.tiles.length % 3 ? 2 : 1 );
                },

                checkChartProfile = function checkChartProfile(){
                    var id = Number( $chartId.val());
                    if( id < snapshot.snapshot_data.charts.length )
                        $instagramId.find('[value=' + snapshot.snapshot_data.charts[id].instagram_id + ']').attr('selected', 'selected');
                }
            ;

            snapshot = Globals.get('snapshot');
            domain = Globals.get('img_domain');

            // initialize page
            var banners = snapshot.snapshot_data.banners,
                charts = snapshot.snapshot_data.charts,
                tiles = snapshot.snapshot_data.tiles,
                i, l;
            // banners
            for( i = 0, l = banners.length; i < l; i++ ){
                banners[i].domain = domain;
                banners[i].order = i;
                $banners.append( $( Template.parse( BANNER_TPL, banners[i] )));
            }
            // charts
            for( i = 0, l = charts.length; i < l; i++ ){
                charts[i].order = i;
                $charts.append( $( Template.parse( CHART_TPL, charts[i] )));
            }
            if( i < 4 ){
                var chart = { order: i, profile_picture: '' };
                for( ; i < 4; i++ ){
                    chart.order = i;
                    $charts.append( $( Template.parse( CHART_TPL, chart )));
                }
            }
            $chartId.find('[value=' + (l < 4 ? l : 0) +']').attr('selected', 'selected');
            if( l == 4 )
                checkChartProfile();
            // tiles
            for( i = 0, l = tiles.length; i < l; i++ ){
                tiles[i].domain = domain;
                tiles[i].order = i;
                $tiles.append( $( Template.parse( TILE_TPL, tiles[i] )));
            }
            checkTileType();

            var saveSnapshot = function saveSnapshot(){
                    var data = {
                            banners: snapshot.snapshot_data.banners,
                            charts: [],
                            tiles: snapshot.snapshot_data.tiles
                        },
                        i = 0, l = snapshot.snapshot_data.charts.length;
                    for(; i < l; i++ ){
                        if( snapshot.snapshot_data.charts[i] )
                            data.charts[data.charts.length] = snapshot.snapshot_data.charts[i].instagram_id;
                    }
                    $.ajax({
                        type: 'post',
                        url: '/admin/snapshot/save',
                        dataType: 'json',
                        data: { snapshot_data: data },
                        success: function( json ){
                            if( json.snapshot )
                                snapshot.snapshot_id = json.snapshot.snapshot_id;
                        }
                    });
                },

                resort = function resort( type ){
                    var types = type + 's',
                        $item, $items = $('#' + types ).find('.'+ type ),
                        id, items = snapshot.snapshot_data[types],
                        itemsNew = [],
                        i = 0, l = $items.length;
                    for(; i < l; i++ ){
                        $item = $($items[i]);
                        id = Number( $item.data('id'));
                        itemsNew[i] = items[id];
                        $item.data('id', i );
                    }
                    snapshot.snapshot_data[types] = itemsNew;
                    saveSnapshot();
                },

                deleteBanner = function deleteBanner(){
                    var $item = $(this).closest('.banner'),
                        id = Number( $item.data('id')),
                        banners = snapshot.snapshot_data.banners,
                        bannersNew = [],
                        i = 0, l = banners.length;
                    for(; i < l; i++ ){
                        if( i != id )
                            bannersNew[bannersNew.length] = banners[i];
                    }
                    snapshot.snapshot_data.banners = bannersNew;
                    $item.remove();
                    saveSnapshot();
                },

                switchChart = function switchChart(){
                    $chartId.val( $(this).closest('.chart').data('id'));
                    $switchChartNav.tab('show');
                    checkChartProfile();
                };

            new Form( $addBannerForm, {
                fields: {
                    banner_img:{
                        validators:[
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.unexpectedType' ) }
                        ]
                    },
                    link:{}
                },
                success: function( json ){
                    json.banner.domain = domain;
                    json.banner.img = json.banner.image_path;
                    json.banner.order = snapshot.snapshot_data.banners.length;
                    var $item = $( Template.parse( BANNER_TPL, json.banner ));
                    $banners.append( $item );
                    $item.find('.-delete-').click( deleteBanner );
                    $addBannerForm[0].reset();
                    snapshot.snapshot_data.banners.push( json.banner );
                    saveSnapshot();
                }
            });

            new Form( $addTileForm, {
                fields: {
                    banner_img:{
                        validators:[
                            { name: 'Match', match: /\.(?:jp(?:e?g|e|2)|png)$/i, msg: I18n.get( 'forms.image.unexpectedType' ) }
                        ]
                    },
                    link:{}
                },
                success: function( json ){
                    json.tile.domain = domain;
                    json.tile.img = json.tile.image_path;
                    json.tile.order = snapshot.snapshot_data.tiles.length;
                    var $item = $( Template.parse( TILE_TPL, json.tile ));
                    $tiles.append( $item );
                    $addTileForm[0].reset();
                    snapshot.snapshot_data.tiles.push( json.tile );
                    saveSnapshot();
                    checkTileType();
                }
            });

            new Form( $switchChartForm, {
                success: function( json ){
                    var chartId = Number( $chartId.val());
                    json.public.order = chartId;
                    var $item = $(Template.parse( CHART_TPL, json.public )),
                        $items = $charts.find('.chart');
                    $($items[chartId]).replaceWith( $item );
                    $item.find('.-switch-').click( switchChart );
                    snapshot.snapshot_data.charts[chartId] = json.public;
                    saveSnapshot();
                }
            });

            $publishSnapshot.click( function(){
                $publishSnapshot.attr('disabled','disabled');
                $.ajax({
                    type: 'post',
                    url: '/admin/snapshot/publish',
                    dataType: 'json',
                    data: {
                        snapshot_id: snapshot.snapshot_id
                    },
                    success: function( json ){
                        if( json.result == 'ok' ){
                            $publishSnapshot.html('Опубликовано');
                        }else{
                            $publishSnapshot.attr('disabled','');
                            alert( 'Произошла ошибка' );
                        }
                    }
                });
            });

            $banners.sortable({ update: function(){ resort('banner'); }});
            $charts.sortable({ update: function(){ resort('chart'); }});
            $tiles.sortable({ update: function(){ resort('tile'); }});

            $banners.find('.-delete-').click( deleteBanner );
            $charts.find('.-switch-').click( switchChart );
            $chartId.select( checkChartProfile );

            $('#deleteLastTile').click( function(){
                snapshot.snapshot_data.tiles.pop();
                $tiles.find('.tile:last').remove();
                saveSnapshot();
                checkTileType();
                return false;
            });
        }
    });

});