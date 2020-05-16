@inject('productPresenter', 'App\Presenters\ProductPresenter')
@extends('layouts.master')

@php
    $shareText = $product->name . " | UNIQLO 比價 | UQ 搜尋";
    $shareTextEncode = urlencode($shareText);

    $url = url()->current();
    $shareUrl = [
        'facebook' => urlencode($url . "?utm_source=uqs&utm_medium=fb&utm_campaign=share"),
        'twitter' => urlencode($url . "?utm_source=uqs&utm_medium=twtr&utm_campaign=share"),
        'line' => urlencode($url . "?utm_source=uqs&utm_medium=line&utm_campaign=share"),
        'webShare' => $url . "?utm_source=uqs&utm_medium=webshare&utm_campaign=share"
    ];
@endphp

@section('title', "{$product->name}")

@section('json-ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "sku": "{{ $product->id }}",
  "name": "{{ $product->name }} | UNIQLO 比價 | UQ 搜尋",
  "description": {!! json_encode($product->comment) !!},
  "image": [
    "{{ $product->main_image_url }}"
  ],
  "itemCondition": "http://schema.org/NewCondition",
  "brand": {
    "@type": "Thing",
    "name": "UNIQLO"
  },
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "{{ $product->min_price }}",
    "highPrice": "{{ $product->max_price }}",
    "priceCurrency": "TWD",
    "priceValidUntil": "{{ $product->updated_at->toDateString() }}",
    "availability": "{{ $productPresenter->getProductAvailabilityForJsonLd($product) }}",
    "itemCondition": "http://schema.org/NewCondition",
    "url": "{{ route('products.show', ['product' => $product->id]) }}",
    "seller": {
      "@type": "Organization",
      "name": "UNIQLO"
    }
  }
  @if ($product->review_count > 0 && ! empty($product->review_rating))
  ,"aggregateRating": {
      "ratingValue": "{{ $product->review_rating }}",
      "ratingCount": "{{ $product->review_count }}"
  }
  @endif
}
</script>
@endsection

@section('metadata')
<link rel="canonical" href="{{ route('products.show', ['product' => $product->id]) }}" />
<meta name="description" content="{{ $product->comment }} | UNIQLO 比價 | UQ 搜尋" />
<meta property="og:type" content="og:product" />
<meta property="og:title" content="{{ $product->name }} | UQ 搜尋" />
<meta property="og:url" content="{{ route('products.show', ['product' => $product->id]) }}" />
<meta property="og:description" content="{{ $product->comment }} | UNIQLO 比價 | UQ 搜尋" />
<meta property="og:image" content="{{ $product->main_image_url }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:creator" content="@littlegoodjack" />
<meta name="twitter:title" content="{{ $product->name }} | UQ 搜尋" />
<meta name="twitter:description" content="{{ $product->comment }} | UNIQLO 比價 | UQ 搜尋" />
<meta name="twitter:image" content="{{ $product->main_image_url }}" />
<meta name="share:text" content="{{ $shareText }}" />
<meta name="share:url" content="{{ $shareUrl['webShare'] }}" />
@endsection

@section('css')
<style>
    .ts.card .overlapped.content.color-header {
        top: unset;
        height: unset;
        bottom: 0;
    }

    #facebook {
        color: #145cbd;
    }

    #facebook:hover {
        color: #ffffff;
        border-color: #ffffff;
        background-color: #1877f2;
    }

    #facebook:active {
        color: #b6bcc7;
        border-color: #ffffff;
        background-color: #145cbd;
    }

    #twitter {
        color: #0d7bbf;
    }

    #twitter:hover {
        color: #ffffff;
        border-color: #ffffff;
        background-color: #1d95e0;
    }

    #twitter:active {
        color: #b6bcc7;
        border-color: #ffffff;
        background-color: #0d7bbf;
    }

    #line {
        color: #05a52f;
    }

    #line:hover {
        color: #ffffff;
        border-color: #ffffff;
        background-color: #06b833;
    }

    #line:active {
        color: #b6bcc7;
        border-color: #ffffff;
        background-color: #05a52f;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/css/lightbox.min.css"
    integrity="sha256-tBxlolRHP9uMsEFKVk+hk//ekOlXOixLKvye5W2WR5c=" crossorigin="anonymous" />
@endsection

