<?php
    namespace Cerceau\Script;

    class FixBugScript extends \Cerceau\Script\Cron\ParseBase {

        public function run(){
            $bugRows = $this->db( 'main' )->selectTable(<<<SQL
        -- SQL_SELECT_BUG_ROWS
            SELECT instagram_id, data FROM {{ t("all_media") }}
SQL
            );
            foreach( $bugRows as $row ){
                $data = unserialize( $row['data'] );

                if( isset( $data['attribution'] ))
                    continue;

                $response = $this->api( 'v1/media/'. $row['instagram_id'] );
                if( $response === 400 ){
                    \Cerceau\Utilities\Debug::instance()->dump( $this->db( 'main' )->query(<<<SQL
    -- SQL_SELECT_BUG_ROWS
    UPDATE {{ t("all_media") }}
      SET deleted = TRUE
      WHERE instagram_id = {{ s(instagram_id) }};
    UPDATE {{ t("media") }}
      SET deleted = TRUE
      WHERE instagram_id = {{ s(instagram_id) }};
SQL
                        , array(
                            'instagram_id' => $row['instagram_id'],
                        )
                    ));
                }
                elseif( $response['data'] ){
                    \Cerceau\Utilities\Debug::instance()->dump( $this->db( 'main' )->query(<<<SQL
    -- SQL_SELECT_BUG_ROWS
    UPDATE {{ t("all_media") }}
      SET data = {{ s(data) }}
      WHERE instagram_id = {{ s(instagram_id) }};
    UPDATE {{ t("media") }}
      SET data = {{ s(data) }}
      WHERE instagram_id = {{ s(instagram_id) }};
SQL
                        , array(
                            'data' => serialize( $response['data'] ),
                            'instagram_id' => $row['instagram_id'],
                        )
                    ));
                }
            }
        }
    }
