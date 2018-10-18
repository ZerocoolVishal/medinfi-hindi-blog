var cachedCity = null;
var cachedLat = null;
var cachedLong = null;
var highlightedElementIndex = -1;
var hideAutosuggestionDivFlag = true;
var LOADING_MESSAGE = "Loading Results...";
var ZERO_RESULT_MESSAGE ="No results found. View all blog posts.";
var keystrokeTimeout = null;
var KEY_STROKE_INTERVAL = 300;

if (typeof(Storage) !== "undefined") {
    if(localStorage.getItem("userCity") != null && 
      localStorage.getItem("popularLocalities") != null && 
      localStorage.getItem("userLat")!= null && 
      localStorage.getItem("userLng") != null) {
          cachedCity = localStorage.getItem("userCity");
          cachedLat = localStorage.getItem("userLat");
          cachedLong = localStorage.getItem("userLng");
    }
}
function showMessage(message) {
    if (message == LOADING_MESSAGE) {
        $loadingResultsHtml = '<li class="blog-auto-suggest-loading-li">Loading Results...</li>';
        document.getElementById('blog-auto-suggest-ul').innerHTML = $loadingResultsHtml;
    }
    else if (message == ZERO_RESULT_MESSAGE) {
        $loadingResultsHtml = '<li class="blog-auto-suggest-loading-li">No results found.</li>';
        document.getElementById('blog-auto-suggest-ul').innerHTML = $loadingResultsHtml;
    }
}

function focusOutActions() {
    var blogAutoSuggestionBox = document.getElementById("blog-auto-suggest-ul");
    var autoSuggestionElements = blogAutoSuggestionBox.getElementsByTagName("li");
    if (highlightedElementIndex !== -1 && blogAutoSuggestionBox !== null && autoSuggestionElements !==null) {
        d = autoSuggestionElements[highlightedElementIndex];
        d.className = d.className.replace(new RegExp ( '(?:^|\\s)' + 'highlighted-element' + '(?:\\s|$)'), ' ');
    }
    highlightedElementIndex = -1;
    if (blogAutoSuggestionBox != null) {
        blogAutoSuggestionBox.style.display = 'none';
    }
}
function updateAutoSuggestionDiv(serverResponse,searchInput) {
   
    var jsonResponse = JSON.parse(serverResponse);
   
    //console.log("JSON "+jsonResponse.length);
    if (jsonResponse.length > 0 ) {
        var blogAutoSuggestionListHtml = '';
        for(var index = 0; index < jsonResponse.length; index++){
            var postTitle = jsonResponse[index]['post_title'];
            var regEx = new RegExp(searchInput, "ig");
            //console.log(regEx);
            var replaceWith = '<span class="mark">$&</span>';
            postTitle = postTitle.replace(regEx, replaceWith);
            blogAutoSuggestionListHtml = blogAutoSuggestionListHtml +
            '<li class="blog-auto-suggest-li" onclick="openSelectedBlogPost(\''+jsonResponse[index]['post_url']+'\')" data-url="'+jsonResponse[index]['post_url']+'">'+
            postTitle+'</li>';
        }
        document.getElementById('blog-auto-suggest-ul').innerHTML = blogAutoSuggestionListHtml;
        //console.log("ul html "+document.getElementById('blog-auto-suggest-ul').innerHTML);
        //console.log('HTML >>> '+blogAutoSuggestionListHtml);
    }
    else{
        showMessage(ZERO_RESULT_MESSAGE);
    }
}
function getAutoSuggestions(searchInput) {
    showMessage(LOADING_MESSAGE);

    var ajax = new XMLHttpRequest();
    ajax.open("GET", baseUrl+"/auto-suggestions/?term="+searchInput, true);
    ajax.onload = function() {
        var response = this.responseText;
        if (ajax.status === 200) {
            try{
                updateAutoSuggestionDiv(response,searchInput);
            }
            catch(e){
                showMessage(ZERO_RESULT_MESSAGE);
                return;
            }
        }
        else{
            showMessage(ZERO_RESULT_MESSAGE);
        }
    };
    ajax.send();
}
function openSelectedBlogPost(postUrl){
    //console.log("About to open the blog post!!!");
    var searchInput = document.getElementById("blog-search").value.trim();
    var postUrlWithoutLeadingSlash = postUrl.substring(0,postUrl.lastIndexOf("/"));
    var postSlug = postUrlWithoutLeadingSlash.substring(postUrlWithoutLeadingSlash.lastIndexOf("/")+1);
    //console.log("postUrl"+postUrl+"/postUrlWithoutLeadingSlash "+postUrlWithoutLeadingSlash+"/postSlug"+postSlug);
    ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Selected search bar auto-suggestion',eventLabel:'Term: '+searchInput+'/Post Slug: '+postSlug});
    window.open(baseUrl+postUrl,'_self');
}