@section('content')
<div class="ts very padded horizontally fitted attached fluid segment">
    <div class="ts container relaxed grid">
        <div class="nine wide computer nine wide tablet sixteen wide mobile column">
            <div class="ts fluid container">
                <a class="ts centered image" href="{{ $product->main_image_url }}" rel="nofollow noopener" data-lightbox="image" data-title="{{ $product->name }}">
                    <img class="ts centered image lazyload" data-src="{{ $product->main_image_url }}" alt="{{ $product->name }}" loading="lazy">
                </a>
            </div>
        </div>
        <div class="seven wide computer seven wide tablet sixteen wide mobile column">
            <div class="ts fluid very narrow container grid">
                <div class="sixteen wide column">
                    <h1 class="ts dividing big header">
                        {{ $product->name }}
                        <div class="sub header">
                            商品編號 {{ $product->id }}
                            {!! $productPresenter->getRatingForProductShow($product) !!}
                        </div>
                    </h1>
                </div>
                <div class="sixteen wide center aligned column">
                    <div class="ts very narrow container">
                        <div class="ts basic fitted segment">
                            {!! $productPresenter->getProductTag($product) !!}
                        </div>
                    </div>
                </div>
                <div class="eight wide column">
                    <div class="ts tiny divided horizontal two statistics">
                        <div class="statistic" style="width: 100%; justify-content: center;">
                            <div class="value">{{ $product->max_price }}</div>
                            <div class="label">歷史高價 <i class="fitted caret up icon"></i></div>
                        </div>
                        <div class="statistic" style="width: 100%; justify-content: center;">
                            <div class="value">{{ $product->min_price }}</div>
                            <div class="label">歷史低價 <i class="fitted caret down icon"></i></div>
                        </div>
                    </div>
                </div>
                <div class="eight wide column">
                    <div class="ts borderless card">
                        <div class="center aligned content">
                            <div class="ts small statistic">
                                <div class="value">{{ $product->price }}</div>
                                <div class="label">現在售價</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ts flatted card">
                <div class="image">
                    <canvas id="priceChart" width="457" height="263"></canvas>
                </div>
            </div>
            <div class="ts flatted vertically fitted segment">
                <details class="ts accordion">
                    <summary>
                        <i class="dropdown icon"></i>商品介紹
                    </summary>
                    <div class="content">
                        <p>{!! $product->comment !!}</p>
                    </div>
                </details>
            </div>
            <div class="ts grid">
                <div id="uniqlo-column" class="sixteen wide column">
                    <a class="ts inverted fluid button" href="https://www.uniqlo.com/tw/store/goods/{{ $product->id }}" target="_blank" rel="nofollow noopener" aria-label="UNIQLO">前往 UNIQLO 官網</a>
                </div>
                <div id="share-column-1" class="five wide column" style="display: none;">
                    <a class="ts basic fluid button" id="share" target="_blank" rel="nofollow noopener" aria-label="Share"><i class="share icon"></i>分享</a>
                </div>
                <div id="share-column-2" class="sixteen wide column">
                    <div class="ts fluid separated stackable buttons">
                        <a id="facebook" class="ts mini basic fluid button"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl['facebook'] }}&quote={{ $shareTextEncode }}"
                            target="_blank" rel="nofollow noopener" aria-label="Facebook">
                            <i class="facebook icon"></i>Facebook 分享
                        </a>
                        <a id="twitter" class="ts mini basic fluid button"
                            href="https://twitter.com/intent/tweet/?text={{ $shareTextEncode }}&url={{ $shareUrl['twitter'] }}"
                            target="_blank" rel="nofollow noopener" aria-label="Twitter">
                            <i class="twitter icon"></i>Twitter 分享
                        </a>
                        <a id="line" class="ts mini basic fluid button"
                            href="https://social-plugins.line.me/lineit/share?text={{ $shareTextEncode }}&url={{ $shareUrl['line'] }}"
                            target="_blank" rel="nofollow noopener" aria-label="Line">
                            <i class="chat icon"></i>LINE 分享
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (count($suggestProducts) > 0)
<div class="ts very padded horizontally fitted attached fluid tertiary segment">
    <div class="ts container">
        <h2 class="ts large dividing header">你可能也喜歡</h2>
        <div class="ts segmented selection items">
            @each('products.item', $suggestProducts, 'product')
        </div>
    </div>
</div>
@endif

