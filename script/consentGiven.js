// ==ClosureCompiler==
// @compilation_level SIMPLE_OPTIMIZATIONS
// @output_file_name default.js
// ==/ClosureCompiler==

// get mtm_settings or create default object
var mtm_setting = mtm_setting || {
    'requireDSGVO': '0',
    'requireConsent': '0',
    'bannerContent': '',
    'relativeUrl': '',
    'siteId': '',
    'contextPath': '',
    'linkColor': '',
    'position': '',
    'cookieTTL': '',
    'disableCookies': '0',
    'acceptBtnTxt': 'Accept',
    'acceptBtnColor': '#fff',
    'acceptBtnBGColor': 'green',
    'declineBtnTxt': 'Decline',
    'declineBtnColor': '#ffffff',
    'declineBtnBGColor': '#808080'
};
// matomo _paq array
var _paq = _paq || [];
// name of the plugin cookie
let _cookieName = 'OJSMTM';
// cookie time to live
let _cookieTTL = mtm_setting.cookieTTL;
let cookie = getCookie(_cookieName);
if (mtm_setting.requireDSGVO === '1') {
    if (mtm_setting.disableCookies === '1') {
        _paq.push(['disableCookies']);
    }
    // opt-in settings: consent required enabled and push consent if cookie content === "true"
    if (mtm_setting.requireConsent === '1') {
        _paq.push(['requireConsent']);
        if (cookie != null)
            pushMatomoSettings(cookie === 'true');
    }
    // build banner if GDPR is enabled and no cookie available
    if (cookie == null)
        buildBannerAndListener();
}
_paq.push(['trackPageView']);
_paq.push(['enableLinkTracking']);
(function () {
    var u = mtm_setting.relativeUrl;
    _paq.push(['setTrackerUrl', u + 'matomo.php']);
    _paq.push(['setSiteId', mtm_setting.siteId]);
    _paq.push(['setDocumentTitle', mtm_setting.contextPath]);
    var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
    g.type = 'text/javascript';
    g.async = true;
    g.defer = true;
    g.src = u + 'matomo.js';
    s.parentNode.insertBefore(g, s);
})();

function buildBannerAndListener(linkColor) {
    let optOut = document.createElement("div");
    optOut.id = "mtm-form";
    optOut.style.position = 'fixed';
    optOut.style.minWidth = '100%';
    if (mtm_setting.position === 'top')
        optOut.style.top = '0';
    else
        optOut.style.bottom = '0';
    optOut.style.margin = '0';
    optOut.style.padding = '20px';
    optOut.style.color = 'white';
    optOut.style.textAlign = 'center';
    optOut.style.backgroundColor = 'rgba(0,0,0,0.8)';
    optOut.style.zIndex = '100000';
    let paragraph = document.createElement("p");
    paragraph.innerHTML = mtm_setting.bannerContent;
    let link = paragraph.querySelector("a");
    if (link) {
        link.target = '_blank';
        link.style.color = mtm_setting.linkColor;
        link.style.fontWeight = 'bold';
        link.style.textDecoration = 'none';
    }
    optOut.append(paragraph);
    paragraph = document.createElement("p");
    let btn_revoke = document.createElement("button");
    btn_revoke.innerHTML = mtm_setting.declineBtnTxt;
    paragraph.append(btn_revoke);
    let btn_accept = document.createElement("button");
    btn_accept.innerHTML = mtm_setting.acceptBtnTxt;
    setButtonMargin(btn_revoke, btn_accept, window.innerWidth);
    styleButton(btn_accept, mtm_setting.acceptBtnBGColor, mtm_setting.acceptBtnColor);
    styleButton(btn_revoke, mtm_setting.declineBtnBGColor, mtm_setting.declineBtnColor);
    paragraph.append(btn_accept);
    optOut.append(paragraph);
    document.body.append(optOut);
    btn_revoke.addEventListener("click", function () {
        optOut.style.display = 'none';
        pushMatomoSettings(false);
        setCookie(_cookieName, false);
    });
    btn_accept.addEventListener("click", function () {
        optOut.style.display = 'none';
        pushMatomoSettings(true);
        setCookie(_cookieName, true);
    });
    window.onresize = function () {
        setButtonMargin(btn_revoke, btn_accept, window.innerWidth)
    }
}


/**
 * Button Style Function
 *
 * @param btn button
 * @param bgColor Background Color
 */
function styleButton(btn, bgColor, color) {
    btn.style.backgroundColor = bgColor;
    btn.style.border = 'none';
    btn.style.padding = '10px 20px';
    btn.style.borderRadius = '5px';
    btn.style.color = color;
    btn.style.cursor = 'pointer';
}

/**
 * Sets the Margin for the accept and decline button depending on the windows size
 *
 * @param btn_off decline button
 * @param btn_on accept button
 * @param w window.innerWidth
 */
function setButtonMargin(btn_off, btn_on, w) {
    if (w > 480) {
        btn_off.style.marginRight = '15vw';
        btn_on.style.marginLeft = '15vw'
    } else if (w > 400) {
        btn_off.style.marginRight = '10px';
        btn_on.style.marginLeft = '10px'
    } else if (w > 335) {
        btn_off.style.marginRight = '1px';
        btn_on.style.marginLeft = '1px'
    } else {
        btn_off.style.marginBottom = '5px'
    }
}

/**
 * Saves the consent given cookie.
 * Functional required true/false option with expire date.
 * This cookie is used to save the users selection for a specific time
 *
 * @param name
 * @param cvalue true(consent given), false (consent declined)
 */
function setCookie(name, cvalue) {
    if (_cookieTTL > 0) {
        let d = new Date();
        d.setTime(d.getTime() + (_cookieTTL * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + cvalue + ";" + expires + ";path=/"
    } else
        document.cookie = name + "=" + cvalue + ";path=/"
}

/**
 * Loads the consent cookie and returns its value
 *
 * @returns {string|null}
 */
function getCookie(name) {
    name = name + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1)
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length)
        }
    }
    return null
}

/**
 * Pushes the Opt Settings to matomos _paq array.
 * @param enabled true(consent given), false (consent declined)
 */
function pushMatomoSettings(enabled) {
    if (enabled) {
        _paq.push(['setConsentGiven']);
    } else {
        _paq.push(['optUserOut']);
    }
}