<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CatalogTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCatalogs() {
        $user = \App\User::find( 1 );

        $this->actingAs( $user )->visit( '/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testShowCatalog() {
        $user = \App\User::find( 1 );

        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->actingAs( $user )->visit( '/catalogs/' . $catalog->id )
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

        $user = \App\User::find( $catalog->author );

        $response = $this->actingAs( $user )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateCatalogByCurator() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $data = factory( App\Catalog::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $user = \App\User::find( $catalog->author );

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id= $user->id;
        $curator->save();

        $response = $this->actingAs( $curator )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateCatalogByParent() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $data = factory( App\Catalog::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $curator = \App\User::find( $catalog->author );

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save();

        $response = $this->actingAs( $parent )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testUpdateCatalogByAdmin() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $data = factory( App\Catalog::class, 1 )->make()->toArray();

        $data['category'] = 'test';
        $data['type'] = 'test';

        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'PUT', '/catalogs/' . $catalog->id, $data );

        $this->seeInDatabase( 'catalogs', ['name' => $data['name']] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $response = $this->actingAs( $user )->call( 'DELETE', '/catalogs/' . $catalog->id );

        $this->seeInDatabase( 'catalogs', ['id' => $catalog->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteCatalogByCurator() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id = $user->id;
        $curator->save(); 

        $response = $this->actingAs( $curator )->call( 'DELETE', '/catalogs/' . $catalog->id );

        $this->seeInDatabase( 'catalogs', ['id' => $catalog->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteCatalogByParent() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $curator = \App\User::find( $catalog->author );

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save(); 

        $response = $this->actingAs( $parent )->call( 'DELETE', '/catalogs/' . $catalog->id );

        $this->seeInDatabase( 'catalogs', ['id' => $catalog->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testDeleteCatalogByAdmin() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $admin = \App\User::find( 1 );

        $response = $this->actingAs( $admin )->call( 'DELETE', '/catalogs/' . $catalog->id );

        $this->seeInDatabase( 'catalogs', ['id' => $catalog->id, 'status' => -1] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexDeletedCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $catalog->status = -1;
        $catalog->save();

        $this->actingAs( $user )->visit( '/deleted/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDeletedCatalogsByCurator() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $catalog->status = -1;
        $catalog->save();

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id = $user->id;
        $curator->save(); 

        $this->actingAs( $curator )->visit( '/deleted/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDeletedCatalogsByParent() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $curator = \App\User::find( $catalog->author );

        $catalog->status = -1;
        $catalog->save();

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save(); 

        $this->actingAs( $parent )->visit( '/deleted/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDraftCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $catalog->status = 0;
        $catalog->save();

        $this->actingAs( $user )->visit( '/draft/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDraftCatalogsByCurator() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $user = \App\User::find( $catalog->author );

        $catalog->status = 0;
        $catalog->save();

        $curator = factory( App\User::class, 1 )->create();

        $curator->parent_id = $user->id;
        $curator->save(); 

        $this->actingAs( $curator )->visit( '/draft/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testIndexDraftCatalogsByParent() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $curator = \App\User::find( $catalog->author );

        $catalog->status = 0;
        $catalog->save();

        $parent = factory( App\User::class, 1 )->create();

        $curator->parent_id = $parent->id;
        $curator->save(); 

        $this->actingAs( $parent )->visit( '/draft/catalogs' )
             ->seeJson( ['type' => 'result'] );
    }

    public function testSearchCatalogs() {
        $user = \App\User::find( 1 );

        $catalog = factory( App\Catalog::class, 1 )->create();

        $data = [
            'term' => substr( $catalog->name, 0, 4 )
        ];

        $response = $this->actingAs( $user )->call( 'POST', '/search/catalogs', $data );
            
        $this->seeJson( ['type' => 'result'] );
    }

    public function testFilterCatalogs() {
        $user = \App\User::find( 1 );

        $catalog = factory( App\Catalog::class, 1 )->create();
        
        $data = [
            'filter'   => 'category_id',
            'operator' => '=',
            'value'    => $catalog->category_id
        ];

        $response = $this->actingAs( $user )->call( 'POST', '/filter/catalogs', $data );
            
        $this->seeJson( ['type' => 'result'] );
    }

    public function testIndexCatalogObjects() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $this->actingAs( $user )->visit( '/catalogs/' . $object->catalog_id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexCatalogProducts() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $object->type_id = 2;
        $object->save();

        $this->actingAs( $user )->visit( '/catalogs/' . $object->catalog_id . '/products' )
             ->see( $object->name );
    }

    public function testIndexCatalogContent() {
        $user = \App\User::find( 1 );

        $object = factory( App\Object::class, 1 )->create();

        $catalog = \App\Catalog::find( $object->catalog_id );

        $this->actingAs( $user )->visit( '/catalogs/' . $object->catalog_id . '/content' )
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

    public function testRecommendCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs/' . $catalog->id . '/recommend' );

        $this->seeInDatabase( 'recommendations', ['foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogRecommendations() {
        $recommendation = factory( App\Recommendation::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $recommendation->foreign_id = $catalog->id;
        $recommendation->foreign_type = 'catalog';
        $recommendation->save();

        $user = \App\User::find( $recommendation->author );

        $this->visit( '/catalogs/' . $catalog->id . '/recommendations' )
             ->see( $user->email );
    }

    public function testFeedbackCatalog() {
        $catalog = factory( App\Catalog::class, 1 )->create();
        $user = factory( App\User::class )->create();

        $data = factory( App\Feedback::class )->make()->toArray();

        $response = $this->actingAs( $user )->call( 'POST', '/catalogs/' . $catalog->id . '/feedback', $data );

        $this->seeInDatabase( 'feedbacks', ['foreign_id' => $catalog->id, 'foreign_type' => 'catalog', 'author' => $user->id] )
             ->assertEquals( 200, $response->status() );
    }

    public function testIndexCatalogFeedbacks() {
        $feedback = factory( App\Feedback::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $feedback->foreign_id = $catalog->id;
        $feedback->foreign_type = 'catalog';
        $feedback->save();

        $user = \App\User::find( $feedback->author );

        $this->visit( '/catalogs/' . $catalog->id . '/feedbacks' )
             ->see( $user->email );
    }

    public function testIndexCatalogActivities() {
        $activity = factory( App\Activity::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $activity->catalog_id = $catalog->id;
        $activity->save();

        $this->visit( '/catalogs/' . $catalog->id . '/activities' )
             ->see( $activity->name );
    }

}
