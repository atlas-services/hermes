/*
ping pong
 */

var loop = true;
var easing = 'linear';
var direction = 'alternate';
var width = 780;
var heighttop1 = '-10';
var heightbottom1 = 700;
var loopball = 8;
var loopleft = 4;
var loopright = 6;

var x = window.matchMedia("(max-width: 900px)")
myHeigt(x) // Call listener function at run time
x.addListener(myHeigt)
function myHeigt(x) {
    if (x.matches) { // If media query matches
         heighttop1 = '-10';
         heightbottom1 = 700;
    } else {
         heighttop1 = '-10';
         heightbottom1 = 200;
    }
}

anime({
    options: 2000,
    targets: '.ball',
    translateX: width,
    translateY: heightbottom1,
    easing,
    loop: loopball,
    direction,
    background: [
        { value: '#573796' },
        { value: '#FB89FB' },
        { value: '#FBF38C' },
        { value: '#18FF92' },
        { value: '#5A87FF' }
    ]
})
var ballTimeline = anime.timeline({
    loop: loopleft,
    direction
})
var bar2Timeline = anime.timeline({
    loop: loopright,
    direction
})
var bar1Timeline = anime.timeline({
    loop: loopleft,
    direction
})
ballTimeline
    .add({
        targets: '.ball',
        translateY: heightbottom1,
        translateX: width,
        easing
    }).add({
    targets: '.ball',
    translateY: 0,
    translateX: 0,
    easing
}).add({
    targets: '.ball',
    translateY: heighttop1,
    translateX: width,
    easing
})
bar2Timeline
    .add({
        targets: '.bar2',
        translateY: heightbottom1,
        easing,
        background: '#573796'
    }).add({
    targets: '.bar2',
    translateY: 0,
    easing,
    background: '#FB89FB'
}).add({
    targets: '.bar2',
    translateY: heighttop1,
    easing,
    background: '#692b02'
})
bar1Timeline
    .add({
        targets: '.bar1',
        translateY: heighttop1,
        easing,
        background: '#18FF92'
    }).add({
    targets: '.bar1',
    translateY: 10,
    easing,
    background: '#5A87FF'
}).add({
    targets: '.bar1',
    translateY: heightbottom1,
    easing,
    background: '#692b02'
})

/*
Text Hermes
 */



var lineDrawing = anime({
    targets: '#lineDrawing .lines path',
    strokeDashoffset: [anime.setDashoffset, 0],
    easing: 'easeInOutSine',
    duration: 3000,
    delay: function(el, i) { return i * 250 },
    direction: 'alternate',
    loop: true
});




/*
text anime
 */
function fitElementToParent(el, padding, exception) {
    var timeout = null;
    function resize() {
        if (timeout) clearTimeout(timeout);
        anime.set(el, {scale: 1});
        if (exception) anime.set(exception, {scale: 1});
        var pad = padding || 0;
        var parentEl = el.parentNode;
        var elOffsetWidth = el.offsetWidth - pad;
        var parentOffsetWidth = parentEl.offsetWidth;
        var ratio = parentOffsetWidth / elOffsetWidth;
        var invertedRatio = elOffsetWidth / parentOffsetWidth;
        timeout = setTimeout(function() {
            anime.set(el, {scale: ratio});
            if (exception) anime.set(exception, {scale: invertedRatio});
        }, 10);
    }
    resize();
    window.addEventListener('resize', resize);
}