//window.onload = function() {
window.addEventListener('load', function() {
    var blogSearchBox = document.getElementById("blog-search");
    var searchButton = document.getElementById("search-glass");
    var navOpenButton = document.getElementById("mobile-navigation-button");
    var navCloseButton = document.getElementById("nav-close");
    var blogAutoSuggestionBox = document.getElementById("blog-auto-suggest-ul");
    var autoSuggestionElements = blogAutoSuggestionBox.getElementsByTagName("li");
    

    if (cachedCity != null && cachedLat != null && cachedLong != null) {
        cachedCitySlug = cachedCity.trim().toLowerCase().replace(" " ,"-");
        document.getElementById("mobile-nav-doc-anchor").setAttribute("href", websiteBaseUrl+"/doctor-search/"+cachedCitySlug+"/doc_/1?lat="+cachedLat+"&lng="+cachedLong);
        document.getElementById("mobile-nav-doc-anchor").setAttribute("onclick","ga('send', {hitType: 'event',eventCategory:'"+pageName+"', eventAction: 'Find doctors link in nav bar',eventLabel: 'Find doctors in "+cachedCity+"'});");
        document.getElementById("header-doc-anchor").setAttribute("href", websiteBaseUrl+"/doctor-search/"+cachedCitySlug+"/doc_/1?lat="+cachedLat+"&lng="+cachedLong);
        document.getElementById("header-doc-anchor").setAttribute("onclick","ga('send', {hitType: 'event',eventCategory:'"+pageName+"', eventAction: 'Find doctors link in header',eventLabel: 'Find doctors in "+cachedCity+"'});");
    }
    
    blogAutoSuggestionBox.addEventListener("mouseover",function (){
        //console.log("Mouse Over!!");
        hideAutosuggestionDivFlag = false;
    });
    blogAutoSuggestionBox.addEventListener("mouseout",function (){
        //console.log("Mouse out!!");
        hideAutosuggestionDivFlag = true;
    });
    searchButton.addEventListener("click",function(e){
        var searchInput = blogSearchBox.value.trim();
        if(searchInput.length > 0 && isNaN(searchInput)){
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Search Bar Button Click',eventLabel:searchInput});
            
            window.open(baseUrl+"/?s="+searchInput,"_self");
        }
    });
    
    blogSearchBox.addEventListener('focus', function() {
        // Show the auto-suggestion when search box has focus and has a input more than 1 character
        if(blogSearchBox.value.length >=2){ 
            blogAutoSuggestionBox.style.display = 'block';
        }
    });
    blogSearchBox.addEventListener('blur', function() {
        //alert("Focus out");
        if (hideAutosuggestionDivFlag) {
            focusOutActions();
        }
    });
    
    
    blogSearchBox.addEventListener("keyup", function(e){
        //alert("keyup");
        var keyCode = e.keyCode;
        var searchInput = blogSearchBox.value.trim();
        if (keyCode == 13) {
            if (highlightedElementIndex == -1 && searchInput.length > 0 && isNaN(searchInput)) {
                ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Search Bar Enter',eventLabel:searchInput});
                window.open(baseUrl+"/?s="+searchInput,"_self");
            }
            else if(highlightedElementIndex != -1){
                var postUrl = autoSuggestionElements[highlightedElementIndex].getAttribute("data-url");
                //console.log(autoSuggestionElements[highlightedElementIndex].getAttribute("data-url"));
                if (postUrl !== null) {
                    openSelectedBlogPost(postUrl);
                }
            }
        }
        else if (keyCode == 27 ) {    // Close the auto-suggestion box on key press of Esc(27) key
            focusOutActions();
            return;
        }
        else if (keyCode == 40 || keyCode == 38 ) {    // Down(40)/Up(38) arrow 
            
            var increment = 1;
            if(keyCode == 38){
                increment = -1;
            }
            //Remove highlight of current element.
            if (highlightedElementIndex !== -1) { 
                d = autoSuggestionElements[highlightedElementIndex];
                d.className = d.className.replace(new RegExp ( '(?:^|\\s)' + 'highlighted-element' + '(?:\\s|$)'), ' ');
            }
            
            //Highlight the next/prev element
            if (keyCode == 40 && highlightedElementIndex == (autoSuggestionElements.length - 1)) {
                highlightedElementIndex = 0 ;
            }
            else if (keyCode == 38 && (highlightedElementIndex == 0 || highlightedElementIndex == -1)) {
                highlightedElementIndex = autoSuggestionElements.length - 1;
            }
            else{
                highlightedElementIndex += increment;
            }
            
            d = autoSuggestionElements[highlightedElementIndex];
            d.className += " highlighted-element";  
        }
        else{
            //console.log("IsNAN"+isNaN(blogSearchBox.value));
            if (searchInput.length >= 2 && isNaN(searchInput)) {
                showMessage(LOADING_MESSAGE);
                if (keystrokeTimeout != null) {
                    clearTimeout(keystrokeTimeout);
                }
                keystrokeTimeout = setTimeout(function() {
                      keystrokeTimeout = null;
                      getAutoSuggestions(searchInput);
      
                    }, KEY_STROKE_INTERVAL);
                //getAutoSuggestions(blogSearchBox.value);
                
            }
        }
        
        
        if(searchInput.length >= 2 && isNaN(searchInput)){
            blogAutoSuggestionBox.style.display = 'block';
        }
        else{
            focusOutActions();
        }
    });
    
    
    
    /* Set the width of the side navigation to 250px */
    navOpenButton.addEventListener("click",function(e){
            navCloseButton.style.display = "block";
            document.getElementById("mobile-nav-div").style.width = "250px";
            document.getElementById("mobile-nav-div").style.borderRight = "#D9D9D9 solid thin";
            document.getElementById("mobile-nav-div").style.overflow = "auto";
            document.body.style.overflow = "hidden";
            
            document.getElementById("page-wrap").style.backgroundColor = "#ffffff";
            document.getElementById("page-wrap").style.opacity = "0.3";
            document.getElementById("page-wrap").style.pointerEvents = "none";
            
            document.getElementById("footer").style.backgroundColor = "#ffffff";
            document.getElementById("footer").style.opacity = "0.3";
            document.getElementById("footer").style.pointerEvents = "none";
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Open Nav Bar',eventLabel:'Open Nav Bar'});
    });
    
    
    
    /* Set the width of the side navigation to 0 */
    navCloseButton.addEventListener("click",function(e){
            navCloseButton.style.display = "none";
            document.body.style.overflow = "auto";
            document.getElementById("mobile-nav-div").style.width = "0";
            document.getElementById("mobile-nav-div").style.borderRight = "0px";
            
            document.getElementById("page-wrap").style.backgroundColor = "#ffffff";
            document.getElementById("page-wrap").style.opacity = "1.0";
            document.getElementById("page-wrap").style.pointerEvents = "auto";
            
            document.getElementById("footer").style.backgroundColor = "#ffffff";
            document.getElementById("footer").style.opacity = "1.0";
            document.getElementById("footer").style.pointerEvents = "auto";
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Close Nav Bar',eventLabel:'Close Nav Bar'});
            
    });

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-58586733-6', 'auto');
      ga('send', 'pageview');
});


