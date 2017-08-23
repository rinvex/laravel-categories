<?php

declare(strict_types=1);

namespace Rinvex\Categorizable\Models;

use Spatie\Sluggable\HasSlug;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Spatie\Translatable\HasTranslations;
use Rinvex\Categorizable\Builders\EloquentBuilder;
use Rinvex\Categorizable\Contracts\CategoryContract;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Rinvex\Categorizable\Models\Category.
 *
 * @property int                                                                       $id
 * @property string                                                                    $slug
 * @property array                                                                     $name
 * @property array                                                                     $description
 * @property int                                                                       $_lft
 * @property int                                                                       $_rgt
 * @property int                                                                       $parent_id
 * @property \Carbon\Carbon                                                            $created_at
 * @property \Carbon\Carbon                                                            $updated_at
 * @property \Carbon\Carbon                                                            $deleted_at
 * @property-read \Kalnoy\Nestedset\Collection|\Rinvex\Categorizable\Models\Category[] $children
 * @property-read \Rinvex\Categorizable\Models\Category|null                           $parent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categorizable\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model implements CategoryContract
{
    use HasSlug;
    use NodeTrait;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        NestedSet::LFT,
        NestedSet::RGT,
        NestedSet::PARENT_ID,
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        NestedSet::LFT => 'integer',
        NestedSet::RGT => 'integer',
        NestedSet::PARENT_ID => 'integer',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.categorizable.tables.categories'));
        $this->setRules([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'slug' => 'required|alpha_dash|max:150|unique:'.config('rinvex.categorizable.tables.categories').',slug',
            NestedSet::LFT => 'sometimes|required|integer',
            NestedSet::RGT => 'sometimes|required|integer',
            NestedSet::PARENT_ID => 'nullable|integer',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        // Auto generate slugs early before validation
        static::registerModelEvent('validating', function (self $category) {
            if (! $category->slug) {
                if ($category->exists && $category->getSlugOptions()->generateSlugsOnUpdate) {
                    $category->generateSlugOnUpdate();
                } elseif (! $category->exists && $category->getSlugOptions()->generateSlugsOnCreate) {
                    $category->generateSlugOnCreate();
                }
            }
        });
    }

    /**
     * Get all attached models of the given class to the category.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'categorizable', config('rinvex.categorizable.tables.categorizables'), 'category_id', 'categorizable_id');
    }

    /**
     * Set the translatable name attribute.
     *
     * @param string $value
     *
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode(! is_array($value) ? [app()->getLocale() => $value] : $value);
    }

    /**
     * Set the translatable description attribute.
     *
     * @param string $value
     *
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ! empty($value) ? json_encode(! is_array($value) ? [app()->getLocale() => $value] : $value) : null;
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->usingSeparator('_')
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * Get category tree.
     *
     * @return array
     */
    public static function tree(): array
    {
        return static::get()->toTree()->toArray();
    }

    /**
     * Find many categories by name or create if not exists.
     *
     * @param array       $categories
     * @param string|null $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findManyByNameOrCreate(array $categories, string $locale = null): Collection
    {
        // Expects array of category names
        return collect($categories)->map(function ($category) use ($locale) {
            return static::findByNameOrCreate($category, $locale);
        });
    }

    /**
     * Find category by name or create if not exists.
     *
     * @param string      $name
     * @param string|null $locale
     *
     * @return static
     */
    public static function findByNameOrCreate(string $name, string $locale = null): Category
    {
        $locale = $locale ?? app()->getLocale();

        return static::findByName($name, $locale) ?: static::createByName($name, $locale);
    }

    /**
     * Find category by name.
     *
     * @param string      $name
     * @param string|null $locale
     *
     * @return static|null
     */
    public static function findByName(string $name, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return static::query()->where("name->{$locale}", $name)->first();
    }

    /**
     * Create category by name.
     *
     * @param string      $name
     * @param string|null $locale
     *
     * @return static
     */
    public static function createByName(string $name, string $locale = null): Category
    {
        $locale = $locale ?? app()->getLocale();

        return static::create([
            'name' => [$locale => $name],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function newEloquentBuilder($query)
    {
        return new EloquentBuilder($query);
    }
}
