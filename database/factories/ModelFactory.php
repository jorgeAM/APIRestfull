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
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINITRADOR, User::USUARIO_NO_ADMINITRADOR]),
        'remember_token' => str_random(10),
    ];
});

#Factory para Category
$factory->define(Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

#Factory para Product
$factory->define(Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantify' => $faker->numberBetween(1,10),
        'status' => $faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
        'image' => $faker->randomElement(['bebidas.png', 'pringles.png', 'shampoo.png']),
        'seller_id' => User::all()->random()->id,
    ];
});

#Factory para Transaction
$factory->define(Transaction::class, function (Faker\Generator $faker) {

    $vendedor = Seller::has('products')->get()->random();
    $comprador = User::all()->except($vendedor->id)->random();

    return [
        'quantify' => $faker->numberBetween(1,3),
        'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()->id,
    ];
});