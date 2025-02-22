<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testIndex()
    {
        // $thisはTestCaseクラスを継承したArticleControllerTestではgetを使える
        // getでリクエストを送ってレスポンスを受け取る
        $response = $this->get(route('articles.index'));

        // assertStatus(200)の代わりにassertOK()でも良い
        // assertViewIsにはビューファイル名を渡し、表示されているかどうかを確認できる
        $response->assertStatus(200)
            ->assertViewIs('articles.index');

        // テストが終了すると、下記模様に表示され、テストの数(1)とassertでテストした数(2)が緑色で表示される
        // OK (1 test, 2 assertions)
    }

}
