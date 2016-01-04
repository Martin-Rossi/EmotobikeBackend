<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase {

    use WithoutMiddleware;

    public function testIndexCategories() {
        $category = factory( App\Category::class, 1 )->create();

        $this->visit( '/categories' )
             ->see( $category->name );
    }

    public function testShowCategory() {
        $category = factory( App\Category::class, 1 )->create();

        $this->visit( '/categories/' . $category->id )
             ->see( $category->name );
    }

    public function testIndexCategoryObjects() {
        $object = factory( App\Object::class, 1 )->create();

        $this->visit( '/categories/' . $object->category_id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexCategoryCatalogs() {
        $catalog = factory( App\Catalog::class, 1 )->create();

        $this->visit( '/categories/' . $catalog->category_id . '/catalogs' )
             ->see( $catalog->name );
    }

}
