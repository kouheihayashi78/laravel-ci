<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use App\User;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        // $faker->text()でランダムな文章を生成
        'title' => $faker->text(50),
        'body' => $faker->text(500),

        // usersテーブルのidカラムに対する外部キー制約持っている
        // ファクトリで外部キー制約のあるカラムを扱うときは、参照先のモデルを生成するfactory関数を返すクロージャ(function(){})をセット
        'user_id' => function() {
            // Userモデルがファクトリで生成され、idがArticleのuser_idにセットされる
            return factory(User::class);
        }
    ];
});
