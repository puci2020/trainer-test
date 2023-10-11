function updateViews(lastView) {
    const myHeaders = new Headers()
    myHeaders.append('Content-Type', 'application/json')

    var raw = JSON.stringify({
        data: {
            view: lastView + 1,
            last_visit: new Date(),
        },
    })

    var requestOptions = {
        method: 'PUT',
        headers: myHeaders,
        body: raw,
        redirect: 'follow',
    }

    fetch('https://panel.trenujzfotka.pl/api/views/1', requestOptions)
        .then((response) => response.text())
        .then((result) => {})
        .catch((error) => {})
}

$(function () {
    const fotka_session = sessionStorage.getItem('fotka_session')

    if (!fotka_session) {
        var requestOptions = {
            method: 'GET',
            redirect: 'follow',
        }
        fetch('https://panel.trenujzfotka.pl/api/views/1', requestOptions)
            .then((response) => response.text())
            .then((result) => {
                const last = JSON.parse(result)
                updateViews(last.data.attributes.view)
            })
            .catch((error) => {})
    }
    sessionStorage.setItem('fotka_session', true)

    // init feather icons
    feather.replace()

    // init tooltip & popovers
    $('[data-toggle="tooltip"]').tooltip()
    $('[data-toggle="popover"]').popover()

    //page scroll
    $('a.page-scroll').bind('click', function (event) {
        var $anchor = $(this)
        $('html, body')
            .stop()
            .animate(
                {
                    scrollTop: $($anchor.attr('href')).offset().top - 20,
                },
                1000,
            )
        event.preventDefault()
    })

    // slick slider
    $('.slick-about').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        arrows: false,
    })

    $('.slider_three').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    })
    $('.slider_opinion').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        dots: true,
        arrows: true,
        swipeToSlide: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    })

    $('.slider_opinion').on('wheel', function (e) {
        e.preventDefault()

        if (e.originalEvent.deltaY < 0) {
            $(this).slick('slickNext')
        } else {
            $(this).slick('slickPrev')
        }
    })
    $('.slider_opinion')
        .on('touchstart', function (e) {
            isDragging = false // Reset the dragging flag
            startX = e.originalEvent.touches[0].pageX
        })
        .on('touchmove', function (e) {
            endX = e.originalEvent.touches[0].pageX

            // Check if the user is scrolling the page vertically
            if (Math.abs(startX - endX) < 10) {
                isDragging = false // Not a horizontal swipe
                return
            }

            isDragging = true
            e.preventDefault() // Prevent page scrolling while swiping
        })
        .on('touchend', function () {
            if (isDragging) {
                const deltaX = endX - startX
                if (deltaX > 50) {
                    // Dragged to the right, move to the previous item
                    $(this).slick('slickPrev')
                } else if (deltaX < -50) {
                    // Dragged to the left, move to the next item
                    $(this).slick('slickNext')
                }
            }
            isDragging = false
        })

    $(document).on('touchend', function () {
        isDragging = false
    })

    $('.slider_one').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    })

    //toggle scroll menu
    var scrollTop = 0
    $(window).scroll(function () {
        var scroll = $(window).scrollTop()
        //adjust menu background
        if (scroll > 80) {
            if (scroll > scrollTop) {
                $('.smart-scroll').addClass('scrolling').removeClass('up')
                // $('.smart-scroll').removeClass('up');
            } else {
                $('.smart-scroll').addClass('up')
            }
        } else {
            // remove if scroll = scrollTop
            $('.smart-scroll').removeClass('scrolling').removeClass('up')
            // $('.smart-scroll').removeClass('up');
        }

        scrollTop = scroll

        // adjust scroll to top
        if (scroll >= 600) {
            $('.scroll-top').addClass('active')
        } else {
            $('.scroll-top').removeClass('active')
        }
        return false
    })

    // scroll top top
    $('.scroll-top').click(function () {
        $('html, body').stop().animate(
            {
                scrollTop: 0,
            },
            1000,
        )
    })
    $('button.navbar-toggler').click(function () {
        let show = $(this).attr('aria-expanded')
        console.log(show)
        $('.navbar-container').css('background-color', 'none')

        if (show == false) {
            $('.navbar-container').css('background-color', 'black')
        }
    })

    //fetch feedbacks
    var requestOptions = {
        method: 'GET',
        redirect: 'follow',
    }

    fetch('https://panel.trenujzfotka.pl/api/feedbacks', requestOptions)
        .then((response) => response.text())
        .then((result) => {
            let res = JSON.parse(result)
            res.data.forEach((opinionData) => {
                $('.slider_opinion').slick(
                    'slickAdd',
                    `
                    <div class="card img-fluid rounded d-block m-2 opinion_block">
        <div class="card-body">
            <h5 class="card-title pt-3">${opinionData.attributes.name}</h5>
            <div class="ratings">
                ${'<i class="fa fa-star rating-color"></i>'.repeat(
                    opinionData.attributes.rate,
                )}
                ${'<i class="fa fa-star"></i>'.repeat(
                    5 - opinionData.attributes.rate,
                )}
            </div>
            <div class="text-muted font-weight-medium mt-2">${
                opinionData.attributes.description
            }</div>
        </div></div>
    `,
                )
                // const opinionBlock = createOpinionBlock(opinionData.attributes)
                // requiredDiv.appendChild(opinionBlock)
            })
        })
        .catch((error) => console.log('error', error))

    var polipop = new Polipop('mypolipop', {
        layout: 'popups',
        insert: 'after',
        pool: 5,
        closer: false,
        sticky: false,
        position: 'bottom-right',
    })

    const contactForm = document.getElementById('contact_form')
    const feedbackForm = document.getElementById('feedback_form')

    if (contactForm)
        contactForm.addEventListener('submit', async (event) => {
            event.preventDefault()

            const firstName = document.getElementById('first_name').value
            const email = document.getElementById('email').value
            const subject = document.getElementById('subject').value
            const message = document.getElementById('message').value
            const rodo = document.getElementById('rodo').checked

            if (!firstName || !email || !subject || !message || !rodo) {
                polipop.add({
                    content:
                        'Sprawdź, czy formularz jest poprawnie wypełniony!',
                    title: 'Coś poszło nie tak :(',
                    type: 'error',
                })
                event.preventDefault()
                if (!firstName)
                    document
                        .getElementById('first_name')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('first_name')
                        .classList.remove('is-invalid')

                if (!email)
                    document.getElementById('email').classList.add('is-invalid')
                else
                    document
                        .getElementById('email')
                        .classList.remove('is-invalid')

                if (!subject)
                    document
                        .getElementById('subject')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('subject')
                        .classList.remove('is-invalid')

                if (!message)
                    document
                        .getElementById('message')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('message')
                        .classList.remove('is-invalid')

                if (!rodo)
                    document.getElementById('rodo').classList.add('is-invalid')
                else
                    document
                        .getElementById('rodo')
                        .classList.remove('is-invalid')
            } else {
                const myHeaders = new Headers()
                myHeaders.append('Content-Type', 'application/json')

                var raw = JSON.stringify({
                    data: {
                        name: firstName,
                        email: email,
                        subject: subject,
                        message: message,
                    },
                })

                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow',
                }

                fetch(
                    'https://panel.trenujzfotka.pl/api/emails',
                    requestOptions,
                )
                    .then((response) => response.text())
                    .then((result) => {
                        contactForm.reset()
                        polipop.add({
                            content: 'Dziękuję za wiadomość!',
                            title: 'Sukces',
                            type: 'success',
                        })
                    })
                    .catch((error) =>
                        polipop.add({
                            content:
                                'Spróbuj ponownie lub powiadom mnie o incydencie!',
                            title: 'Coś poszło nie tak :(',
                            type: 'error',
                        }),
                    )
            }
        })

    if (feedbackForm)
        feedbackForm.addEventListener('submit', async (event) => {
            event.preventDefault()

            const firstName = document.getElementById('first_name').value
            const feedbackValue =
                document.getElementById('feedback_value').value
            const comment = document.getElementById('comment').value
            const rodo = document.getElementById('rodo').checked

            if (!firstName || !feedbackValue || !comment || !rodo) {
                polipop.add({
                    content:
                        'Sprawdź, czy formularz jest poprawnie wypełniony!',
                    title: 'Coś poszło nie tak :(',
                    type: 'error',
                })
                event.preventDefault()
                if (!firstName)
                    document
                        .getElementById('first_name')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('first_name')
                        .classList.remove('is-invalid')

                if (!feedbackValue)
                    document
                        .getElementById('feedback_value')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('feedback_value')
                        .classList.remove('is-invalid')

                if (!comment)
                    document
                        .getElementById('comment')
                        .classList.add('is-invalid')
                else
                    document
                        .getElementById('comment')
                        .classList.remove('is-invalid')

                if (!rodo)
                    document.getElementById('rodo').classList.add('is-invalid')
                else
                    document
                        .getElementById('rodo')
                        .classList.remove('is-invalid')
            } else {
                const myHeaders = new Headers()
                myHeaders.append('Content-Type', 'application/json')

                var raw = JSON.stringify({
                    data: {
                        rate: feedbackValue,
                        description: comment,
                        name: firstName,
                        publishedAt: null,
                    },
                })

                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow',
                }

                fetch(
                    'https://panel.trenujzfotka.pl/api/feedbacks',
                    requestOptions,
                )
                    .then((response) => response.text())
                    .then((result) => {
                        feedbackForm.reset()
                        polipop.add({
                            content: 'Dziękuję za opinię!',
                            title: 'Sukces',
                            type: 'success',
                        })
                    })
                    .catch((error) =>
                        polipop.add({
                            content:
                                'Spróbuj ponownie lub powiadom mnie o incydencie!',
                            title: 'Coś poszło nie tak :(',
                            type: 'error',
                        }),
                    )
            }
        })
})
