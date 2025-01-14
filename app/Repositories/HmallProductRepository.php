<?php

namespace App\Repositories;

use App\HmallPriceHistory;
use App\HmallProduct;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yish\Generators\Foundation\Repository\Repository;

class HmallProductRepository extends Repository
{
    protected $model;

    private const CACHE_KEY_LIMITED_OFFER = 'hmall_product:limited_offer';
    private const CACHE_KEY_SALE = 'hmall_product:sale';
    private const CACHE_KEY_MOST_REVIEWED = 'hmall_product:most_reviewed';
    private const CACHE_KEY_NEW = 'hmall_product:new';
    private const CACHE_KEY_COMING_SOON = 'hmall_product:coming_soon';
    private const CACHE_KEY_MULTI_BUY = 'hmall_product:multi_buy';
    private const CACHE_KEY_ONLINE_SPECIAL = 'hmall_product:online_special';
    private const SELECT_COLUMNS_FOR_LIST = [
        'id',
        'code',
        'product_code',
        'name',
        'min_price',
        'lowest_record_price',
        'highest_record_price',
        'identity',
        'time_limited_begin',
        'time_limited_end',
        'score',
        'evaluation_count',
        'main_first_pic',
        'gender',
        'sex',
        'stock',
        'stockout_at',
        'updated_at',
    ];

    public function __construct(HmallProduct $model, HmallPriceHistory $hmallPriceHistory)
    {
        $this->model = $model;
        $this->hmallPriceHistory = $hmallPriceHistory;
    }

    public function getRelatedHmallProducts(HmallProduct $hmallProduct, $excludeItself = true)
    {
        $query = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where(function ($query) use ($hmallProduct) {
                $query->where('code', $hmallProduct->code)
                    ->orWhere(function ($query) use ($hmallProduct) {
                        $query->where('name', 'like', "%{$hmallProduct->name}%")
                            ->where('gender', $hmallProduct->gender);
                    });
            });

        if ($excludeItself) {
            $query->where('id', '<>', $hmallProduct->id);
        }

        return $query->orderByRaw('CASE WHEN `id` = ? THEN 0 ELSE 1 END', [$hmallProduct->id])
            ->orderByRaw('CASE WHEN `name` = ? THEN 0 ELSE 1 END', [$hmallProduct->name])
            ->orderBy(DB::raw('ISNULL(`stockout_at`)'), 'desc')
            ->orderByRaw('CHAR_LENGTH(`name`)')
            ->orderBy('min_price')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getRelatedHmallProductsForProduct(Product $product)
    {
        $relatedId = substr($product->id, 0, 6);

        $hmallProduct = $this->model
            ->where('code', $relatedId)
            ->orderBy(DB::raw('ISNULL(`stockout_at`)'), 'desc')
            ->orderBy('min_price')
            ->orderBy('id', 'desc')
            ->first();

        if ($hmallProduct) {
            return $this->getRelatedHmallProducts($hmallProduct, false);
        }

        $similarHmallProducts = $this->getSimilarHmallProductsFromProduct($product);

        if ($similarHmallProducts->isNotEmpty()) {
            return $similarHmallProducts;
        }

        $sexTypes = ['男裝', '女裝', '童裝', '男童', '女童', '新生兒', '嬰幼兒'];
        $sexTypesPattern = implode('|', $sexTypes);

        preg_match("/({$sexTypesPattern})?(.*)/", $product->name, $matches);

        $relatedName = trim($matches[2]);
        $relatedSex = trim($matches[1]);

        return $this->getRelatedHmallProductsByName($relatedName, $relatedSex);
    }

    public function getSimilarHmallProductsFromProduct(Product $product)
    {
        $productRepository = app(ProductRepository::class);

        $relatedProducts = $productRepository->getRelatedProducts($product);
        $relatedProductIds = $relatedProducts->pluck('id')->all();

        return $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->whereIn('code', $relatedProductIds)
            ->orderBy(DB::raw('ISNULL(`stockout_at`)'), 'desc')
            ->orderBy('min_price')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getRelatedHmallProductsByName(string $name, string $sex = '')
    {
        return $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('product_name', 'like', "%{$name}%")
            ->where(function ($query) use ($sex) {
                $query->where('product_name', 'like', "%{$sex}%")
                    ->orWhere('gender', 'like', "%{$sex}%");
            })
            ->orderByRaw('CASE WHEN `name` = ? THEN 0 ELSE 1 END', [$name])
            ->orderBy(DB::raw('ISNULL(`stockout_at`)'), 'desc')
            ->orderByRaw('CHAR_LENGTH(`name`)')
            ->orderByRaw('CASE WHEN `gender` = "男裝" THEN 0 WHEN `gender` = "女裝" THEN 1 ELSE 2 END')
            ->orderBy('min_price')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getLimitedOfferHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_LIMITED_OFFER)) {
            $this->setLimitedOfferHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_LIMITED_OFFER);
    }

