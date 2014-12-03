<?

/*

CREATE TABLE "public"."news_item" (
  "id" SERIAL,
  "insert_time" TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  "message" varchar,
  "category" varchar,
  "mod__person_id" INTEGER,
  "active" SMALLINT DEFAULT 1 NOT NULL,
  CONSTRAINT "news_item_pkey" PRIMARY KEY("id")
) WITHOUT OIDS;

COMMENT ON TABLE "public"."news_item"
IS '{"codebase":"cms"}';

CREATE TABLE "public"."news_who" (
  "id" SERIAL,
  "news_item_id" integer,
  "who" varchar,
  "insert_time" TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL,
  "mod__person_id" INTEGER,
  "active" SMALLINT DEFAULT 1 NOT NULL,
  CONSTRAINT "news_who_pkey" PRIMARY KEY("id")
) WITHOUT OIDS;

COMMENT ON TABLE "public"."news_who"
IS '{"codebase":"cms"}';

CREATE TABLE "public"."news_category" (
  "id" SERIAL,
  "slug" VARCHAR,
  "name" VARCHAR,
  "mod__person_id" INTEGER,
  "active" SMALLINT DEFAULT 1 NOT NULL,
  CONSTRAINT "news_category_pkey" PRIMARY KEY("id")
) WITHOUT OIDS;

COMMENT ON TABLE "public"."news_category"
IS '{"codebase":"cms"}';

CREATE TABLE "public"."news_hide" (
  "id" SERIAL,
  "person_id" integer,
  "news_item_id" integer,
  "active" SMALLINT DEFAULT 1 NOT NULL,
  CONSTRAINT "news_hide_pkey" PRIMARY KEY("id")
) WITHOUT OIDS;

COMMENT ON TABLE "public"."news_hide"
IS '{"codebase":"cms"}';

 */

class news {


    /*  get an array of news_items for the specified "who" values
     *  parameters:
     *  who - string or array
     *  limit - number of items to return
     *  offset -
     */
    public function getFeed( $who, $limit=50, $offset=0 ) {

        if (!is_array($who)) $who = array($who);
        $who_array = $who;
        foreach ( $who_array as $who ) {
            if ( $who_csv ) $who_csv .= ',';
            $who_csv .= "'$who'";
        }

        // return a distinct field in a table with an order by
        $where = "who in ( $who_csv )";
        $order_by = 'news_who.insert_time desc';
        $person_id = PERSON_ID ? PERSON_ID : 0;
        $sql = "
            SELECT news_item_id FROM (
                SELECT DISTINCT ON (q.news_item_id) news_item_id, row FROM (
                    SELECT
                        news_who.news_item_id,
                        row_number() OVER (ORDER BY {$order_by}) AS row
                    FROM news_who
                    LEFT JOIN news_hide on news_hide.news_item_id = news_who.news_item_id
                        and news_hide.person_id = $person_id
                        and news_hide.active = 1
                    LEFT JOIN news_item on news_item.id = news_who.news_item_id
                    WHERE news_who.active = 1
                    AND news_item.active = 1
                    AND news_hide.id is null
                    AND {$where}
                    ORDER BY {$order_by}
                    OFFSET {$offset}
                    LIMIT {$limit}
                ) AS q
            ) AS fin ORDER BY row";
        //print_pre($sql);
        elapsed('before news query');
        $arr = sql_array($sql);
        elapsed('after news query');
        return $arr;
    }


    /*  add a news item to the feed
     *  param array:
     *  category
     *  subject
     *  message
     *  who - string or array
     *  time - default now()
     *  mod__person_id
     */
    public function add($param) {

        $n = new \Sky\Model\news_item();
        $n->category = $param['category'];
        $n->json = $param['json'];
        $n->mod__person_id = $param['mod__person_id'];
        //$n->insert_time = 'now()';
        if ( $param['time'] ) $n->insert_time = $param['time'];
        $who = $param['who'];
        if ( !is_array($who) ) $who = array($who);
        $who_array = $who;
        foreach ( $who_array as $i => $who ) {
            $n->news_who[$i]['who'] = $who;
            //$n->news_who[$i]['insert_time'] = 'now()';
        }
        //d($n);
        $n->save();

    }


    /*  format timestamp as xxx ago
     */
    public function ago($insert_time) {
        $then = strtotime($insert_time);
        $now = self::getDbTime();
        $seconds_ago = $now - $then;
        //return 'insert_time: ' . $insert_time . ' , now: ' . $now;
        if ( $seconds_ago < 0 ) return 'a while ago';
        $intervals = array(
            'year' => 60 * 60 * 24 * 365,
            'month' => 60 * 60 * 24 * 30,
            'day' => 60 * 60 * 24,
            'hour' => 60 * 60,
            'minute' => 60
        );
        foreach ($intervals as $interval => $num_seconds) {
            $qty = floor($seconds_ago / $num_seconds);
            if ( $qty > 0 ) {
                if ( $qty == 1 ) return "1 $interval ago";
                else return $qty . " {$interval}s ago";
            }
        }
        return 'a few seconds ago';
    }


    public function getDbTime() {
        // get the current time of the master database, but only get it once per page load
        global $dbw;
        if ( $GLOBALS['news_db_time'] ) return $GLOBALS['news_db_time'];
        $r = sql("select current_timestamp(0) as now",$dbw);
        $now = strtotime($r->now);
        $GLOBALS['news_db_time'] = $now;
        return $now;
    }

}
