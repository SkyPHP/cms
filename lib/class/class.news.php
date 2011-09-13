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
        $table = 'news_who';
        $field = 'news_item_id';
        $where = "who in ( $who_csv )";
        $order_by = 'insert_time desc';
        $sql = "
            SELECT {$field} FROM (
                SELECT DISTINCT ON (q.{$field}) {$field}, row FROM (
                    SELECT
                        {$field},
                        row_number() OVER (ORDER BY {$order_by}) AS row
                    FROM {$table}
                    WHERE {$table}.active = 1
                    AND {$where}
                    ORDER BY {$order_by}
                    OFFSET {$offset}
                    LIMIT {$limit}
                ) AS q
            ) AS fin ORDER BY row";

        if ( PERSON_ID ) $sql = "
            ($sql)
            EXCEPT
            (
                SELECT news_item_id
                FROM news_hide
                WHERE active = 1
                AND person_id = " . PERSON_ID . "
            )";

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

        $n = new news_item();
        $n->subject = $param['subject'];
        $n->message = $param['message'];
        $n->mod__person_id = $param['mod__person_id'];
        if ( $param['time'] ) $n->insert_time = $param['time'];
        $n->category = $param['category'];
        $who = $param['who'];
        if ( !is_array($who) ) $who = array($who);
        $who_array = $who;
        foreach ( $who_array as $i => $who ) {
            $n->news_who[$i]['who'] = $who;
        }
        $n->save();

    }


    /*  format timestamp as xxx ago
     */
    public function ago($insert_time) {
        $then = strtotime($insert_time);
        $now = time();
        $seconds_ago = $now - $then;
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


}