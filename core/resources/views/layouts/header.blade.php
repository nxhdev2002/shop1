<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('images/icons/favicon.png') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/linearicons-v1.0.0/icon-font.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/select2/select2.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/slick/slick.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/MagnificPopup/magnific-popup.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
    <script>
        function deleteCart(product_id) {
            $(`#status-${product_id}`).removeClass("hidden")
            $.ajax({
                url: '{{route("user.cart.remove")}}',
                data: {
                    'product_id': product_id
                },
                type: 'DELETE',
                success: function (res) {
                    toastr.success(res.message)
                    loadCart()
                }
            })
        }

        async function remove(id) {
            deleteCart(id)
            $('#product-' + id).removeClass('opacity-100').addClass('opacity-0')
            await new Promise(r => setTimeout(r, 300));
            $('#product-' + id).addClass('hidden')
            window.top.location = window.top.location
        }
    </script>
    <!--===============================================================================================-->
</head>

<body class="animsition">

    <!-- Header -->
    <header class="header-v4">
        <!-- Header desktop -->
        <div class="container-menu-desktop">
            <!-- Topbar -->
            <div class="top-bar">
                <div class="container h-full content-topbar flex-sb-m">
                    <div class="left-top-bar">
                        Bài tập nhóm 5
                    </div>

                    <div class="h-full right-top-bar flex-w">
                        <a href="#" class="flex-c-m trans-04 p-lr-25">
                            Help & FAQs
                        </a>

                        @if (isset(auth()->user()->name))
                        <a href="/seller/dashboard" class="flex-c-m trans-04 p-lr-25">
                            {{ auth()->user()->name }}
                        </a>
                        <a href="/user/deposit" class="flex-c-m trans-04 p-lr-25">
                            Balance: {{ number_format(auth()->user()->balance) }}
                        </a>

                        <a href="/user/orders" class="flex-c-m trans-04 p-lr-25">
                            Order History
                        </a>

                        @if (auth()->user()->rights >= 5)

                        <a href="/admin" class="flex-c-m trans-04 p-lr-25">
                            Admin Panel
                        </a>

                        @endif
                        <form action="/logout" method="POST" class="flex-c-m trans-04 p-lr-25">
                            @csrf
                            <button type="submit">
                                <p class="flex-c-m trans-04 p-lr-25" style="color: #b2b2b2">Logout</p>
                            </button>
                        </form>
                        @else
                        <a href="/login" class="flex-c-m trans-04 p-lr-25">
                            Login
                        </a>
                        @endif



                    </div>
                </div>
            </div>

            <div class="wrap-menu-desktop">
                <nav class="container limiter-menu-desktop">

                    <!-- Logo desktop -->
                    <a href="#" class="logo" style="font-size:25px; color: #000">
                        HUBT STORE
                    </a>

                    <!-- Menu desktop -->
                    <div class="menu-desktop">
                        <ul class="main-menu">
                            <li class="">
                                <a href="/">Home</a>

                            </li>

                            <li>
                                <a href="/products">Shop</a>
                            </li>

                            <li class="label1" data-label1="hot">
                                <a href="/products">Features</a>
                            </li>

                            <li>
                                <a href="#">Blog</a>
                            </li>

                            <li>
                                <a href="#">About</a>
                            </li>

                            <li>
                                <a href="#">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Icon header -->
                    <div class="wrap-icon-header flex-w flex-r-m">
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                            <i class="zmdi zmdi-search"></i>
                        </div>

                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                            data-notify="{{isset($carts) ? count($carts) : 0}}">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>

                        <a href="#"
                            class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti"
                            data-notify="0">
                            <i class="zmdi zmdi-favorite-outline"></i>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Header Mobile -->
        <div class="wrap-header-mobile">
            <!-- Logo moblie -->
            <div class="logo-mobile">
                <a href="index.html"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
            </div>

            <!-- Icon header -->
            <div class="wrap-icon-header flex-w flex-r-m m-r-15">
                <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                    <i class="zmdi zmdi-search"></i>
                </div>

                <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
                    data-notify="{{isset($carts) ? count($carts) : 0}}">
                    <i class="zmdi zmdi-shopping-cart"></i>
                </div>

                <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti"
                    data-notify="0">
                    <i class="zmdi zmdi-favorite-outline"></i>
                </a>
            </div>

            <!-- Button show menu -->
            <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </div>
        </div>


        <!-- Menu Mobile -->
        <div class="menu-mobile">
            <ul class="topbar-mobile">
                <li>
                    <div class="left-top-bar">
                        Free shipping for standard order over $100
                    </div>
                </li>

                <li>
                    <div class="h-full right-top-bar flex-w">
                        <a href="#" class="flex-c-m p-lr-10 trans-04">
                            Help & FAQs
                        </a>

                        <a href="#" class="flex-c-m p-lr-10 trans-04">
                            My Account
                        </a>

                        <a href="#" class="flex-c-m p-lr-10 trans-04">
                            EN
                        </a>

                        <a href="#" class="flex-c-m p-lr-10 trans-04">
                            USD
                        </a>
                    </div>
                </li>
            </ul>

            <ul class="main-menu-m">
                <li>
                    <a href="/">Home</a>
                </li>

                <li>
                    <a href="product.html">Shop</a>
                </li>

                <li>
                    <a href="shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>
                </li>

                <li>
                    <a href="blog.html">Blog</a>
                </li>

                <li>
                    <a href="about.html">About</a>
                </li>

                <li>
                    <a href="contact.html">Contact</a>
                </li>
            </ul>
        </div>

        <!-- Modal Search -->
        <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
            <div class="container-search-header">
                <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                    <img src="images/icons/icon-close2.png" alt="CLOSE">
                </button>

                <form class="wrap-search-header flex-w p-l-15">
                    <button class="flex-c-m trans-04">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                    <input class="plh3" type="text" name="search" placeholder="Search...">
                </form>
            </div>
        </div>
    </header>

    <!-- Cart -->
    <div class="wrap-header-cart js-panel-cart">
        <div class="s-full js-hide-cart"></div>

        <div class="header-cart flex-col-l p-l-65 p-r-25">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span class="mtext-103 cl2">
                    Your Cart
                </span>

                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            @if (isset($carts) && isset($total))
            <div class="header-cart-content flex-w js-pscroll">
                <ul class="w-full header-cart-wrapitem">

                    @foreach ($carts as $cart)
                    <li class="header-cart-item flex-w flex-t m-b-12 product-{{$cart->product->id}}">
                        <div class="header-cart-item-img" onclick="remove('{{$cart->product->id}}')">
                            <img src="{{$cart->product->picture_url}}" alt="IMG">
                        </div>

                        <div class="header-cart-item-txt p-t-8">
                            <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                {{$cart->product->name}}
                            </a>

                            <span class="header-cart-item-info">
                                {{$cart->quantity}} x {{number_format($cart->product->price)}} VND
                            </span>
                        </div>
                    </li>

                    @endforeach
                </ul>

                <div class="w-full">
                    <div class="w-full header-cart-total p-tb-40">
                        Total: {{ number_format($total) }} VND
                    </div>

                    <div class="w-full header-cart-buttons flex-w">
                        <a href="/user/cart"
                            class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                            View Cart
                        </a>

                        <a href="/user/cart"
                            class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                            Check Out
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
