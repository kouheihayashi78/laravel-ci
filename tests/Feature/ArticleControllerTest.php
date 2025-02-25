<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストの書き方のパターンはAAA（Arrange-Act-Assert）を使う
     * 日本語で言うと準備・実行・検証
     * 今回も必要なデータを「準備」、ログインして記事登録画面などにアクセうする「実行」、レスポンスを「検証」
     */

    /**
     * @test
     */
    public function testIndex()
    {
        // $thisはTestCaseクラスを継承したArticleControllerTestではgetを使える
        // getでリクエストを送ってTestResponseクラスのレスポンスを受け取る
        $response = $this->get(route('articles.index'));

        // assertStatus(200)の代わりにassertOK()でも良い
        // assertViewIsにはビューファイル名を渡し、表示されているかどうかを確認できる
        $response->assertStatus(200)
            ->assertViewIs('articles.index');

        // テストが終了すると、下記模様に表示され、テストの数(1)とassertでテストした数(2)が緑色で表示される
        // OK (1 test, 2 assertions)
    }

    /**
     * @test
     */
    public function testGuestCreate()
    {
        $response = $this->get(route('articles.create'));
        // assertRedirectは引数として渡したURLにリダイレクトされたかどうかをテストする
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function testAuthCreate()
    {
        // factoryでテストに必要なモデルのインスタンス作成し、createでUserモデルがDBに登録されてインスタンスを返す
        // factorを使うには、あらかじめそのモデルのファクトリ(/factories/UserFactory.php)が存在する必要がある
        $user = factory(User::class)->create();

        // actingAsは引数として渡したUserモデルにてログインした状態を作り出す
        // その上で記事投稿画面に遷移すること
        // また、第二引数にガード名も指定できるので、必要に応じて使う
        $response = $this->actingAs($user)->get(route('articles.create'));
        var_dump($user);

        $response->assertStatus(200)->assertViewIs('articles.create');
    }

    /**
     * @test
     */
    public function testGuestStoreArticle()
    {
        $response = $this->post(route('articles.store', [
            'title' => 'Test Title',
            'body' => 'Test Content'
        ]));
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function testAuthStoreArticle()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $response = $this->post(route('articles.store'), [
            'title' => 'Test Title',
            'body' => 'Test Content'
        ]);

        // データが保存されているか確認
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Title',
            'body' => 'Test Content'
        ]);

        // 投稿後にリダイレクトされることを確認
        $response->assertRedirect(route('articles.index'));
    }

    /**
     * @test
     */
    public function testArticleStoreValidation()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        // 空データを送信
        $response = $this->post(route('articles.store'), []);

        // バリデーションエラーが返ることを確認
        $response->assertSessionHasErrors(['title', 'body']);
    }
}
