@inject('productPresenter', 'App\Presenters\ProductPresenter')
@extends('layouts.master')

@section('title', "{$productPresenter->countProducts($sales)} 件商品特價中")

@section('metadata')
{{--  <meta property="og:image" content="{{ $productPresenter->getProductMainImageUrl($productInfo) }}" />  --}}
@endsection

@section('content')
    <div class="ts fluid slate">
        <i class="certificate faded icon"></i>
        <span class="header">{{ $productPresenter->countProducts($sales) }} 件商品特價中</span>
    </div>

    <br>

    <div class="ts narrow container grid">
        @include('products.cards', ['products' => $sales])
    </div>
@endsection