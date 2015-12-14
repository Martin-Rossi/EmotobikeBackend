<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CatalogTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/catalogs' )
             ->see( $catalog->name );
    }

    public function testShowCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/catalogs/' . $catalog->id )
             ->see( $catalog->name );
    }

    public function testAddCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->make()->toArray();

        $catalog['category'] = 'test';
        $catalog['type'] = 'test';

        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs', $catalog );

        $this->seeInDatabase( 'catalogs', ['name' => $catalog['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $data = factory( App\Catalog::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $user = factory( App\User::class )->create();

        $catalog->author = $user->id;
        $catalog->save();

        $response = $this->actingAs( $user )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogObjects() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $object->catalog_id = $catalog->id;
        $object->save();

        $this->visit( '/catalogs/' . $catalog->id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexCatalogContent() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $object->catalog_id = $catalog->id;
        $object->save();

        $this->visit( '/catalogs/' . $catalog->id . '/content' )
             ->see( $catalog->name )
             ->see( $object->name );
    }

    public function testCommentCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data = factory( App\Comment::class )->make()->toArray();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs/' . $catalog->id . '/comment', $data );

        $this->seeInDatabase( 'comments', ['foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogComments() {
        $comment = factory( App\Comment::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $comment->foreign_id = $catalog->id;
        $comment->foreign_type = 'catalog';
        $comment->save();

        $user = \App\User::find( $comment->author );

        $this->visit( '/catalogs/' . $catalog->id . '/comments' )
             ->see( $user->email );
    }

    public function testLikeCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs/' . $catalog->id . '/like' );

        $this->seeInDatabase( 'likes', ['foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogLikes() {
        $like = factory( App\Like::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $like->foreign_id = $catalog->id;
        $like->foreign_type = 'catalog';
        $like->save();

        $user = \App\User::find( $like->author );

        $this->visit( '/catalogs/' . $catalog->id . '/likes' )
             ->see( $user->email );
    }

    public function testFollowCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs/' . $catalog->id . '/follow' );

        $this->seeInDatabase( 'follows', ['foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogFollows() {
        $follow = factory( App\Follow::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $follow->foreign_id = $catalog->id;
        $follow->foreign_type = 'catalog';
        $follow->save();

        $user = \App\User::find( $follow->author );

        $this->visit( '/catalogs/' . $catalog->id . '/follows' )
             ->see( $user->email );
    }

}
