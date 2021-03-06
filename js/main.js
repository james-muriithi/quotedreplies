//Global letiable for starting page
let currentPageId = "page-home";
let currentSelectorId = "home";

//Function for getting the button ids
const getButtons = ()=> {
    //List of button ids
    return ["home", "popular", "about", "account"];
     
}
// service worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('service-worker.js')
            .then(()=> {
                console.log("Service Worker Registered,");
            });
    });
}

//Make sure the window is loaded before we add listeners
window.onload = ()=> {
    let pageIdList = getButtons();
    //Add an event listener to each button
    pageIdList.forEach(page => {
        document.getElementById(page).addEventListener("click", changePage, false);
    });
    if (window.jQuery) {
        $('.navbar-nav').children().each(function(index, el) {
            $(el).on('click', changePage);
        });
    }
}

window.addEventListener('DOMContentLoaded', function(){
    if (window.jQuery) {
        const parsedUrl = new URL(window.location)
        let url = parsedUrl.searchParams.get('url');
        console.log(url);

        url = url ? url : parsedUrl.searchParams.get('text');

        if (url) {
            const [link, tweetId] = generateLink(url);

            if (link && tweetId) {
                $('input[name="url"]').val(url)
                $('a.link').attr('href', link).parent().removeClass('d-none')
                // $('.search-svg svg').addClass('d-none');
            } else {
                window.history.replaceState({}, '', '/quotedreplies/')
                $('input[name="url"]').val('')
                $('a.link').parent().addClass('d-none')
            }
        } else {
            $('input[name="url"]').val('')
            $('a.link').parent().addClass('d-none')
        }

        $('a.link').on('click', function(event) {
            window.history.replaceState({}, '', '/quotedreplies/')
            $('input[name="url"]').val('')
            $('a.link').parent().addClass('d-none')
        });
    }
})

const changePage = function() {
    id = this.id ? this.id : this.dataset.trigger
    let currentSelector = document.getElementById(currentSelectorId);
    let currentPage = document.getElementById(currentPageId);
    let pageId = "page-" + id;
    let page = document.getElementById(pageId);
    let pageSelector = document.getElementById(id);

    if (page.classList.contains("active")) {
        return;
    }

    currentSelector.classList.remove("button-active");
    currentSelector.classList.add("button-inactive");
    currentPage.classList.remove("active");
    currentPage.classList.add("inactive");

    pageSelector.classList.remove("button-inactive");
    pageSelector.classList.add("button-active");

    page.classList.remove("inactive");
    page.classList.add("active");

    //Need to reset the scroll
    window.scrollTo(0, 0);

    currentSelectorId = id;
    currentPageId = pageId;

    $('.navbar-nav').children().removeClass('active')
    $('.navbar-nav').find(`[data-trigger='${id}']`).addClass('active');
}

function generateLink (url) {
    let parts = url.match(/(^|[^'"])(https?:\/\/twitter\.com\/(?:#!\/)?(\w+)\/status(?:es)?\/(\d+))/);
    try {
        let tweetId = parts[4]
        let link = `https://twitter.com/search?q=-from:quoted_replies%20url:${tweetId}`
        return [link, tweetId];

    } catch (error) {
        return[null, null];
    }
}

// window.twttr = (function (d, s, id) {
//     var t, js, fjs = d.getElementsByTagName(s)[0];
//     if (d.getElementById(id)) return;
//     js = d.createElement(s);
//     js.id = id;
//     js.src = "https://platform.twitter.com/widgets.js";
//     fjs.parentNode.insertBefore(js, fjs);
//     return window.twttr || (t = {
//         _e: [],
//         ready: function (f) {
//             t._e.push(f)
//         }
//     });
// }(document, "script", "twitter-wjs"));