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
        'name' 				=> $faker->name,
        'email' 			=> $faker->email,
        'password' 			=> bcrypt( 'test' ),
        'remember_token' 	=> str_random( 10 ),
    ];
} );

$factory->define( App\Object::class, function( Faker\Generator $faker ) {
	$catalog = factory( App\Catalog::class, 1 )->create();
    $type = factory( App\Type::class, 1 )->create();

    return [
        'catalog_id' 		=> $catalog->id,
        'type_id'			=> $type->id,
        'name'				=> implode( ' ', $faker->words( 3 ) ),
        'description'		=> $faker->paragraph( 5 ),
        'url'               => $faker->url,
        'retail_price'		=> $faker->randomFloat( 2, 100, 10000 ),
        'sale_price' 		=> $faker->randomFloat( 2, 100, 10000 ),
        'layout'            => implode( ' ', $faker->words( 1 ) ),
        'position'          => implode( ' ', $faker->words( 1 ) ),
        'competitor_flag'	=> $faker->randomElement( [0, 1] ),
        'recomended'		=> $faker->randomElement( [0, 1] ),
        'curated'			=> $faker->randomElement( [0, 1] )
    ];
} );

$factory->define( App\Catalog::class, function( Faker\Generator $faker ) {
    return [
        'collection_id'     => 1,
        'name'              => implode( ' ', $faker->words( 3 ) ),
        'title'             => implode( ' ', $faker->words( 3 ) )
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
