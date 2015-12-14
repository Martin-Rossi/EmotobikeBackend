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
        $category = factory( App\Category::class, 1 )->create();
        $object = factory( App\Object::class, 1 )->create();

        $object->category_id = $category->id;
        $object->save();

        $this->visit( '/categories/' . $category->id . '/objects' )
             ->see( $object->name );
    }

    public function testIndexCategoryCatalogs() {
        $category = factory( App\Category::class, 1 )->create();
        $catalog = factory( App\Catalog::class, 1 )->create();

        $catalog->category_id = $category->id;
        $catalog->save();

        $this->visit( '/categories/' . $category->id . '/catalogs' )
             ->see( $catalog->name );
    }

}
