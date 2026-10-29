<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel</title>
    <link rel="stylesheet" href="{{ asset('css/own-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog-home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div id="site-preloader">
        <div class="loader-content">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <p>Preparing your journey...</p>
        </div>
    </div>
    <div class="wrapper">
        <header class="header banner-area">
            <nav class="nav-bar" id="nav-bar">
                <div class="nav-bar-top">
                    <div class="container">
                        <div class="nav-bar-top-scrolling">
                            <div class="nav-bar-top-left">
                                <ul>
                                    <li><a href="#">Visit Us</a></li>
                                    <li><a href="#">Buy Tickets</a></li>
                                </ul>
                            </div>
                            <div class="nav-bar-top-right">
                                <div class="nav-bar-social">
                                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                    <a href="#"><i class="fa-brands fa-dribbble"></i></a>
                                    <a href="#"><i class="fa-brands fa-behance"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container nav-bar-main-menu">
                    <button type="button" id="mobile-nav-toggle"><i class="lnr lnr-menu"></i></button>
                    <div class="nav-bar-main-menu-full">
                        <div id="logo">
                            <a href="{{ url('/index') }}"><img src="{{ asset('images/logo.png') }}" alt="" title=""></a>
                        </div>
                        <nav id="nav-menu-container">
                            <ul class="nav-menu sf-js-enabled sf-arrows" style="touch-action: pan-y;">
                                <li><a href="{{ url('/index') }}" class="menu-active">Home</a></li>
                                <li><a href="{{ url('/about') }}">About</a></li>
                                <li><a href="{{ url('/packages') }}">Packages</a></li>
                                <li><a href="{{ url('/hotels') }}">Hotels</a></li>
                                <li><a href="{{ url('/insurance') }}">Insurance</a></li>
                                <li class="menu-has-children"><a href="{{ url('/profile') }}"
                                        class="sf-with-ul">Profile</a>
                                    <ul>
                                        <li><a href="{{ url('/profile') }}">Profile</a></li>
                                        <li><a href="{{ url('/auth_page') }}">Auth Page</a></li>
                                    </ul>
                                </li>
                                <li class="menu-has-children"><a href="{{ url('/blog-home') }}"
                                        class="sf-with-ul">Blog</a>
                                    <ul>
                                        <li><a href="{{ url('/blog-home') }}">Blog Home</a></li>
                                        <li><a href="{{ url('/add-blog') }}">Add Blog</a></li>
                                    </ul>
                                </li>
                                <li class="menu-has-children"><a href="{{ url('/elements') }}"
                                        class="sf-with-ul">Pages</a>
                                    <ul>
                                        <li><a href="{{ url('/elements') }}">Elements</a></li>
                                        <li class="menu-has-children"><a href="" class="sf-with-ul">Level 2 </a>
                                            <ul>
                                                <li><a href="#">Item One</a></li>
                                                <li><a href="#">Item Two</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('/contact') }}">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </nav>
            <div class="overlay overlay-bg"></div>
            <div class="hero-section" style="height: 730px;">
                <div class="container">
                    <div class="hero-section-main">
                        <div class="hero-section-main-blog-head">
                            <h1>
                                Dude You’re Getting
                                a Telescope
                            </h1>
                            <p>
                                There is a moment in the life of any aspiring astronomer that it is time to buy that
                                first
                            </p>
                            <a href="#" class="primary-btn">View More</a>
                        </div>
                    </div>
                </div>

            </div>

        </header>
        <main class="main">
            <!-- Start Top Category Widget Section -->
            <section class="top-category-widget-section section-gap">
                <div class="container">
                    <div class="top-category-widget-section-cards">
                        <div class="top-category-widget-section-card">
                            <div class="single-cat-widget">
                                <div class="content">
                                    <div class="overlay overlay-bg"></div>
                                    <a href="#" target="_blank">
                                        <div class="thumb">
                                            <img class="content-image"
                                                src="{{ asset('images/blog-home-img/cat-widget1.jpg') }}" alt="">
                                        </div>
                                        <div class="content-details">
                                            <h4 class="content-title">Social life</h4>
                                            <span></span>
                                            <p>Enjoy your social life together</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="top-category-widget-section-card">
                            <div class="single-cat-widget">
                                <div class="content">
                                    <div class="overlay overlay-bg"></div>
                                    <a href="#" target="_blank">
                                        <div class="thumb">
                                            <img class="content-image"
                                                src="{{ asset('images/blog-home-img/cat-widget2.jpg') }}" alt="">
                                        </div>
                                        <div class="content-details">
                                            <h4 class="content-title">Politics</h4>
                                            <span></span>
                                            <p>Be a part of politics</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="top-category-widget-section-card">
                            <div class="single-cat-widget">
                                <div class="content">
                                    <div class="overlay overlay-bg"></div>
                                    <a href="#" target="_blank">
                                        <div class="thumb">
                                            <img class="content-image"
                                                src="{{ asset('images/blog-home-img/cat-widget3.jpg') }}" alt="">
                                        </div>
                                        <div class="content-details">
                                            <h4 class="content-title">Food</h4>
                                            <span></span>
                                            <p>Let the food be finished</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Top Category Widget Section -->

            <!-- Start Post Content Section -->
            <section class="post-content-section">
                <div class="container">
                    <div class="post-content-section-main">
                        <div class="post-content-section-cards-list">
                            @forelse ($posts as $post)
                            <div class="post-content-single-post">
                                <div class="post-content-single-post-side-bar">
                                    <ul class="tags">
                                        @foreach ($post->tags as $tag)
                                        <li><a href="{{ route('blog.index', ['tag' => $tag->id]) }}">{{ $tag->name }}@if(!$loop->last),@endif</a></li>
                                        @endforeach
                                    </ul>
                                    <div class="user-details">
                                        <p class="user-name">
                                            <a href="#">@if($post->author) {{ $post->author->name }} @else Admin @endif</a>
                                            <span class="lnr lnr-user"></span>
                                        </p>
                                        <p class="date">
                                            <a href="#">{{ optional($post->pub_date)->format('d M, Y') ?? "" }}</a>
                                            <span class="lnr lnr-calendar-full"></span>
                                        </p>
                                        <p class="view">
                                            <a href="#">{{ $post->view_count }} Views</a>
                                            <span class="lnr lnr-eye"></span>
                                        </p>

                                        <p class="comments">
                                            <a href="{{ url('/blog-single/' . $post->id) }}">
                                                {{ $post->comments_count }} Comments
                                            </a>
                                            <span class="lnr lnr-bubble"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="post-content-single-post-card">
                                    <div class="feature-img">
                                        <img class="img-fluid" src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                    </div>
                                    <a href="{{ url('/blog-single/' . $post->id) }}" class="posts-title">
                                        <h3>{{ $post->title }}</h3>
                                    </a>
                                    <p class="excert">
                                        {{ str($post->content)->words(30) }}
                                    </p>
                                    <a href="{{ url('/blog-single/' . $post->id) }}" class="primary-btn">View More</a>
                                </div>
                            </div>
                            @empty
                            <h1 class="not-found">No posts found.</h1>
                            @endforelse

                            @if($posts->hasPages())
                            <nav class="post-content-footer-bar">
                                <ul class="pagination">
                                    @if(!$posts->onFirstPage())
                                    <li class="page-item">
                                        <a href="{{ $posts->previousPageUrl() }}" class="page-link"
                                            aria-label="Previous">
                                            <span aria-hidden="true"><span class="lnr lnr-chevron-left"></span></span>
                                        </a>
                                    </li>
                                    @endif

                                    @foreach (range(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $num)
                                    @if($posts->currentPage() == $num)
                                    <li class="page-item active"><a href="{{ $posts->url($num) }}" class="page-link">{{ $num }}</a></li>
                                    @else <li
                                        class="page-item"><a href="{{ $posts->url($num) }}" class="page-link">{{ $num }}</a></li>
                                    @endif
                                    @endforeach

                                    @if($posts->hasMorePages())
                                    <li class="page-item">
                                        <a href="{{ $posts->nextPageUrl() }}" class="page-link"
                                            aria-label="Next">
                                            <span aria-hidden="true"><span
                                                    class="lnr lnr-chevron-right"></span></span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </nav>
                            @endif
                        </div>
                        <div class="post-content-section-side-bar">
                            <div class="widget-wrap">
                                <div class="single-sidebar-widget search-widget">
                                    <form class="search-form" action="{{ url('/blog-home') }}" method="GET">
                                        <input name="q" value="{{ $search }}" placeholder="Search Posts" type="text">
                                        <button type="submit"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="single-sidebar-widget user-info-widget">

                                    @if($logged_in_user)
                                    <img src="{{ $logged_in_user->profile_image_url }}" alt="{{ $logged_in_user->name }}"
                                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">

                                    <a href="#">
                                        <h4>{{ $logged_in_user->first_name ?? $logged_in_user->name }}
                                            {{ $logged_in_user->last_name }}
                                        </h4>
                                    </a>

                                    <p>{{ $logged_in_user->writer ?? 'Writer' }}</p>

                                    <p>
                                        {{ $logged_in_user->about_me ?? 'Welcome to my blog profile!' }}
                                    </p>

                                    @else
                                    <img src="{{ asset('images/user-img/user-info.png') }}" alt="Guest">

                                    <a href="#">
                                        <h4>Guest User</h4>
                                    </a>

                                    <p>Visitor</p>

                                    <p>
                                        Please log in to update your profile and see your personal bio here.
                                    </p>

                                    <div class="br" style="margin: 10px 0;"></div>
                                    <a href="{{ url('/auth_page') }}" class="primary-btn text-uppercase"
                                        style="margin-bottom: 20px;">Login /
                                        Register</a>
                                    @endif

                                    <ul class="social-links">
                                        <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-github"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-behance"></i></a></li>
                                    </ul>
                                </div>
                                <div class="single-sidebar-widget popular-post-widget">
                                    <h4 class="popular-title">Popular Posts</h4>
                                    <div class="popular-post-list">
                                        @forelse ($popular_posts as $pp)
                                        <div class="single-post-list d-flex flex-row align-items-center">
                                            <div class="thumb">
                                                <img style="width: 100px; height: 60px; object-fit: cover;"
                                                    src="{{ $pp->image_url }}" alt="{{ $pp->title }}">
                                            </div>
                                            <div class="details">
                                                <a href="{{ url('/blog-single/' . $pp->id) }}">
                                                    <h6>{{ str($pp?->title)->limit(25) }}</h6>
                                                </a>
                                                <p>{{ $pp?->pub_date?->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @empty
                                        <p>No popular posts yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="single-sidebar-widget ads-widget">
                                    <a href="#"><img class="img-fluid"
                                            src="{{ asset('images/blog-home-img/ads-banner.jpg') }}" alt=""></a>
                                </div>
                                <div class="single-sidebar-widget post-category-widget">
                                    <h4 class="category-title">Post Categories</h4>
                                    <ul class="cat-list">
                                        @forelse ($categories as $category)
                                        <li>
                                            <a href="{{ route('blog.index', ['tag' => $category->id]) }}">
                                                <p>{{ $category->name }}</p>
                                                <p>{{ sprintf('%0d', $category->posts_count) }}</p>
                                            </a>
                                        </li>
                                        @empty
                                        <li>
                                            <p>No categories available.</p>
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="single-sidebar-widget newsletter-widget">
                                    <h4 class="newsletter-title">Newsletter</h4>
                                    <p>
                                        Here, I focus on a range of items and features that we use in life without
                                        giving them a second thought.
                                    </p>
                                    <div class="form-group">
                                        <div class="col-autos">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-envelope"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" id="inlineFormInputGroup"
                                                    placeholder="Enter email" onfocus="this.placeholder = ''"
                                                    onblur="this.placeholder = 'Enter email'">
                                            </div>
                                        </div>
                                        <a href="#" class="bbtns">Subcribe</a>
                                    </div>
                                    <p class="text-bottom">
                                        You can unsubscribe at any time
                                    </p>
                                </div>
                                <div class="single-sidebar-widget tag-cloud-widget">
                                    <h4 class="tagcloud-title">Tag Clouds</h4>
                                    <ul>
                                        @forelse ($tags as $tag)
                                        <li><a href="{{ route('blog.index', ['tag' => $tag->id]) }}">{{ $tag->name }}</a></li>
                                        @empty
                                        <li>No tags found</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Post Content Section -->
        </main>
        <footer class="footer section-gap">
            <div class="container">
                <div class="footer-top">
                    <div class="footer-top-sections">
                        <div class="footer-top-sections-widget">
                            <h6>About Agency</h6>
                            <p>
                                The world has become so fast paced that people don’t want to stand by reading a page of
                                information, they would much rather look at a presentation and understand the message.
                                It has come to a point
                            </p>
                        </div>
                    </div>
                    <div class="footer-top-sections">
                        <div class="footer-top-sections-widget">
                            <h6>Navigation Links</h6>
                            <div class="row">
                                <div class="col">
                                    <ul>
                                        <li><a href="{{ url('/index') }}">Home</a></li>
                                        <li><a href="#">Feature</a></li>
                                        <li><a href="#">Services</a></li>
                                        <li><a href="#">Portfolio</a></li>
                                    </ul>
                                </div>
                                <div class="col">
                                    <ul>
                                        <li><a href="#">Team</a></li>
                                        <li><a href="#">Pricing</a></li>
                                        <li><a href="{{ url('/blog-home') }}">Blog</a></li>
                                        <li><a href="{{ url('/contact') }}">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-top-sections">
                        <div class="single-footer-widget">
                            <h6>Newsletter</h6>
                            <p>
                                For business professionals caught between high OEM price and mediocre print and graphic
                                output.
                            </p>
                            <div>
                                <form method="post" action="{{ route('newsletter.store') }}" class="subscription" novalidate="true">
                                    @csrf
                                    <div class="footer-top-input">
                                        <input name="EMAIL" placeholder="Email Address" onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Email Address '" required="" type="email">

                                        <button class="footer-top-button"><i
                                                class="fa-regular fa-paper-plane"></i></button>
                                    </div>

                                    <!-- can put animate here -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="footer-top-sections">
                        <div class="single-footer-widget">
                            <h6 class="mb-20">InstaFeed</h6>
                            <ul class="footer-top-img">
                                <li><img src="{{ asset('images/footer-img/i1.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i2.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i3.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i4.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i5.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i6.jpg') }}" alt=""></li>
                                <li><img src="{{ asset('images/footer-img/i7.jpg') }}" alt="">
                                <li><img src="{{ asset('images/footer-img/i8.jpg') }}" alt=""></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="footer-bottom-txt">Copyright ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | This template
                        is made with <i class="fa-regular fa-heart"></i> by <a href="#">Colorlib</a>
                    </p>
                    <div class="footer-social">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-dribbble"></i></a>
                        <a href="#"><i class="fa-brands fa-behance"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/blog-home.js') }}"></script>
    <script>
        function lazyLoad() {
            const preloader = document.getElementById('site-preloader')

            if (!preloader) return

            setTimeout(() => {
                preloader.style.opacity = '0'
                setTimeout(() => {
                    preloader.style.display = 'none'
                }, 500)
            }, 200)
        }

        window.addEventListener('DOMContentLoaded', () => {
            navBarLinksChilds()
            navBarMovement()
            smoothUp()


            lazyLoad()
        })
    </script>
</body>

</html>
