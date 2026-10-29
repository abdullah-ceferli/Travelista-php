function navBarLinksChilds() {
    document.querySelectorAll('.menu-has-children').forEach(function (menu) {
        let timeout
        const submenu = menu.querySelector('ul')

        menu.addEventListener('mouseenter', function () {
            clearTimeout(timeout)
            submenu.classList.add('is-visible')
        })

        menu.addEventListener('mouseleave', function () {
            timeout = setTimeout(function () {
                submenu.classList.remove('is-visible')
            }, 200)
        })
    })
}

document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('.tab-link')
    const tabPanes = document.querySelectorAll('.tab-pane')

    tabLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault()

            tabLinks.forEach(item => item.classList.remove('active'))
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active')
            })

            this.classList.add('active')

            const targetId = this.getAttribute('data-target')
            const targetPane = document.getElementById(targetId)

            if (targetPane) {
                targetPane.classList.add('active')
                setTimeout(() => {
                    targetPane.classList.add('show')
                }, 10)
            }
        })
    })
})


function navBarMovement() {
    const headerSticky = document.getElementById("nav-bar")
    const navBarMainMenu = document.querySelector(".nav-bar-main-menu")

    window.addEventListener("scroll", () => {
        if (window.scrollY > 80) {
            headerSticky.classList.add("nav-bar-scrolled")
            navBarMainMenu.style.background = "transparent"
        }
        else {
            headerSticky.classList.remove("nav-bar-scrolled")
            navBarMainMenu.style.background = "rgba(255, 255, 255, 0.15)"
        }
    })
}

function recentBlogCarusel() {
    const slider = document.getElementById('blogSlider')
    const dots = document.querySelectorAll('.recent-blog-dot')
    const cards = document.querySelectorAll('.single-recent-blog-post:not(.clone)')
    if (!slider || !cards.length) return
    let autoPlay

    const getWidth = () => cards[0].offsetWidth + 30

    const scrollToIndex = (i) => {
        slider.scrollTo({ left: getWidth() * i, behavior: 'smooth' })
    }

    const startAuto = () => {
        autoPlay = setInterval(() => {
            let current = Math.round(slider.scrollLeft / getWidth())
            let next = (current + 1) % cards.length

            if (current >= cards.length - 1) {
                slider.scrollTo({ left: 0, behavior: 'auto' })
                next = 0;
            }
            scrollToIndex(next)
        }, 10000)
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(autoPlay)
            scrollToIndex(i % cards.length)
            setTimeout(startAuto, 3000)
        })
    })

    slider.addEventListener('scroll', () => {
        const activeIdx = Math.round(slider.scrollLeft / getWidth()) % cards.length
        dots.forEach((d, i) => d.classList.toggle('active', i === activeIdx))
    })

    startAuto()

}

function smoothUp() {
    document.querySelectorAll('a[href="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault()
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            })
        })
    })
}


async function loadTestimonials() {
    const slider = document.getElementById('testimonial-slider')
    const dotContainer = document.getElementById('dotContainer')
    if (!slider || !dotContainer) return

    try {
        const response = await fetch(slider.dataset.testimonialsUrl, { headers: { 'Accept': 'application/json' } })
        if (!response.ok) throw new Error('Unable to load testimonials.')
        const data = await response.json()
        const items = data.results || data

        if (items.length > 0) {
            slider.replaceChildren()
            items.forEach(item => {
                let starsHtml = ''
                for (let i = 1; i <= 5; i++) {
                    starsHtml += i <= item.stars ? ' ★ ' : ' ☆ '
                }

                const card = document.createElement('div')
                card.className = 'testimonial-card'
                const image = document.createElement('img')
                image.src = item.user_img
                image.className = 'testimonial-card-img'
                image.alt = 'user'
                const content = document.createElement('div')
                const message = document.createElement('p')
                message.className = 'testimonial-card-text'
                message.textContent = item.message
                const name = document.createElement('h4')
                name.className = 'testimonial-card-user-name'
                name.textContent = item.name + ' ' + item.surname
                const stars = document.createElement('div')
                stars.className = 'testimonial-card-stars'
                stars.textContent = starsHtml
                content.append(message, name, stars)
                card.append(image, content)
                slider.append(card)
            })

            let dotsHtml = ''
            items.forEach((_, i) => {
                dotsHtml += `<div class="testimonial-nav-dot ${i === 0 ? 'active' : ''}"></div>`
            })
            dotContainer.innerHTML = dotsHtml

            testimonialCarusel()

        } else {
            slider.innerHTML = '<p>No testimonials available.</p>'
        }
    } catch (error) {
        console.error("Error:", error)
    }
}


function testimonialCarusel() {
    const slider = document.getElementById('testimonial-slider')
    const dotContainer = document.getElementById('dotContainer')
    if (!slider || !dotContainer) return

    const dots = dotContainer.querySelectorAll('.testimonial-nav-dot')
    const cards = slider.querySelectorAll('.testimonial-card')
    if (cards.length === 0) return

    let autoSlideInterval;
    const totalItems = dots.length

    function goToSlide(index) {
        const cardWidth = cards[0].offsetWidth + 20
        slider.scrollTo({ left: cardWidth * index, behavior: 'smooth' })
        resetAutoSlide()
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index))
    })

    slider.addEventListener('scroll', () => {
        const cardWidth = cards[0].offsetWidth + 20
        const activeIndex = Math.round(slider.scrollLeft / cardWidth) % totalItems
        dots.forEach((dot, i) => dot.classList.toggle('active', i === activeIndex))
    })

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            const cardWidth = cards[0].offsetWidth + 20
            let currentIndex = Math.round(slider.scrollLeft / cardWidth)
            let nextIndex = (currentIndex + 1) % totalItems
            goToSlide(nextIndex)
        }, 10000)
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval)
        startAutoSlide()
    }

    startAutoSlide()
}