// main logo animation

    var logoAnimation = (function () {
        if ($(".logo-animation")[0]) {

        var logoAnimationEl = document.querySelector('.logo-animation');
        var bouncePath = anime.path('.bounce path');

        fitElementToParent(logoAnimationEl, 0, '.bounce svg');

        anime.set(['.letter-h', '.letter-e', '.letter-r'], {translateX: 70});
        anime.set('.letter-e', {translateX: -70});
        anime.set('.dot', {translateX: 630, translateY: -200});

        var logoAnimationTL = anime.timeline({
            autoplay: false,
            easing: 'easeOutSine'
        })
            .add({
                targets: '.letter-r .line',
                duration: 0,
                begin: function (a) {
                    a.animatables[0].target.removeAttribute('stroke-dasharray');
                }
            }, 0)
            .add({
                targets: '.bounced',
                transformOrigin: ['50% 100% 0px', '50% 100% 0px'],
                translateY: [
                    {value: [150, -160], duration: 190, endDelay: 20, easing: 'cubicBezier(0.225, 1, 0.915, 0.980)'},
                    {value: 4, duration: 120, easing: 'easeInQuad'},
                    {value: 0, duration: 120, easing: 'easeOutQuad'}
                ],
                scaleX: [
                    {value: [.25, .85], duration: 190, easing: 'easeOutQuad'},
                    {value: 1.08, duration: 120, delay: 85, easing: 'easeInOutSine'},
                    {value: 1, duration: 260, delay: 25, easing: 'easeOutQuad'}
                ],
                scaleY: [
                    {value: [.3, .8], duration: 120, easing: 'easeOutSine'},
                    {value: .35, duration: 120, delay: 180, easing: 'easeInOutSine'},
                    {value: .57, duration: 180, delay: 25, easing: 'easeOutQuad'},
                    {value: .5, duration: 190, delay: 15, easing: 'easeOutQuad'}
                ],
                delay: anime.stagger(80)
            }, 1000)
            .add({
                targets: '.dot',
                opacity: {value: 1, duration: 100},
                translateY: 250,
                scaleY: [4, .7],
                scaleX: {value: 1.3, delay: 100, duration: 200},
                duration: 280,
                easing: 'cubicBezier(0.350, 0.560, 0.305, 1)'
            }, '-=290')
            .add({
                targets: '.letter-m .line',
                easing: 'easeOutElastic(1, .8)',
                duration: 600,
                d: function (el) {
                    return el.dataset.d2
                },
                begin: function (a) {
                    a.animatables[0].target.removeAttribute('stroke-dasharray');
                }
            }, '-=140')
            .add({
                targets: ['.letter-h', '.letter-e', '.letter-r', '.letter-e'],
                translateX: 0,
                easing: 'easeOutElastic(1, .6)',
                duration: 800,
                delay: anime.stagger(40, {from: 2.5}),
                change: function (a) {
                    a.animatables[2].target.removeAttribute('stroke-dasharray');
                }
            }, '-=600')
            .add({
                targets: '.letter-m .line',
                d: function (el) {
                    return el.dataset.d3
                },
                easing: 'spring(.2, 200, 3, 60)',
            }, '-=680')
            .add({
                targets: '.dot',
                translateX: bouncePath('x'),
                translateY: bouncePath('y'),
                rotate: {value: '1turn', duration: 790},
                scaleX: {value: 1, duration: 50, easing: 'easeOutSine'},
                scaleY: [
                    {value: [1, 1.5], duration: 50, easing: 'easeInSine'},
                    {value: 1, duration: 50, easing: 'easeOutExpo'}
                ],
                easing: 'cubicBezier(0, .74, 1, .255)',
                duration: 800
            }, '-=1273')
            .add({
                targets: '.dot',
                scale: 1,
                rotate: '1turn',
                scaleY: {value: .5, delay: 0, duration: 150, delay: 230},
                translateX: 430,
                translateY: [
                    {value: 244, duration: 100},
                    {value: 204, duration: 200, delay: 130},
                    {value: 224, duration: 225, easing: 'easeOutQuad', delay: 25}
                ],
                duration: 200,
                easing: 'easeOutSine'
            }, '-=474')
            .add({
                targets: '.letter-r .line',
                transformOrigin: ['50% 100% 0', '50% 100% 0'],
                d: function (el) {
                    return el.dataset.d2
                },
                easing: 'cubicBezier(0.400, 0.530, 0.070, 1)',
                duration: 80
            }, '-=670')
            .add({
                targets: '.logo-letter',
                translateY: [
                    {value: 40, duration: 150, easing: 'easeOutQuart'},
                    {value: 0, duration: 800, easing: 'easeOutElastic(1, .5)'}
                ],
                strokeDashoffset: [anime.setDashoffset, 0],
                delay: anime.stagger(60, {from: 'center'})
            }, '-=670')
            .add({
                targets: '.bounced',
                scaleY: [
                    {value: .4, duration: 150, easing: 'easeOutQuart'},
                    {value: .5, duration: 800, easing: 'easeOutElastic(1, .5)'}
                ],
                delay: anime.stagger(60, {from: 'center'})
            }, '-=1090')
            .add({
                targets: '.logo-text',
                translateY: [
                    {value: 20, easing: 'easeOutQuad', duration: 100},
                    {value: 0, easing: 'easeOutElastic(1, .9)', duration: 450}
                ],
                opacity: {value: [0.001, 1], duration: 50},
                duration: 500
            }, '-=970')
            .add({
                targets: '.main-logo-circle',
                opacity: {value: [0.001, 1], duration: 1500},
                backgroundImage: ['linear-gradient(-135deg, #FFFFFF 50%, #F6F4F2 75%, #F6F4F2 100%, #DDDAD7 100%)', 'linear-gradient(-135deg, #FFFFFF 5%, #F6F4F2 40%, #F6F4F2 70%, #DDDAD7 100%)'],
                translateY: {value: ['60px', 0], easing: 'cubicBezier(0.175, 0.865, 0.245, 0.840)'},
                duration: 2000,
                easing: 'easeInOutQuad'
            }, '-=970')
            .add({
                targets: ['.description-title', '.description-paragraph'],
                opacity: {value: [0.001, 1], easing: 'cubicBezier(0.175, 0.865, 0.245, 0.840)'},
                translateY: {value: ['80px', 0], easing: 'cubicBezier(0.175, 0.865, 0.245, 0.840)'},
                duration: 3500,
                delay: anime.stagger(75)
            }, '-=1300')

        return logoAnimationTL;

    }
    })();


