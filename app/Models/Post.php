<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public const TYPE_PRODUCT = 'product';
    public const TYPE_NEWS = 'news';

    protected $fillable = [
        'type',
        'category_id',
        'seller_id',
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'summary',
        'sales_policy',
        'content',
        'address',
        'itinerary',
        'departure_location',
        'destination',
        'departure_date',
        'attractions',
        'transport',
        'duration',
        'guide_content',
        'visa_content',
        'insurance_content',
        'promotion_content',
        'province_id',
        'ward_id',
        'map_embed',
        'location_image',
        'area',
        'area_from',
        'area_to',
        'floor_count',
        'floor_count_from',
        'floor_count_to',
        'unit_count',
        'unit_count_from',
        'unit_count_to',
        'bedroom_count',
        'bedroom_count_from',
        'bedroom_count_to',
        'bathroom_count',
        'bathroom_count_from',
        'bathroom_count_to',
        'image',
        'price',
        'is_active',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'area' => 'decimal:2',
        'area_from' => 'decimal:2',
        'area_to' => 'decimal:2',
        'price' => 'decimal:2',
        'departure_date' => 'date',
        'published_at' => 'datetime',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_PRODUCT => 'Tour',
            self::TYPE_NEWS => 'News',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class, 'project_id')->latest();
    }

    public function customerInquiries()
    {
        return $this->hasMany(CustomerInquiry::class)->latest();
    }

    public function getFrontendUrlAttribute(): string
    {
        if ($this->category?->slug) {
            return route('frontend.content.show', [
                'categorySlug' => $this->category->slug,
                'slug' => $this->slug,
            ]);
        }

        return match ($this->type) {
            self::TYPE_PRODUCT => route('frontend.products.show.legacy', $this->slug),
            self::TYPE_NEWS => route('frontend.news.show.legacy', $this->slug),
            default => url('/' . ltrim($this->slug, '/')),
        };
    }
}
