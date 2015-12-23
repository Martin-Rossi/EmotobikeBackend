<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define( App\User::class, function( Faker\Generator $faker ) {
    return [
        'tags'              => implode( ';', $faker->words( 5 ) ),
        'name' 				=> $faker->name,
        'email' 			=> $faker->email,
        'password' 			=> bcrypt( 'test' ),
        'remember_token' 	=> str_random( 10 ),
        'avatar'            => $faker->imageUrl()
    ];
} );

$factory->define( App\Object::class, function( Faker\Generator $faker ) {
	$catalog = factory( App\Catalog::class, 1 )->create();
    $category = factory( App\Category::class, 1 )->create();
    $type = factory( App\Type::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'catalog_id' 		=> $catalog->id,
        'category_id'       => $category->id,
        'type_id'			=> $type->id,
        'tags'              => implode( ';', $faker->words( 5 ) ),
        'name'				=> implode( ' ', $faker->words( 3 ) ),
        'description'		=> $faker->paragraph( 5 ),
        'url'               => $faker->url,
        'image'             => $faker->imageUrl(),
        'weight'            => $faker->randomFloat( 4, 10, 100 ),
        'retail_price'		=> $faker->randomFloat( 2, 100, 10000 ),
        'sale_price' 		=> $faker->randomFloat( 2, 100, 10000 ),
        'offer_value'       => $faker->randomFloat( 2, 100, 10000 ),
        'offer_url'         => $faker->url,
        'offer_description' => $faker->paragraph( 1 ),
        'offer_start'       => $faker->dateTime(),
        'offer_stop'        => $faker->dateTime(),
        'layout'            => $faker->numberBetween( 1  ,12 ),
        'position'          => $faker->numberBetween( 1  ,19 ),
        'competitor_flag'	=> $faker->randomElement( [0, 1] ),
        'recomended'		=> $faker->randomElement( [0, 1] ),
        'curated'			=> $faker->randomElement( [0, 1] ),
        'author'            => $user->id
    ];
} );

$factory->define( App\Catalog::class, function( Faker\Generator $faker ) {
    $category = factory( App\Category::class, 1 )->create();
    $type = factory( App\Type::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'category_id'       => $category->id,
        'type_id'           => $type->id,
        'tags'              => implode( ';', $faker->words( 5 ) ),
        'name'              => implode( ' ', $faker->words( 3 ) ),
        'title'             => implode( ' ', $faker->words( 3 ) ),
        'layout'            => $faker->numberBetween( 1  ,12 ),
        'position'          => $faker->numberBetween( 1  ,19 ),
        'publish'           => $faker->randomElement( [0, 1] ),
        'trending'          => $faker->randomElement( [0, 1] ),
        'popular'           => $faker->randomElement( [0, 1] ),
        'recomended'        => $faker->randomElement( [0, 1] ),
        'author'            => $user->id
    ];
} );

$factory->define( App\Collection::class, function( Faker\Generator $faker ) {
    $catalog = factory( App\Catalog::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'collection_id'     => $faker->numberBetween( 1, 20 ),
        'foreign_id'        => $catalog->id,
        'foreign_type'      => 'catalog',
        'author'            => $user->id
    ];
} );

$factory->define( App\Category::class, function( Faker\Generator $faker ) {
    return [
        'name'              => implode( ' ', $faker->words( 1 ) )
    ];
} );

$factory->define( App\Type::class, function( Faker\Generator $faker ) {
    return [
        'name'              => implode( ' ', $faker->words( 1 ) )
    ];
} );

$factory->define( App\Comment::class, function( Faker\Generator $faker ) {
    $object = factory( App\Object::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'foreign_id'        => $object->id,
        'foreign_type'      => 'object',
        'text'              => $faker->paragraph( 1 ),
        'author'            => $user->id
    ];
} );

$factory->define( App\Like::class, function( Faker\Generator $faker ) {
    $object = factory( App\Object::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'foreign_id'        => $object->id,
        'foreign_type'      => 'object',
        'author'            => $user->id
    ];
} );

$factory->define( App\Follow::class, function( Faker\Generator $faker ) {
    $object = factory( App\Object::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'foreign_id'        => $object->id,
        'foreign_type'      => 'object',
        'author'            => $user->id
    ];
} );

$factory->define( App\Feedback::class, function( Faker\Generator $faker ) {
    $object = factory( App\Object::class, 1 )->create();
    $user = factory( App\User::class, 1 )->create();

    return [
        'foreign_id'        => $object->id,
        'foreign_type'      => 'object',
        'value'             => $faker->numberBetween( 100, 100000 ),
        'author'            => $user->id
    ];
} );

$factory->define( App\Activity::class, function( Faker\Generator $faker ) {
    $catalog = factory( App\Catalog::class, 1 )->create();
    $type = factory( App\Type::class, 1 )->create();

    return [
        'catalog_id'        => $catalog->id,
        'type_id'           => $type->id,
        'name'              => implode( ' ', $faker->words( 1 ) ),
        'description'       => $faker->paragraph( 1 )
    ];
} );
