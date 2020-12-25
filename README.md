# Rinvex Categories

**Rinvex Categories** is a polymorphic Laravel package, for category management. You can categorize any eloquent model with ease, and utilize the power of **[Nested Sets](https://github.com/lazychaser/laravel-nestedset)**, and the awesomeness of **[Sluggable](https://github.com/spatie/laravel-sluggable)**, and **[Translatable](https://github.com/spatie/laravel-translatable)** models out of the box.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-categories.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-categories)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-categories.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-categories/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-categories.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-categories)
[![StyleCI](https://styleci.io/repos/87599972/shield)](https://styleci.io/repos/87599972)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-categories.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-categories/blob/develop/LICENSE)


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/laravel-categories
    ```

2. Publish resources (migrations and config files):
    ```shell
    php artisan rinvex:publish:categories
    ```

3. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:categories
    ```

4. Done!


## Usage

To add categories support to your eloquent models simply use `\Rinvex\Categories\Traits\Categorizable` trait.

### Manage your categories

Your categories are just normal [eloquent](https://laravel.com/docs/master/eloquent) models, so you can deal with it like so. Nothing special here!

> **Notes:** since **Rinvex Categories** extends and utilizes other awesome packages, checkout the following documentations for further details:
> - Powerful Nested Sets using [`kalnoy/nestedset`](https://github.com/lazychaser/laravel-nestedset)
> - Automatic Slugging using [`spatie/laravel-sluggable`](https://github.com/spatie/laravel-sluggable)
> - Translatable out of the box using [`spatie/laravel-translatable`](https://github.com/spatie/laravel-translatable)

### Manage your categorizable model

The API is intutive and very straightfarwad, so let's give it a quick look:

```php
// Get all categories
$allCategories = app('rinvex.categories.category')->first();

// Get instance of your model
$post = new \App\Models\Post::find();

// Get attached categories collection
$post->categories;

// Get attached categories query builder
$post->categories();
```

You can attach categories in various ways:

```php
// Single category id
$post->attachCategories(1);

// Multiple category IDs array
$post->attachCategories([1, 2, 5]);

// Multiple category IDs collection
$post->attachCategories(collect([1, 2, 5]));

// Single category model instance
$categoryInstance = app('rinvex.categories.category')->first();
$post->attachCategories($categoryInstance);

// Single category slug
$post->attachCategories('test-category');

// Multiple category slugs array
$post->attachCategories(['first-category', 'second-category']);

// Multiple category slugs collection
$post->attachCategories(collect(['first-category', 'second-category']));

// Multiple category model instances
$categoryInstances = app('rinvex.categories.category')->whereIn('id', [1, 2, 5])->get();
$post->attachCategories($categoryInstances);
```

> **Notes:** 
> - The `attachCategories()` method attach the given categories to the model without touching the currently attached categories, while there's the `syncCategories()` method that can detach any records that's not in the given items, this method takes a second optional boolean parameter that's set detaching flag to `true` or `false`.
> - To detach model categories you can use the `detachCategories()` method, which uses **exactly** the same signature as the `attachCategories()` method, with additional feature of detaching all currently attached categories by passing null or nothing to that method as follows: `$post->detachCategories();`.

And as you may have expected, you can check if categories attached:

```php
// Single category id
$post->hasAnyCategories(1);

// Multiple category IDs array
$post->hasAnyCategories([1, 2, 5]);

// Multiple category IDs collection
$post->hasAnyCategories(collect([1, 2, 5]));

// Single category model instance
$categoryInstance = app('rinvex.categories.category')->first();
$post->hasAnyCategories($categoryInstance);

// Single category slug
$post->hasAnyCategories('test-category');

// Multiple category slugs array
$post->hasAnyCategories(['first-category', 'second-category']);

// Multiple category slugs collection
$post->hasAnyCategories(collect(['first-category', 'second-category']));

// Multiple category model instances
$categoryInstances = app('rinvex.categories.category')->whereIn('id', [1, 2, 5])->get();
$post->hasAnyCategories($categoryInstances);
```

> **Notes:** 
> - The `hasAnyCategories()` method check if **ANY** of the given categories are attached to the model. It returns boolean `true` or `false` as a result.
> - Similarly the `hasAllCategories()` method uses **exactly** the same signature as the `hasAnyCategories()` method, but it behaves differently and performs a strict comparison to check if **ALL** of the given categories are attached.

### Advanced usage

#### Generate category slugs

**Rinvex Categories** auto generates slugs and auto detect and insert default translation for you if not provided, but you still can pass it explicitly through normal eloquent `create` method, as follows:

```php
app('rinvex.categories.category')->create(['name' => ['en' => 'My New Category'], 'slug' => 'custom-category-slug']);
```

> **Note:** Check **[Sluggable](https://github.com/spatie/laravel-sluggable)** package for further details.

#### Smart parameter detection

**Rinvex Categories** methods that accept list of categories are smart enough to handle almost all kinds of inputs as you've seen in the above examples. It will check input type and behave accordingly. 

#### Retrieve all models attached to the category

You may encounter a situation where you need to get all models attached to certain category, you do so with ease as follows:

```php
$category = app('rinvex.categories.category')->find(1);
$category->entries(\App\Models\Post::class)->get();
```

#### Query scopes

Yes, **Rinvex Categories** shipped with few awesome query scopes for your convenience, usage example:

```php
// Single category id
$post->withAnyCategories(1)->get();

// Multiple category IDs array
$post->withAnyCategories([1, 2, 5])->get();

// Multiple category IDs collection
$post->withAnyCategories(collect([1, 2, 5]))->get();

// Single category model instance
$categoryInstance = app('rinvex.categories.category')->first();
$post->withAnyCategories($categoryInstance)->get();

// Single category slug
$post->withAnyCategories('test-category')->get();

// Multiple category slugs array
$post->withAnyCategories(['first-category', 'second-category'])->get();

// Multiple category slugs collection
$post->withAnyCategories(collect(['first-category', 'second-category']))->get();

// Multiple category model instances
$categoryInstances = app('rinvex.categories.category')->whereIn('id', [1, 2, 5])->get();
$post->withAnyCategories($categoryInstances)->get();
```

> **Notes:**
> - The `withAnyCategories()` scope finds posts with **ANY** attached categories of the given. It returns normally a query builder, so you can chain it or call `get()` method for example to execute and get results.
> - Similarly there's few other scopes like `withAllCategories()` that finds posts with **ALL** attached categories of the given, `withoutCategories()` which finds posts without **ANY** attached categories of the given, and lastly `withoutAnyCategories()` which find posts without **ANY** attached categories at all. All scopes are created equal, with same signature, and returns query builder.

#### Category translations

Manage category translations with ease as follows:

```php
$category = app('rinvex.categories.category')->find(1);

// Update title translations
$category->setTranslation('name', 'en', 'New English Category Title')->save();

// Alternatively you can use default eloquent update
$category->update([
    'name' => [
        'en' => 'New Category',
        'ar' => 'تصنيف جديد',
    ],
]);

// Get single category translation
$category->getTranslation('name', 'en');

// Get all category translations
$category->getTranslations('name');

// Get category title in default locale
$category->name;
```

> **Note:** Check **[Translatable](https://github.com/spatie/laravel-translatable)** package for further details.

___

## Manage your nodes/nestedsets

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

### Inserting categories

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
app('rinvex.categories.category')->create($attributes); // Saved as root

$category = app('rinvex.categories.category')->fill($attributes);
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
$category->parent_id = $parent->getKey();
$category->save();

// #7 Using static method
app('rinvex.categories.category')->create($attributes, $parent);
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
$category = app('rinvex.categories.category')->create([
    'name' => [
        'en' => 'New Category Title',
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

app('rinvex.categories.category')->rebuildTree($data, $delete);
```

There is an id specified for category with the title of `foo` which means that existing
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
$result = app('rinvex.categories.category')->ancestorsOf($id);
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
$result = app('rinvex.categories.category')->descendantsOf($id);

// #3 Get descendants and the category by id
$result = app('rinvex.categories.category')->descendantsAndSelf($id);
```

Descendants can be eagerly loaded:

```php
$categories = app('rinvex.categories.category')->with('descendants')->whereIn('id', $idList)->get();
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
$result = app('rinvex.categories.category')->withDepth()->find($id);

$depth = $result->depth;
```

Root category will be at level 0. Children of root categories will have a level of 1, etc.
To get categories of specified level, you can apply `having` constraint:

```php
$result = app('rinvex.categories.category')->withDepth()->having('depth', '=', 1)->get();
```

#### Default order

Each category has it's own unique `_lft` value that determines its position in the tree.
If you want category to be ordered by this value, you can use `defaultOrder` method
on the query builder:

```php
// All categories will now be ordered by lft value
$result = app('rinvex.categories.category')->defaultOrder()->get();
```

You can get categories in reversed order:

```php
$result = app('rinvex.categories.category')->reversed()->get();
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
$result = app('rinvex.categories.category')->whereDescendantOf($category)->get();
$result = app('rinvex.categories.category')->whereNotDescendantOf($category)->get();
$result = app('rinvex.categories.category')->orWhereDescendantOf($category)->get();
$result = app('rinvex.categories.category')->orWhereNotDescendantOf($category)->get();

// Include target category into result set
$result = app('rinvex.categories.category')->whereDescendantOrSelf($category)->get();
```

Ancestor constraints:

```php
$result = app('rinvex.categories.category')->whereAncestorOf($category)->get();
```

`$category` can be either a primary key of the model or model instance.

#### Building a tree

After getting a set of categories, you can convert it to tree. For example:

```php
$tree = app('rinvex.categories.category')->get()->toTree();
```

This will fill `parent` and `children` relationships on every category in the set and
you can render a tree using recursive algorithm:

```php
$categories = app('rinvex.categories.category')->get()->toTree();

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
$categories = app('rinvex.categories.category')->get()->toFlatTree();
```

##### Getting a subtree

Sometimes you don't need whole tree to be loaded and just some subtree of specific category:

```php
$root = app('rinvex.categories.category')->find($rootId);
$tree = $root->descendants->toTree($root);
```

Now `$tree` contains children of `$root` category.

If you don't need `$root` category itself, do following instead:

```php
$tree = app('rinvex.categories.category')->descendantsOf($rootId)->toTree($rootId);
```

### Deleting categories

To delete a category:

```php
$category->delete();
```

**IMPORTANT!** Any descendant that category has will also be **deleted**!

**IMPORTANT!** Categories are required to be deleted as models, **don't** try do delete them using a query like so:

```php
app('rinvex.categories.category')->where('id', '=', $id)->delete();
```

**That will break the tree!**


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
$bool = app('rinvex.categories.category')->isBroken();

// Get tree error statistics
$data = app('rinvex.categories.category')->countErrors();
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
app('rinvex.categories.category')->fixTree();
```

> **Note:** Check **[Nested Sets](https://github.com/lazychaser/laravel-nestedset)** package for further details.


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
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

(c) 2016-2021 Rinvex LLC, Some rights reserved.
