@inject('productPresenter', 'App\Presenters\ProductPresenter')
@extends('layouts.master')

@section('title', $product->name)

@section('metadata')
<meta property="og:image" content="{{ $product->main_image_url }}" />
@endsection

@section('content')
<!-- 片段 -->
<div class="ts card">
    <div class="content">
        <div class="ts stackable grid">
            <div class="five wide column">
                <img class="ts fluid rounded link image" src="{{ $product->main_image_url }}">
            </div>
            <div class="eleven wide column">
                <h2 class="ts header">{{ $product->name }}</h2>
                {!! $productPresenter->getProductTag($product) !!}
                <p>{!! $product->comment !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="right aligned extra content">
                <div class="ts separated buttons">
                    <button class="ts mini link button"><h4>NT${{ $product->price }}</h4></button>
                    <a class="ts mini negative basic labeled icon button" href="http://www.uniqlo.com/tw/store/goods/{{ $product->id }}" target="_blank"><i class="external link icon"></i> 前往官網</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ts doubling four waterfall cards">
    <div class="ts flatted card">
        <div class="center aligned content">
            <div class="ts negative big statistic">
                <div class="value">{{ $highestPrice }}</div>
                <div class="label">歷史高價</div>
            </div>
        </div>
        <div class="symbol">
            <i class="arrow up icon"></i>
        </div>
    </div>
    <div class="ts card">
        <div class="center aligned content">
            <div class="ts positive big statistic">
                <div class="value">{{ $lowestPrice }}</div>
                <div class="label">歷史低價</div>
            </div>
        </div>
        <div class="symbol">
            <i class="arrow down icon"></i>
        </div>
    </div>
    {!! $productPresenter->getStyleDictionaryImages($styleDictionary) !!}
    {!! $productPresenter->getSubImages($product) !!}
</div>
@endsection