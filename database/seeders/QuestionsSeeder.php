<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // 既存の公式質問と、それに紐づく回答を順に削除（再シード）。
        $officialIds = Question::whereNull('owner_user_id')->pluck('id');
        if ($officialIds->isNotEmpty()) {
            DB::table('answers')->whereIn('question_id', $officialIds)->delete();
            Question::whereIn('id', $officialIds)->delete();
        }

        // よくある質問（10 問）— 入力フォームの上段に通常表示。
        $featured = [
            '名前の由来',
            '性別',
            '誕生日',
            '血液型',
            '似ている芸能人',
            '趣味',
            '好きな食べ物',
            '好きな男性のタイプ',
            '好きな女性のタイプ',
            'ここだけの話',
        ];

        // それ以外の質問（54 問）— アコーディオン内に格納。
        $others = [
            '星座',
            '前世',
            '住んでいるところ',
            '生まれたところ',
            '職業',
            '学年',
            '絡むーちょ',
            '身長',
            '体重',
            '足のサイズ',
            '手の長さ',
            '特技',
            '握力',
            '髪型',
            '口癖',
            '性格',
            '嗜好品',
            '自慢なこと',
            '持っている資格',
            '使っている携帯電話',
            '好きな言葉',
            '好きな芸能人',
            '嫌いな食べ物',
            '好きな飲み物',
            '嫌いな飲み物',
            '好きな教科',
            '嫌いな教科',
            '好きなテレビ番組',
            '好きな映画',
            '好きな本',
            '好きなスポーツ',
            '好きな音楽',
            '好きなブランド',
            '愛用の香水',
            '好きな花',
            '好きなゲーム',
            '愛車',
            '将来の夢',
            '好きな動物',
            '休日の過ごし方',
            '尊敬する人',
            '今一番欲しいもの',
            '今一番行きたいところ',
            '今一番やりたいこと',
            'よく使う路線',
            'よく遊ぶところ',
            'カラオケでよく歌う曲',
            '初めて買ったCD',
            'マイブーム',
            '最近ひそかに興味があること',
            '生まれ変わったら',
            '世界平和に必要なのは',
            '兎に角主張したい事',
            '疑問に思っている事',
        ];

        // featured: sort_order 1〜10
        foreach ($featured as $i => $body) {
            Question::create([
                'body' => $body,
                'owner_user_id' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
                'is_featured' => true,
            ]);
        }

        // others: sort_order 11〜64
        foreach ($others as $i => $body) {
            Question::create([
                'body' => $body,
                'owner_user_id' => null,
                'sort_order' => 11 + $i,
                'is_active' => true,
                'is_featured' => false,
            ]);
        }
    }
}
