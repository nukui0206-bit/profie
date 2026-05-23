<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // 既存の公式質問（owner_user_id IS NULL）と、それに紐づく回答を順に削除。
        // 回答 → 質問の順に明示的に消して、FK 制約に依存しないようにする。
        $officialIds = Question::whereNull('owner_user_id')->pluck('id');
        if ($officialIds->isNotEmpty()) {
            \DB::table('answers')->whereIn('question_id', $officialIds)->delete();
            Question::whereIn('id', $officialIds)->delete();
        }

        // 前略プロフィール風 64 問
        $questions = [
            '名前の由来',
            '性別',
            '誕生日',
            '星座',
            '血液型',
            '前世',
            '住んでいるところ',
            '生まれたところ',
            '職業',
            '学年',
            '絡むーちょ',
            '似ている芸能人',
            '身長',
            '体重',
            '足のサイズ',
            '手の長さ',
            '趣味',
            '特技',
            '握力',
            '髪型',
            '口癖',
            '性格',
            '嗜好品',
            '自慢なこと',
            '持っている資格',
            '使っている携帯電話',
            '好きな男性のタイプ',
            '好きな女性のタイプ',
            '好きな言葉',
            '好きな芸能人',
            '好きな食べ物',
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
            'ここだけの話',
        ];

        foreach ($questions as $i => $body) {
            Question::create([
                'body' => $body,
                'owner_user_id' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
