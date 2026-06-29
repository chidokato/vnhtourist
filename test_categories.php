<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mb = \App\Models\Category::where('slug', 'mien-bac')->first();
$mt = \App\Models\Category::where('slug', 'mien-trung')->first();
$mn = \App\Models\Category::where('slug', 'mien-nam')->first();

function getIds($cat) {
    if (!$cat) return [];
    $ids = [$cat->id];
    foreach ($cat->children as $c) {
        $ids = array_merge($ids, getIds($c));
    }
    return $ids;
}
$mbIds = getIds($mb);
$mtIds = getIds($mt);
$mnIds = getIds($mn);

$foreignCategory = \App\Models\Category::where('slug', 'tour-nuoc-ngoai')->first();
$foreignCategoryIds = getIds($foreignCategory);

$domesticTours = \App\Models\Post::where('type', \App\Models\Post::TYPE_PRODUCT)
    ->where('is_active', true)
    ->whereNotIn('category_id', $foreignCategoryIds)
    ->orderByDesc('is_featured')
    ->orderByDesc('published_at')
    ->orderByDesc('id')
    ->limit(8)
    ->get();

foreach ($domesticTours as $tour) {
    $catId = $tour->category_id;
    $region = 'mien-bac';
    if (in_array($catId, $mbIds)) $region = 'mien-bac';
    elseif (in_array($catId, $mtIds)) $region = 'mien-trung';
    elseif (in_array($catId, $mnIds)) $region = 'mien-nam';
    
    echo "Tour ID: {$tour->id}, Cat ID: {$catId}, Region: {$region}\n";
}
