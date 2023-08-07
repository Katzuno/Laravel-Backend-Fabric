<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @OA\Schema(
 *     schema="Record",
 *     required={"title", "release_year", "imdb_id"},
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the record",
 *         example="A Record Title"
 *     ),
 *     @OA\Property(
 *         property="release_year",
 *         type="integer",
 *         description="The release year of the record",
 *         example=2023
 *     ),
 *     @OA\Property(
 *         property="imdb_id",
 *         type="string",
 *         description="The IMDB id of the record",
 *         example="tt0000000"
 *     ),
 *     @OA\Property(
 *         property="images",
 *         type="string",
 *         description="Images of the record",
 *         example="http://example.com/image.jpg"
 *     )
 * )
 */

class Record extends Pivot
{
    use HasFactory, Searchable;

    protected $table = 'records';

    public $incrementing = true;

    protected $fillable = [
        'title',
        'release_year',
        'imdb_id',
        'images',
        'metadata'
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        if ($this->metadata)    {
            return json_decode($this->metadata, true);
        } else {
            return [];
        }
    }
}