@if (count($relatedProducts) > 0)
<div class="ts very padded horizontally fitted attached fluid tertiary segment">
    <div class="ts container">
        <h2 class="ts large dividing header">延伸商品</h2>
        <br>
        <div class="ts doubling link cards six">
            @each('products.card', $relatedProducts, 'product')
        </div>
    </div>
</div>
@endif

@if (count($styles) > 0 || count($styleDictionaries) > 0)
<div class="ts very padded horizontally fitted attached fluid tertiary segment">
    <div class="ts container">
        <h2 class="ts large dividing header">精選穿搭</h2>
        <br>
        <div class="ts doubling four flatted cards">
            {!! $productPresenter->getStyles($styles) !!}
            {!! $productPresenter->getStyleDictionaries($styleDictionaries) !!}
        </div>
    </div>
</div>
@endif

@if (! empty($product->colors) || ! empty($product->sub_images))
<div class="ts very padded horizontally fitted attached fluid tertiary segment">
    <div class="ts container">
        <h2 class="ts large dividing header">商品實照</h2>
        <br>
        <div class="ts doubling four flatted cards">
            {!! $productPresenter->getSubImages($product) !!}
            {!! $productPresenter->getItemImages($product) !!}
        </div>
    </div>
</div>
@endif
@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/js/lightbox-plus-jquery.min.js" integrity="sha256-j4lH4GKeyuTMQAFtmqhxfZbGxx+3WS6n2EJ/NTB21II=" crossorigin="anonymous"></script>

<script>
    'use strict';

    let ctx = document.getElementById("priceChart");
    let pointBackgroundColor = [];
    let pointRadius = [];
    let priceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $productPresenter->getPriceChartLabels($productHistories) !!},
            datasets: [{
                label: '價格',
                data: {!! $productPresenter->getPriceChartData($productHistories) !!},
                radius: 1.5,
                backgroundColor: 'rgba(206, 94, 87, 0.2)',
                borderColor: 'rgba(206, 94, 87, 1.0)',
                borderWidth: 1,
                cubicInterpolationMode: 'monotone',
                pointBackgroundColor: pointBackgroundColor,
                pointRadius: pointRadius
            }],
            multiBuyData: {!! $productPresenter->getPriceChartMultiBuyData($productHistories) !!}
        },
        options: {
            title: {
                display: true,
                lineHeight: 1,
                text: '歷史價格折線圖'
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                xPadding: 11,
                yPadding: 8,
                titleMarginBottom: 10,
                titleFontSize: 14,
                bodyFontSize: 15,
                footerFontColor: 'rgba(218, 133, 128, 1.0)',
                displayColors: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + "：NT$" + tooltipItem.yLabel;
                    },
                    footer: function(tooltipItems, data) {
                        if (data.multiBuyData[tooltipItems[0].index] !== null) {
                           return data.multiBuyData[tooltipItems[0].index];
                        }
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    for (let i = 0; i < priceChart.data.datasets[0].data.length; i++) {
        if (priceChart.data.multiBuyData[i] === null) {
            pointBackgroundColor.push('rgba(206, 94, 87, 0.2)');
            pointRadius.push(1.5);
        } else {
            pointBackgroundColor.push('rgba(206, 94, 87, 0.9)');
            pointRadius.push(4);
        }
    }

    priceChart.update();

    lightbox.option({
        'alwaysShowNavOnTouchDevices': true,
        'albumLabel': '相片 %1 / %2',
        'disableScrolling': true,
        'fadeDuration': 150,
        'resizeDuration': 150,
        'imageFadeDuration': 0,
    });

    async function webShare() {
        if (navigator.share === undefined) {
            return;
        }

        const title = document.title;
        const text = document.querySelector('meta[name="share:text"]').getAttribute('content');
        const url = document.querySelector('meta[name="share:url"]').getAttribute('content');

        try {
            await navigator.share({title, text, url});
        } catch (error) {}
    }

    function onLoad() {
        if (navigator.share !== undefined) {
            document.getElementById('share-column-1').style.display = 'block';
            document.getElementById('share-column-2').style.display = 'none';

            let uniqloColumn = document.getElementById('uniqlo-column');
            uniqloColumn.className = uniqloColumn.className.replace('sixteen', 'eleven');
        }

        document.querySelector('#share').addEventListener('click', webShare);
    }

    window.addEventListener('load', onLoad);
</script>
@endsection
