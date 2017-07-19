# Rinvex Categorizable

**Rinvex Categorizable** is a polymorphic Laravel package, for category management. You can categorize any eloquent model
with ease, and utilize the power of **[Nested Sets](https://github.com/lazychaser/laravel-nestedset)**,
and the awesomeness of **[Sluggable](https://github.com/spatie/laravel-sluggable)**,
and **[Translatable](https://github.com/spatie/laravel-translatable)**
models out of the box.

[![Packagist](https://img.shields.io/packagist/v/rinvex/categorizable.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/categorizable)
[![VersionEye Dependencies](https://img.shields.io/versioneye/d/php/rinvex:categorizable.svg?label=Dependencies&style=flat-square)](https://www.versioneye.com/php/rinvex:categorizable/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/categorizable.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/categorizable/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/categorizable.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/categorizable)
[![Travis](https://img.shields.io/travis/rinvex/categorizable.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/categorizable)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/109d4cb7-3826-468d-ae82-5c936a8dae2d.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/109d4cb7-3826-468d-ae82-5c936a8dae2d)
[![StyleCI](https://styleci.io/repos/87599972/shield)](https://styleci.io/repos/87599972)
[![License](https://img.shields.io/packagist/l/rinvex/categorizable.svg?label=License&style=flat-square)](https://github.com/rinvex/categorizable/blob/develop/LICENSE)


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/categorizable
    ```

2. Execute migrations via the following command:
    ```
    php artisan migrate --path="vendor/rinvex/categorizable/database/migrations"
    ```

3. Add the following service provider to the `'providers'` array inside `app/config/app.php`:
    ```php
    Rinvex\Categorizable\CategorizableServiceProvider::class,
    ```

4. **Optionally** you can publish migrations and config files by running the following command:
    ```shell
    // Publish migrations
    php artisan vendor:publish --tag="rinvex-categorizable-migrations"

    // Publish config
    php artisan vendor:publish --tag="rinvex-categorizable-config"
    ```

5. Done!


## Usage

- [Create Your Model](#create-your-model)
- [Manage Your Categories](#manage-your-categories)
- [Manage Your Categorizable Model](#manage-your-categorizable-model)
- [Generate Tag Slugs](#generate-tag-slugs)
- [Smart Parameter Detection](#smart-parameter-detection)
- [Retrieve All Models Attached To The Tag](#retrieve-all-models-attached-to-the-tag)
- [Fired Events](#fired-events)
- [Query Scopes](#query-scopes)
- [Category Translations](#category-translations)

### Create Your Model

Simply create a new eloquent model, and use `\Rinvex\Categorizable\Categorizable` trait:
```php
namespace App\Models;

use Rinvex\Categorizable\Category;
use Rinvex\Categorizable\Categorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    use Categorizable;

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
```

### Manage Your Categories

```php
use Rinvex\Categorizable\Category;

// Create a new category by name
Category::createByName('My New Category');

// Create a new category by translation
Category::createByName('تصنيف جديد', 'ar');

// Get existing category by name
Category::findByName('My New Category');

// Get existing category by translation
Category::findByName('تصنيف جديد', 'ar');

// Find category by name or create if not exists
Category::findByNameOrCreate('My Brand New Category');

// Find many categories by name or create if not exists
Category::findManyByNameOrCreate(['My Brand New Category 2', 'My Brand New Category 3']);
```

> **Notes:** since **Rinvex Categorizable** extends and utilizes other awesome packages, checkout the following
> documentations for further details:
> - Powerful Nested Sets using [`kalnoy/nestedset`](https://github.com/lazychaser/laravel-nestedset)
> - Automatic Slugging using [`spatie/laravel-sluggable`](https://github.com/spatie/laravel-sluggable)
> - Translatable out of the box using [`spatie/laravel-translatable`](https://github.com/spatie/laravel-translatable)

### Manage Your Categorizable Model

The API is intutive and very straightfarwad, so let's give it a quick look:
```php
// Instantiate your model
$post = new \App\Models\Post();

// Attach given categories to the model
$post->categorize(['my-new-category', 'my-brand-new-category']);

// Detach given categories from the model
$post->uncategorize(['my-new-category']);

// Remove all attached categories
$post->recategorize(null);

// Get attached categories collection
$post->categories;

// Get attached categories array with slugs and names
$post->categoryList();

// Check model if has any given categories
$post->hasCategory(['my-new-category', 'my-brand-new-category']);

// Check model if has any given categories
$post->hasAllCategories(['my-new-category', 'my-brand-new-category']);

// Sync given categories with the model (remove attached categories and reattach given ones)
$post->recategorize(['my-new-category', 'my-brand-new-category']);
```

### Generate Tag Slugs

**Rinvex Categorizable** auto generates slugs and auto detect and insert default translation for you, but you still
can pass it explicitly through normal eloquent `create` method, as follows:

```php
Category::create(['name' => ['en' => 'My New Category'], 'slug' => 'custom-category-slug']);
```

### Smart Parameter Detection

All categorizable methods that accept list of categories are smart enough to handle almost all kind of inputs,
for example you can pass single category slug, single category id, single category model, an array of category slugs,
an array of category ids, or a collection of category models. It will check input type and behave accordingly. Example:

```php
$post = new \App\Models\Post();

$post->hasCategory(1);
$post->hasCategory([1,2,4]);
$post->hasCategory('my-new-category');
$post->hasCategory(['my-new-category', 'my-brand-new-category']);
$post->hasCategory(Category::where('slug', 'my-new-category')->first());
$post->hasCategory(Category::whereIn('id', [5,6,7])->get());
```
**Rinvex Categorizable** can understand any of the above parameter syntax and interpret it correctly, same for other methods in this package.

### Retrieve All Models Attached To The Tag

It's very easy to get all models attached to certain category as follows:

```php
$category = Category::find(1);
$category->entries(\App\Models\Post::class);
```

### Fired Events

You can listen to the following events fired whenever there's an action on categories:

- rinvex.categorizable.attaching
- rinvex.categorizable.attached
- rinvex.categorizable.detaching
- rinvex.categorizable.detached
- rinvex.categorizable.syncing
- rinvex.categorizable.synced

### Query Scopes

Yes, **Rinvex Categorizable** shipped with few awesome query scopes for your convenience, usage example:

```php
// Get models with all given categories
$postsWithAllCategories = \App\Models\Post::withAllCategories(['my-new-category', 'my-brand-new-category'])->get();

// Get models with any given categories
$postsWithAnyCategories = \App\Models\Post::withAnyCategories(['my-new-category', 'my-brand-new-category'])->get();

// Get models without categories
$postsWithoutCategories = \App\Models\Post::withoutCategories(['my-new-category', 'my-brand-new-category'])->get();

// Get models without any categories
$postsWithoutAnyCategories = \App\Models\Post::withoutAnyCategories()->get();
```

### Category Translations

Manage category translations with ease as follows:

```php
$category = Category::find(1);

// Set category translation
$category->setTranslation('name', 'en', 'Name in English');

// Get category translation
$category->setTranslation('name', 'en');

// Get category name in default locale
$category->name;
```

___

## Advanced Usage

- [Inserting Categories](#inserting-categories)
    - [Creating categories](#creating-categories)
    - [Making a root from existing category](#making-a-root-from-existing-category)
    - [Appending and prepending to the specified parent](#appending-and-prepending-to-the-specified-parent)
    - [Inserting before or after specified category](#inserting-before-or-after-specified-category)
    - [Building a tree from array](#building-a-tree-from-array)
    - [Rebuilding a tree from array](#rebuilding-a-tree-from-array)
- [Retrieving categories](#retrieving-categories)
    - [Ancestors](#ancestors)
    - [Descendants](#descendants)
    - [Siblings](#siblings)
    - [Getting related models from other table](#getting-related-models-from-other-table)
    - [Including category depth](#including-category-depth)
    - [Default order](#default-order)
        - [Shifting a category](#shifting-a-category)
    - [Constraints](#constraints)
    - [Building a tree](#building-a-tree)
        - [Building flat tree](#building-flat-tree)
        - [Getting a subtree](#getting-a-subtree)
- [Deleting categories](#deleting-categories)
- [Helper methods](#helper-methods)
- [Checking consistency](#checking-consistency)
    - [Fixing tree](#fixing-tree)

### Inserting Categories

Moving and inserting categories includes several database queries, so **transaction is automatically started**
when category is saved. It is safe to use global transaction if you work with several models.

Another important note is that **structural manipulations are deferred** until you hit `save` on model
(some methods implicitly call `save` and return boolean result of the operation).

If model is successfully saved it doesn't mean that category was moved. If your application
depends on whether the category has actually changed its position, use `hasMoved` method:

```php
if ($category->save()) {
    $moved = $category->hasMoved();
}
```

#### Creating categories

When you simply create a category, it will be appended to the end of the tree:

```php
Category::createByName('Additional Category'); // Saved as root

Category::create($attributes); // Saved as root

$category = new Category($attributes);
$category->save(); // Saved as root
```

In this case the category is considered a _root_ which means that it doesn't have a parent.

#### Making a root from existing category

The category will be appended to the end of the tree:

```php
// #1 Implicit save
$category->saveAsRoot();

// #2 Explicit save
$category->makeRoot()->save();
```

#### Appending and prepending to the specified parent

If you want to make category a child of other category, you can make it last or first child.
Suppose that `$parent` is some existing category, there are few ways to append a category:

```php
// #1 Using deferred insert
$category->appendToNode($parent)->save();

// #2 Using parent category
$parent->appendNode($category);

// #3 Using parent's children relationship
$parent->children()->create($attributes);

// #5 Using category's parent relationship
$category->parent()->associate($parent)->save();

// #6 Using the parent attribute
$category->parent_id = $parent->id;
$category->save();

// #7 Using static method
Category::create($attributes, $parent);
```

And only a couple ways to prepend:

```php
// #1 Using deferred insert
$category->prependToNode($parent)->save();

// #2 Using parent category
$parent->prependNode($category);
```

#### Inserting before or after specified category

You can make `$category` to be a neighbor of the `$neighbor` category.
Suppose that `$neighbor` is some existing category, while target category can be fresh.
If target category exists, it will be moved to the new position and parent will be changed if it's required.

```php
# Explicit save
$category->afterNode($neighbor)->save();
$category->beforeNode($neighbor)->save();

# Implicit save
$category->insertAfterNode($neighbor);
$category->insertBeforeNode($neighbor);
```

#### Building a tree from array

When using static method `create` on category, it checks whether attributes contains `children` key.
If it does, it creates more categories recursively, as follows:

```php
$category = Category::create([
    'name' => [
        'en' => 'New Category Name',
    ],

    'children' => [
        [
            'name' => 'Bar',

            'children' => [
                [ 'name' => 'Baz' ],
            ],
        ],
    ],
]);
```

`$category->children` now contains a list of created child categories.

#### Rebuilding a tree from array

You can easily rebuild a tree. This is useful for mass-changing the structure of the tree.
Given the `$data` as an array of categories, you can build the tree as follows:

```php
$data = [
    [ 'id' => 1, 'name' => 'foo', 'children' => [ ... ] ],
    [ 'name' => 'bar' ],
];

Category::rebuildTree($data, $delete);
```

There is an id specified for category with the name of `foo` which means that existing
category will be filled and saved. If category does not exists `ModelNotFoundException` is
thrown. Also, this category has `children` specified which is also an array of categories;
they will be processed in the same manner and saved as children of category `foo`.

Category `bar` has no primary key specified, so it will treated as a new one, and be created.

`$delete` shows whether to delete categories that are already exists but not present
in `$data`. By default, categories aren't deleted.

### Retrieving categories

_In some cases we will use an `$id` variable which is an id of the target category._

#### Ancestors

Ancestors make a chain of parents to the category.
Helpful for displaying breadcrumbs to the current category.

```php
// #1 Using accessor
$result = $category->getAncestors();

// #2 Using a query
$result = $category->ancestors()->get();

// #3 Getting ancestors by primary key
$result = Category::ancestorsOf($id);
```

#### Descendants

Descendants are all categories in a sub tree,
i.e. children of category, children of children, etc.

```php
// #1 Using relationship
$result = $category->descendants;

// #2 Using a query
$result = $category->descendants()->get();

// #3 Getting descendants by primary key
$result = Category::descendantsOf($id);

// #3 Get descendants and the category by id
$result = Category::descendantsAndSelf($id);
```

Descendants can be eagerly loaded:

```php
$categories = Category::with('descendants')->whereIn('id', $idList)->get();
```

#### Siblings

Siblings are categories that have same parent.

```php
$result = $category->getSiblings();

$result = $category->siblings()->get();
```

To get only next siblings:

```php
// Get a sibling that is immediately after the category
$result = $category->getNextSibling();

// Get all siblings that are after the category
$result = $category->getNextSiblings();

// Get all siblings using a query
$result = $category->nextSiblings()->get();
```

To get previous siblings:

```php
// Get a sibling that is immediately before the category
$result = $category->getPrevSibling();

// Get all siblings that are before the category
$result = $category->getPrevSiblings();

// Get all siblings using a query
$result = $category->prevSiblings()->get();
```

#### Getting related models from other table

Imagine that each category `has many` products. I.e. `HasMany` relationship is established.
How can you get all products of `$category` and every its descendant? Easy!

```php
// Get ids of descendants
$categories = $category->descendants()->pluck('id');

// Include the id of category itself
$categories[] = $category->getKey();

// Get products
$goods = Product::whereIn('category_id', $categories)->get();
```

Now imagine that each category `has many` posts. I.e. `morphToMany` relationship is established this time.
How can you get all posts of `$category` and every its descendant? Is that even possible?! Sure!

```php
// Get ids of descendants
$categories = $category->descendants()->pluck('id');

// Include the id of category itself
$categories[] = $category->getKey();

// Get posts
$posts = \App\Models\Post::withCategories($categories)->get();
```

#### Including category depth

If you need to know at which level the category is:

```php
$result = Category::withDepth()->find($id);

$depth = $result->depth;
```

Root category will be at level 0. Children of root categories will have a level of 1, etc.
To get categories of specified level, you can apply `having` constraint:

```php
$result = Category::withDepth()->having('depth', '=', 1)->get();
```

#### Default order

Each category has it's own unique `_lft` value that determines its position in the tree.
If you want category to be ordered by this value, you can use `defaultOrder` method
on the query builder:

```php
// All categories will now be ordered by lft value
$result = Category::defaultOrder()->get();
```

You can get categories in reversed order:

```php
$result = Category::reversed()->get();
```

##### Shifting a category

To shift category up or down inside parent to affect default order:

```php
$bool = $category->down();
$bool = $category->up();

// Shift category by 3 siblings
$bool = $category->down(3);
```

The result of the operation is boolean value of whether the category has changed its position.

#### Constraints

Various constraints that can be applied to the query builder:

- **whereIsRoot()** to get only root categories;
- **whereIsAfter($id)** to get every category (not just siblings) that are after a category with specified id;
- **whereIsBefore($id)** to get every category that is before a category with specified id.

Descendants constraints:

```php
$result = Category::whereDescendantOf($category)->get();
$result = Category::whereNotDescendantOf($category)->get();
$result = Category::orWhereDescendantOf($category)->get();
$result = Category::orWhereNotDescendantOf($category)->get();

// Include target category into result set
$result = Category::whereDescendantOrSelf($category)->get();
```

Ancestor constraints:

```php
$result = Category::whereAncestorOf($category)->get();
```

`$category` can be either a primary key of the model or model instance.

#### Building a tree

After getting a set of categories, you can convert it to tree. For example:

```php
$tree = Category::get()->toTree();
```

This will fill `parent` and `children` relationships on every category in the set and
you can render a tree using recursive algorithm:

```php
$categories = Category::get()->toTree();

$traverse = function ($categories, $prefix = '-') use (&$traverse) {
    foreach ($categories as $category) {
        echo PHP_EOL.$prefix.' '.$category->name;

        $traverse($category->children, $prefix.'-');
    }
};

$traverse($categories);
```

This will output something like this:

```
- Root
-- Child 1
--- Sub child 1
-- Child 2
- Another root
```

##### Building flat tree

Also, you can build a flat tree: a list of categories where child categories are immediately
after parent category. This is helpful when you get categories with custom order
(i.e. alphabetically) and don't want to use recursion to iterate over your categories.

```php
$categories = Category::get()->toFlatTree();
```

##### Getting a subtree

Sometimes you don't need whole tree to be loaded and just some subtree of specific category:

```php
$root = Category::find($rootId);
$tree = $root->descendants->toTree($root);
```

Now `$tree` contains children of `$root` category.

If you don't need `$root` category itself, do following instead:

```php
$tree = Category::descendantsOf($rootId)->toTree($rootId);
```

### Deleting categories

To delete a category:

```php
$category->delete();
```

**IMPORTANT!** Any descendant that category has will also be **deleted**!

**IMPORTANT!** Categories are required to be deleted as models, **don't** try do delete them using a query like so:

```php
Category::where('id', '=', $id)->delete();
```

**That will break the tree!**

`SoftDeletes` trait is supported, also on model level.

### Helper methods

```php
// Check if category is a descendant of other category
$bool = $category->isDescendantOf($parent);

// Check whether the category is a root:
$bool = $category->isRoot();

// Other checks
$category->isChildOf($other);
$category->isAncestorOf($other);
$category->isSiblingOf($other);
```

### Checking consistency

You can check whether a tree is broken (i.e. has some structural errors):

```php
// Check if tree is broken
$bool = Category::isBroken();

// Get tree error statistics
$data = Category::countErrors();
```

Tree error statistics will return an array with following keys:

- `oddness` -- the number of categories that have wrong set of `lft` and `rgt` values
- `duplicates` -- the number of categories that have same `lft` or `rgt` values
- `wrong_parent` -- the number of categories that have invalid `parent_id` value that doesn't correspond to `lft` and `rgt` values
- `missing_parent` -- the number of categories that have `parent_id` pointing to category that doesn't exists

#### Fixing tree

Category tree can now be fixed if broken. Using inheritance info from `parent_id` column,
proper `_lft` and `_rgt` values are set for every category.

```php
Category::fixTree();
```


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](http://chat.rinvex.com)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

We want to ensure that this package is secure for everyone. If you've discovered a security vulnerability in this package, we appreciate your help in disclosing it to us in a [responsible manner](https://en.wikipedia.org/wiki/Responsible_disclosure).

Publicly disclosing a vulnerability can put the entire community at risk. If you've discovered a security concern, please email us at [help@rinvex.com](mailto:help@rinvex.com). We'll work with you to make sure that we understand the scope of the issue, and that we fully address your concern. We consider correspondence sent to [help@rinvex.com](mailto:help@rinvex.com) our highest priority, and work to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a security hotfix release will be deployed as soon as possible.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2017 Rinvex LLC, Some rights reserved.
