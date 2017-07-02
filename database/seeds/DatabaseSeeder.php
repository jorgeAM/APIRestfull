<?php
#llamamos a modelos, opcional
use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	#DESACTIVAR CLAVE FORANEA
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        #borrar datos sin tabla
        User::truncate();
    	Category::truncate();
    	Product::truncate();
    	Transaction::truncate();
    	DB::table('category_product')->truncate();

    	factory(User::class, 1000)->create();
    	factory(Category::class, 30)->create();

    	#relacion muchos a muchos
    	factory(Product::class, 1000)->create()->each(function($producto){
    		$categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
    		$producto->categories()->attach($categorias);
    	});

    	factory(Transaction::class, 1000)->create();
    }
}
