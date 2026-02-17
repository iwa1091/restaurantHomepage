<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存データをクリアしてから投入
        Review::truncate();

        $reviews = [
            [
                'name'    => 'K.A 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => '人気YouTuberの動画で紹介されていたのがきっかけで訪問。片貝で河豚がいただけることに驚きましたが、実際に食べてみると本当に美味しくて感動しました。河豚だけでなく、どの料理も全般的にレベルが高く、接客も素晴らしかったです。大満足の時間を過ごせました。',
                'service' => 'ふぐ料理ほか',
                'date'    => '2025年01月',
            ],
            [
                'name'    => 'M.P 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => '予約なしでふらっと立ち寄りましたが、河豚料理をいただくことができました。大将も奥様もとても感じが良く、温かい雰囲気のお店です。どの料理を食べても絶品で、ぜひまた伺いたいと思います。',
                'service' => 'ふぐ料理',
                'date'    => '2025年01月',
            ],
            [
                'name'    => 'S.F 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => '連休中にたまたま入ったお店でしたが、これが大当たりでした。お寿司と海鮮丼を注文しましたが、味の良さに驚きました。お値段も手頃で、大将をはじめお店の方々がとても家庭的で温かい雰囲気です。知る人ぞ知る名店という印象で、また静かに通い続けたいお店です。',
                'service' => '寿司・海鮮丼',
                'date'    => '2024年08月',
            ],
            [
                'name'    => 'C.M 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => 'とにかく美味しいの一言です。一品一品丁寧に作られたお料理は、見た目も美しく目でも楽しませてくれます。ほっとするような温かいお店の雰囲気もとても気に入っています。',
                'service' => 'コース料理',
                'date'    => '2023年',
            ],
            [
                'name'    => 'H.I 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => 'ネタがとにかく新鮮で、しかも大きい。最高に美味しいお寿司をいただきました。料理もサービスも文句なしの満点です。',
                'service' => '握り寿司',
                'date'    => '2024年',
            ],
            [
                'name'    => 'S.R 様',
                'age'     => null,
                'rating'  => 5,
                'comment' => '気さくな大将との会話が楽しく、ほっこりとした気持ちになれるお店です。お造りも握りもとても美味しくいただきました。料理・サービスともに大満足です。',
                'service' => 'お造り・握り寿司',
                'date'    => '2024年',
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
