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
	$user = factory( App\User::class, 1 )->create();

    return [
        'category_id' 		=> 1,
        'type_id'			=> 1,
        'name'				=> implode( ' ', $faker->words( 3 ) ),
        'description'		=> $faker->paragraph( 5 ),
        'retail_price'		=> $faker->randomFloat( 2, 100, 10000 ),
        'sale_price' 		=> $faker->randomFloat( 2, 100, 10000 ),
        'competitor_flag'	=> $faker->randomElement( [0, 1] ),
        'recomended'		=> $faker->randomElement( [0, 1] ),
        'curated'			=> $faker->randomElement( [0, 1] ),
        'author'			=> $user->id
    ];
} );
