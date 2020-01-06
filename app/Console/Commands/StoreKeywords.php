<?php

namespace App\Console\Commands;

use App\Libraries\Lib_redis_key;
use App\Model\HotSearch;
use Illuminate\Console\Command;
use DB;
class StoreKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:keywords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //存储每日搜索关键词


        $hot_words = \Redis::get(Lib_redis_key::HOT_WORDS);

        $hot_words_array = json_decode($hot_words,true);
        if($hot_words_array){

            $keys = array_keys($hot_words_array);

            $hot_search = new HotSearch();
            $result = $hot_search->getByWords($keys);
            if($result){
                $data = [];
                foreach($result as $k=>$v){
                    $tmp['id'] = $v->id;
                    $tmp['search_times'] = $v->search_times + $hot_words_array[$v->search_word];
                    $data[] = $tmp;
                    unset($hot_words_array[$v->search_word]);
                }

                self::updateBatch($data,'hot_search');
            }

            if($hot_words_array){
                $insert_data =[];
                foreach($hot_words_array as $k=>$v){
                    $insert_data[] = ['search_word'=>$k,'search_times'=>$v];
                }
                $hot_search->insert($insert_data);
            }

            \Redis::del(Lib_redis_key::HOT_WORDS);
        }
    }


    public function updateBatch($multipleData = [],$tableName)
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception("数据不能为空");
            }
            $firstRow  = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新，如果没有ID则以第一个字段为条件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql语句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets      = [];
            $bindings  = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings  = array_merge($bindings, $whereIn);
            $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 传入预处理sql语句和对应绑定数据
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            return false;
        }
    }



}
