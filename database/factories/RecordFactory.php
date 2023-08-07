<?php


namespace Database\Factories;

use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Record::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'release_year' => $this->faker->year((int)date('Y') + 2),
            'imdb_id' => 'tt' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'images' => $this->faker->imageUrl(640, 480, 'movies', true, 'Faker')
        ];
    }
}
