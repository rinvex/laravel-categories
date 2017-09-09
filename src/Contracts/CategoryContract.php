<?php

declare(strict_types=1);

namespace Rinvex\Categories\Contracts;

/**
 * Rinvex\Categories\Contracts\CategoryContract.
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
 * @property-read \Kalnoy\Nestedset\Collection|\Rinvex\Categories\Models\Category[] $children
 * @property-read \Rinvex\Categories\Models\Category|null                           $parent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Categories\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface CategoryContract
{
    //
}
