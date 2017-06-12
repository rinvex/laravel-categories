<?php

declare(strict_types=1);

namespace Rinvex\Categorizable;

use Spatie\Sluggable\HasSlug;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Rinvex\Categorizable\Category.
 *
 * @property int                                                           $id
 * @property array                                                         $name
 * @property string                                                        $slug
 * @property array                                                         $description
 * @property int                                                           $_lft
 * @property int                                                           $_rgt
 * @property int                                                           $parent_id
 * @property \Carbon\Carbon                                                $created_at
 * @property \Carbon\Carbon                                                $updated_at
 * @property \Carbon\Carbon                                                $deleted_at
 * @property-read \Rinvex\Categorizable\Category                                $parent
 * @property-read \Kalnoy\Nestedset\Collection|\Rinvex\Categorizable\Category[] $children
 *
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rinvex\Categorizable\Category whereDeletedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Category extends Model
{
    use HasSlug;
    use NodeTrait;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = ['validating', 'validated'];

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
            'name' => 'required|string|max:250',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|max:250|unique:'.config('rinvex.categorizable.tables.categories').',slug',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        if (isset(static::$dispatcher)) {
            // Early auto generate slugs before validation
            static::$dispatcher->listen('eloquent.validating: '.static::class, function (self $model) {
                if (! $model->slug) {
                    if ($model->exists) {
                        $model->generateSlugOnUpdate();
                    } else {
                        $model->generateSlugOnCreate();
                    }
                }
            });
        }
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
     * Enforce clean slugs.
     *
     * @param string $value
     *
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($value);
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
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
        return new EloquentBuilderOverride($query);
    }
}
