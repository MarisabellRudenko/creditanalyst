<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Support\Facades\DB;

class IndicatorWatcher extends Model
{
    protected $table = 'user_indicator_watch_list';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function indicator()
    {
        return $this->belongsTo('App\Indicator');
    }

    public static function getDataListByUserId($userId): array
    {
        $userId = (int)$userId;
        $sql = 'SELECT 
`uiwl`.`id`,
`uiwl`.`indicator_id`,
`uiwl`.`alias`,
`uiwl`.`notify`,
`i`.`name`,
(SELECT `d`.`value` FROM `data` AS `d` WHERE `d`.`indicator_id` = `i`.`id` ORDER BY `d`.`id` DESC LIMIT 1) AS `value`
FROM `user_indicator_watch_list` AS `uiwl`
JOIN `indicators` AS `i` ON `i`.`id` = `uiwl`.`indicator_id`
WHERE
    `uiwl`.`user_id` = ?
ORDER BY `uiwl`.`position` DESC;';
        $result = DB::select($sql, [$userId]);
        foreach ($result as $key => $value) {
            $result[$key]->notify = (bool)$value->notify;
            $result[$key]->value = (float)$value->value;
        }
        return $result;
    }
}
