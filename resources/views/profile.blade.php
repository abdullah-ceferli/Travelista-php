<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/own-bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <div class="hero-section" style="height: 340px;">
                <div class="container">
                    <div class="hero-section-main">
                        <div class="hero-section-title">
                            <h1>
                                Profile
                            </h1>
                            <p class="text-white link-nav"><a href="{{ url('/index') }}">Home </a> <i
                                    class="fa-solid fa-arrow-right"></i> <a href="{{ url('/profile') }}"> Profile </a>
                        </div>
                    </div>
                </div>

            </div>
        </header>
        <main class="main">
            <div class="profile-container">
                <div class="profile-card">

                    <div class="profile-image-section">
                        <div class="image-circle">
                            @if($profile_user->profile_img)
                            <img src="{{ $profile_user->profile_image_url }}" alt="Profile Image">
                            @else
                            <img src="{{ asset('images/user-img/default.jpg') }}" alt="Default Profile">
                            @endif
                        </div>
                    </div>

                    <div class="profile-header-actions">
                        <a href="{{ url('/connect') }}" class="btn-connect">Connect</a>
                        <a href="" class="btn-message">Message</a>
                    </div>

                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-num">{{ sprintf('%02d', $photo_count) }}</span>
                            <span class="stat-label">Photos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">{{ sprintf('%02d', $post_count) }}</span>
                            <span class="stat-label">Posts</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">{{ sprintf('%02d', $comment_count) }}</span>
                            <span class="stat-label">Comments</span>
                        </div>
                    </div>

                    <div class="profile-info-text">
                        <h3>{{ $profile_user->first_name ?? $profile_user->name }} {{ $profile_user->last_name }}, <span class="age-text">{{ $profile_user->age }}</span></h3>
                        <p class="location-text"><i class="fa-solid fa-location-dot"></i> {{ $profile_user->location }}</p>

                        <div class="job-info">
                            <p><i class="fa-solid fa-briefcase"></i> Solution Manager - Creative Tim Officer</p>
                            <p><i class="fa-solid fa-graduation-cap"></i> University of Computer Science</p>
                        </div>

                        <hr class="divider">

                        <p class="bio-text">
                            {{ $profile_user->about_me ?? 'No description provided.' }}
                        </p>

                        <div class="profile-footer">
                            <a href="{{ route('profile.edit') }}" class="show-more">Show more</a>
                            <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-comments">
                <div class="comments-scrool">
                    @forelse ($comments as $comment)
                    <div class="comment-list" style="margin-bottom: 30px;">
                        <div class="single-comment">
                            <div class="thumb">
                                <img src="{{ $comment->avatar_url }}" alt="avatar" style="width:50px; height:50px; border-radius:50%; object-fit: cover;">
                            </div>
                            <div class="desc">
                                <h5>{{ $comment->name }}</h5>
                                <p class="date">{{ $comment->created_at->diffForHumans() }}</p>
                                <div class="comment">
                                    <p>{{ $comment->message }}</p>
                                </div>
                            </div>
                            <div class="reply-btn" style="margin-left: auto;">
                                <a href="{{ route('blog.show', $comment->post_id) }}#comments-section" class="btn-reply text-uppercase">
                                    Reply
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p>No comments yet!</p>
                    @endforelse
                </div>
            </div>
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
    <script src="{{ asset('js/profile.js') }}"></script>
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
            toggleComments()


            lazyLoad()
        })
    </script>
</body>

</html>