    public function getSaleHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_SALE)) {
            $this->setSaleHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_SALE);
    }

    public function getMostReviewedHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_MOST_REVIEWED)) {
            $this->setMostReviewedHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_MOST_REVIEWED);
    }

    public function getNewHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_NEW)) {
            $this->setNewHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_NEW);
    }

    public function getComingSoonHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_COMING_SOON)) {
            $this->setComingSoonHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_COMING_SOON);
    }

    public function getMultiBuyHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_MULTI_BUY)) {
            $this->setMultiBuyHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_MULTI_BUY);
    }

    public function getOnlineSpecialHmallProducts()
    {
        if (! Cache::has(self::CACHE_KEY_ONLINE_SPECIAL)) {
            $this->setOnlineSpecialHmallProductsCache();
        }

        return Cache::get(self::CACHE_KEY_ONLINE_SPECIAL);
    }

    public function setLimitedOfferHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('time_limited_begin', '<=', now())
            ->where('time_limited_end', '>=', now())
            ->where('stock', 'Y')
            ->orderBy('time_limited_end')
            ->orderByRaw('min_price/highest_record_price')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_LIMITED_OFFER, $hmallProducts);
    }

    public function setSaleHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('identity', 'like', '%concessional_rate%')
            ->where('stock', 'Y')
            ->orderByRaw('min_price/highest_record_price')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_SALE, $hmallProducts);
    }

    public function setMostReviewedHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('evaluation_count', '>=', function ($query) {
                $query->selectRaw('MAX(evaluation_count) as max_evaluation_count')
                    ->from('hmall_products')
                    ->where('stock', 'Y')
                    ->whereIn('gender', ['男裝', '女裝', '童裝', '新生兒/嬰幼兒'])
                    ->groupBy('gender')
                    ->orderBy('max_evaluation_count')
                    ->limit(1);
            })
            ->where('stock', 'Y')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_MOST_REVIEWED, $hmallProducts);
    }

    public function setNewHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('identity', 'like', '%new_product%')
            ->where('stock', 'Y')
            ->orderByRaw('min_price/highest_record_price')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_NEW, $hmallProducts);
    }

    public function setComingSoonHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('identity', 'like', '%COMING SOON%')
            ->where('stock', 'Y')
            ->orderByRaw('min_price/highest_record_price')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_COMING_SOON, $hmallProducts);
    }

    public function setMultiBuyHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('identity', 'like', '%SET%')
            ->where('stock', 'Y')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_MULTI_BUY, $hmallProducts);
    }

    public function setOnlineSpecialHmallProductsCache()
    {
        $hmallProducts = $this->model
            ->select(self::SELECT_COLUMNS_FOR_LIST)
            ->where('identity', 'like', '%ONLINE SPECIAL%')
            ->where('stock', 'Y')
            ->orderByRaw('min_price/highest_record_price')
            ->orderBy('evaluation_count', 'desc')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        Cache::forever(self::CACHE_KEY_ONLINE_SPECIAL, $hmallProducts);
    }

    public function saveProductsFromUniqloHmall($products)
    {
        collect($products)->each(function ($product) {
            try {
                /** @var HmallProduct $model */
                $model = $this->model->firstOrNew(['product_code' => $product->productCode]);

                $model->code = $product->code;
                $model->product_code = $product->productCode;
                $model->oms_product_code = $product->omsProductCode;
                $model->name = $product->name;
                $model->product_name = $product->productName;
                $model->prices = json_encode($product->prices);
                $model->min_price = $product->minPrice;
                $model->max_price = $product->maxPrice;
                $model->lowest_record_price = $this->getLowestRecordPrice($model, $product->minPrice);
                $model->highest_record_price = $this->getHighestRecordPrice($model, $product->maxPrice);
                $model->origin_price = $product->originPrice;
                $model->price_color = $product->priceColor;
                $model->identity = json_encode($product->identity);
                $model->label = $product->label;
                $model->time_limited_begin = $this->getCarbonOrNull($product->timeLimitedBegin);
                $model->time_limited_end = $this->getCarbonOrNull($product->timeLimitedEnd);
                $model->score = $product->score;
                $model->size_score = $product->sizeScore;
                $model->evaluation_count = $product->evaluationCount;
                $model->sales = $product->sales;
                $model->new = $product->new;
                $model->season = $product->season;
                $model->style_text = json_encode($product->styleText);
                $model->color_nums = json_encode($product->colorNums);
                $model->color_pic = json_encode($product->colorPic);
                $model->chip_pic = json_encode($product->chipPic);
                $model->main_first_pic = $product->mainPic;
                $model->size = json_encode($product->size);
                $model->min_size = $product->minSize;
                $model->max_size = $product->maxSize;
                $model->gender = $product->gender;
                $model->sex = $product->sex;
                $model->material = $product->material;

                $model->stockout_at = $this->getStockoutAt($model, $product);
                $model->stock = $product->stock;

                $model->save();

                if ($this->hasNotChangeThePrice($model)) {
                    return;
                }

                $hmallPriceHistory = new HmallPriceHistory();
                $hmallPriceHistory->min_price = $model->min_price;
                $hmallPriceHistory->max_price = $model->max_price;
                $model->hmallPriceHistories()->save($hmallPriceHistory);
            } catch (Throwable $e) {
                report($e);
            }
        });
    }

    public function setStockoutHmallProducts($updatedIsBefore = null)
    {
        if (is_null($updatedIsBefore)) {
            $updatedIsBefore = today();
        }

        $this->model
            ->whereNull('stockout_at')
            ->where('updated_at', '<', $updatedIsBefore)
            ->update([
                'stockout_at' => now(),
                'updated_at' => DB::raw('updated_at'),
            ]);
    }

    public function updateProductDescriptionsFromUniqloSpu(HmallProduct $hmallProduct, $instruction, $sizeChart, bool $updateTimestamps = false)
    {
        $hmallProduct->instruction = $instruction;
        $hmallProduct->size_chart = $sizeChart;

        $hmallProduct->timestamps = $updateTimestamps;
        $hmallProduct->save();
    }

    public function getAllProductsForSitemap()
    {
        return $this->model
            ->select(['id', 'product_code', 'updated_at'])
            ->orderBy('id', 'desc')
            ->get();
    }

    private function getLowestRecordPrice($model, $minPrice)
    {
        $lowestRecordPrice = $model->lowest_record_price;

        if (empty($lowestRecordPrice)) {
            return $minPrice;
        }

        return min($lowestRecordPrice, $minPrice);
    }

    private function getHighestRecordPrice($model, $maxPrice)
    {
        $highestRecordPrice = $model->highest_record_price;

        if (empty($highestRecordPrice)) {
            return $maxPrice;
        }

        return max($highestRecordPrice, $maxPrice);
    }

    private function getCarbonOrNull($unixTimestampInMilliseconds)
    {
        if (empty($unixTimestampInMilliseconds)) {
            return null;
        }

        return Carbon::createFromTimestampMs($unixTimestampInMilliseconds);
    }

    private function getStockoutAt($model, $product)
    {
        // 有庫存，移除售罄時間
        if ($product->stock === 'Y') {
            return null;
        }

        // 這次才無庫存
        if ($product->stock === 'N' && empty($model->stockout_at)) {
            return now();
        }

        // 先前就無庫存
        return $model->stockout_at;
    }

    private function hasNotChangeThePrice($model)
    {
        $latestHmallPriceHistory = $model->hmallPriceHistories()->latest()->first();

        if (is_null($latestHmallPriceHistory)) {
            return false;
        }

        return $model->min_price == $latestHmallPriceHistory->min_price &&
            $model->max_price == $latestHmallPriceHistory->max_price;
    }
}
