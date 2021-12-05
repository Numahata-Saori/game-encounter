<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    //AppServiceProvider.phpより移動
    public function getMyMemo() {
        //上部でインポートしていない場合、直接\で呼ぶ
        $query_tag = \Request::query('tag');
        // dd($query_tag);

        // ==== ベースのメソッド ====
        $query = Memo::query()->select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC'); //ASC=小さい順、DESC=大きい順
        // ==== ベースのメソッドここまで ====

        // もしクエリパラメータtagがあれば
        if( !empty($query_tag) ) {
            $query->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->where('memo_tags.tag_id', '=', $query_tag);
        }

        $memos = $query->get();

        return $memos;

    }
}
