<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function testIsLikedByNull()
    {
        // ファクトリでArticleモデルをDBに保存
        $article = factory(Article::class)->create();

        $result = $article->isLikedBy(null);

        // ここの$thisはTestCaseを継承したArticleTestを継承
        // assertFalseで引数がfalseかどうかを判定
        $this->assertFalse($result);

        // ※なぜ今回は$this->assertFalseのような書き方かというと、前回はgetなどを使ってリクエストを送り、
        // レスポンスでTestResponseクラスのレスポンスを受け取り、その中にあるassertStatusを使っていた
        // 今回はArticleクラスのisLikedByメソッドの戻り値が代入されており、assert〇〇のようなメソッドはない！
    }

    public function testIsLikedByTheUser()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        // ここで「いいね」を実行
        // belongsToManyクラスのインスタンスが帰ってくるので、attachメソッドを使用
        $article->likes()->attach($user);

        $result = $article->isLikedBy($user);

        $this->assertTrue($result);
    }

    public function testIsLikedByAnother()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        // 他人がいいねする
        $another = factory(User::class)->create();
        $article->likes()->attach($another);
        $result = $article->isLikedBy($user);

        $this->assertFalse($result);
    }
}
