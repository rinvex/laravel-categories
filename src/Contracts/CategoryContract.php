<?php

declare(strict_types=1);

namespace Rinvex\Categorizable\Contracts;

/**
 * Rinvex\Categorizable\Contracts\CategoryContract.
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
interface CategoryContract
{
    //
}