if ($(".logo-animation")[0]) {
    logoAnimation.play();
}

/*
stagering
 */

// const images = document.querySelectorAll("span.staggering");
//
// images.forEach((image, index) => {
//     image.addEventListener("mouseover", e => {
//         anime({
//             targets: images,
//             autoplay: true,
//             opacity: [
//                 { value: .5, easing: "easeOutSine", duration: 600 },
//                 { value: 1, easing: "easeInOutQuad", duration: 900 }],
//
//             filter: [
//                 { value: "blur(5px) grayscale(100%)", easing: "easeOutSine", duration: 700 },
//                 { value: "blur(0px) grayscale(0%)", easing: "easeInOutQuad", duration: 1000 }],
//
//             borderRadius: [
//                 { value: "50%", easing: "easeOutSine", duration: 700 },
//                 { value: "0%", easing: "easeInOutQuad", duration: 1000 }],
//
//             scale: [
//                 { value: 0.5, easing: "easeOutSine", duration: 600 },
//                 { value: 1, easing: "easeInOutQuad", duration: 1100 }],
//
//             delay: anime.stagger(200, { grid: [5, 5], from: index }) });
//
//     });
// });
//
// // window.onload = () => document.querySelector('h1').remove();
// document.addEventListener("DOMContentLoaded", () => {
//     document.querySelectorAll("img.stagge").forEach(img => {
//         img.onerror = function () {
//             this.style.display = "none";
//         };
//     });
// });

/*
anime card
 */

var card = document.getElementById("card-anime");
var playing = false;
// document.addEventListener("click", function () {
$('.card-click').on("click", function () {
    if (playing) {
        $('.card-container').hide();
        return;
    }

    playing = true;
    anime({
        targets: '.card-anime',
        scale: [{ value: 1 }, { value: 1.4 }, { value: 1, delay: 250 }],
        rotateY: { value: "+=180", delay: 200 },
        easing: "easeInOutSine",
        duration: 400,
        complete: function (anim) {
            playing = false;
        }
    });
});