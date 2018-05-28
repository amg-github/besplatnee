<?php

use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties = require(dirname(__FILE__) . '/properties.php');

        foreach($properties as $property) {
            $_property = new \App\Property([
                'name'        => $property['name'],
                'title'       => $property['title'],
                'type'        => $property['type'],
                'default'     => $property['default'],
                'description' => $property['description'],
                'options'     => $property['options'],
            ]);
            
            $_property->save();

            $_property->setParent($property['parent_id'], $property['values']);
        }
    }
}
