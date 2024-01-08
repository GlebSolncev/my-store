<div class="header-bottom  header-sticky">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-3 col-lg-4 col-md-6 vertical-menu d-none d-lg-block">
                <span class="categorie-title">Категории</span>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-12">
                @include('layouts.parts.header.bottom.vertical')
            </div>
        </div>
        <!-- Row End -->
    </div>
    <!-- Container End -->
</div>
<!-- Header Bottom End Here -->

<!-- Mobile Vertical Menu Start Here -->
<div class="container d-block d-lg-none">
    <div class="vertical-menu mt-30">
        <span class="categorie-title mobile-categorei-menu">Категории</span>
        <nav>
            <div id="cate-mobile-toggle" class="category-menu sidebar-menu sidbar-style mobile-categorei-menu-list menu-hidden ">
                <ul>
                    @foreach(App\Helper::getCategories() as $category)
                        <li class="has-sub"><a href="{{ route('category.index', ['category' => $category['slug']]) }}">{{ $category['title'] }}</a>
                            @if($category['child'])
                                <ul class="category-sub">
                                    @foreach($category['child'] as $child)
                                        @if($child['child'])
                                            <li class="has-sub"><a href="{{ route('category.index', ['category' => $child['slug']]) }}">{{ $child['title'] }}</a>
                                                <ul class="category-sub">
                                                    @foreach($child['child'] as $c)
                                                        <li><a href="{{ route('category.index', ['category' => $c['slug']]) }}">{{ $c['title'] }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li><a href="{{ route('category.index', ['category' => $child['slug']]) }}">{{ $child['title'] }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                            <!-- category submenu end-->
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- category-menu-end -->
        </nav>
    </div>
</div>
<!-- Mobile Vertical Menu Start End -->