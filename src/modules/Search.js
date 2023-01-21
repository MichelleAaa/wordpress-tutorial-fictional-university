// This is for the live search feature. -- Note that during testing -- You have to use npm start in the terminal or else the js isn't going to load correctly.-- for that you must have package.json set up with dependencies. (see file) 

// To use the jQuery library:
import $ from 'jquery';

class Search {
    // 1. Describe and create/initiate our object
    constructor () {
        // This will call the method addSearchHTML so it will load it on the page at the bottom. We have to add this to the top becuase if the HTML code isn't there, then the other variables don't work.
        this.addSearchHTML();
        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger"); //This is to select the class of js-search-trigger
        this.closeButton = $(".search-overlay__close"); 
        this.searchOverlay = $(".search-overlay");
        // It's faster to check the DOM in one area instead of all over the place, so we can just reference this property below instead.
        this.searchField = $("#search-term");
        this.events();
        // To avoid having to read the DOM to check if the class is there, we are just going to keep a variable to track if the class was added or not (aka if the overlay is open or not.)
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;// This keeps track of the state of the spinner becuase we don't want to keep starting it over again each time a key is pressed.
        this.previousValue; //we don't need an initial value.
        this.typingTimer;
    }
    // 2. Events
    // On this.head feels cold, we respond by running wearsHat. That's an example of an event. Then down in 3 - methods, we would have a method called wearsHat. Events taken in the incoming event and calls the function.
    events() {
        //this selects the element, and then calls the on jQuery method.
        // on method - first is the event, in this case, click event. Second is the function you want to call in response.
        this.openButton.on("click", this.openOverlay.bind(this)); 
        // Note that this means something different here in the on method. That's why we have to add .bind(this) to the end.
        this.closeButton.on("click", this.closeOverlay.bind(this));
        // document is the web browsers model for the entire page. -- 
        $(document).on("keyup", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this)); // by default the on method sets this to the thing that triggered the event, instead of the object (so it's pointing towards the main text imput field, instead of the object. so again we have to add .bind(this))
        // note that keydown doesn't give the search field a chance to update it's properly, which is why we are using the keyup event so the internal value of the field definitely has been updated by the browser by then to register the input value.
    }


    // 3. Methods (function, action...)
    typingLogic() {
        // Only if the current value is equal to the previous value do we not want to do anything additional -- becuase sometimes people want to press arrow keys but they aren't actually changing the text yet.
        if (this.searchField.val() != this.previousValue) {
            // setTimeout takes two arguments, first is a function you want to run. You could name the function or create an anonymous function. The second is how many milliseconds you want to wait. (2000 is 2 seconds.)
            // We don't want the setTimeout floating without a way to clear it becuase then they could stack and it could get out of control. -- we added this.typingTimer so we can assign it to a variable, so we can clear it each time another key is pressed.
            clearTimeout(this.typingTimer); //this is how you clear a timer. So we are passing in the name of the timer.
            if (this.searchField.val()) {// if the field has a value
                // While we are waiting for the timeout to pass 2000 milliseconds, the spinner will run so the user doesn't think the system isn't working:
                // We don't want to overwrite it with each keystroke, so we have a property to keep track of the state of the spinner. 
                if (!this.isSpinnerVisible) {
                this.resultsDiv.html('<div class="spinner-loader"></div>');
                this.isSpinnerVisible = true;
                }
                // After 2000 milliseconds, call getResults
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else { // If the field is empty.
                this.resultsDiv.html('');//Empty out the resultsDiv.
                this.isSpinnerVisible = false;
            }
            
        }
        this.previousValue = this.searchField.val();//pulls in the value of the search field.
    }

    getResults() {
        // Within the when(), you can provide as many requests as you want, and the when() method will babysit all the requests and wait for them all to finish before it completes the then() method.
        // If there are two, then the first in when is mapped to the first parameter of then. So you must include matching parameters.
        $.when(
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
            $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
            ).then((posts, pages) => {
            
            // When info comes from when() then(), the data now contains more information than what comes from just a standard getJSON requests. (The first index 0 - is the information itself, while the rest is the extra info. So that's why we are indexing in at 0.)
            let combinedResults = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
            <h2 class="search-overlay__section-title">General Information</h2>
            ${combinedResults.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>" }
            
            ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join("")}

            ${combinedResults.length ? '</ul>' : '' }
            `);
            // <li><a href="${posts[0].link}">${posts[0].title.rendered}</a></li>
            this.isSpinnerVisible = false;
        }, () => {
            this.resultsDiv.html('<p>Unexpected error, please try again.</p>');
        });

        // NOTE THAT THE BELOW WAS REPLACED WITH THE ABOVE:
        // When this code runs below, it overwrites the code where the spinner is. So we need to set the property to false now:
        // this.isSpinnerVisible = false;
        // this.resultsDiv.html("Imagine real search results here...");

        //first arg is the url you want to send a request to. Second argument is the function you want to do after the url responds.
        // Localhost:3000/wp-json/wp/v2/posts?search= this, plus the search term after =, will send a json object back.
        // NOTE: in WP we can only search in posts or pages at one time, not in both. So to get data for both, we have nested $.getJSON requests.
        // $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => {
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val(), pages => {
        //         // After the getJSON method fires, it will pass the data into the function, so we have to include a parameter to get the data that's returned. -- That's called posts
        //     // posts[0].title.rendered -- that pulls in the name of the first wp post from the array of results

        //     // NOTE: in a string block you can't press enter, if you do it will show an error. If you still want to, you could use \ at the end of each line break. Example:
        //     // this.resultsDiv.html('<h2>General Information</h2> \
        //     // <ul>\
        //     // <li></li>\
        //     // </ul>');
        //     // this.resultsDiv.html('<h2>General Information</h2><ul><li></li></ul>');
        //     // Instead of the above, you could use the backtick `` to create a template literal. In between them we can do whatever we want for the most part.
        //     // NOTE: The below is used to combine the results of both posts and pages.
        //     let combinedResults = posts.concat(pages);
        //     this.resultsDiv.html(`
        //     <h2 class="search-overlay__section-title">General Information</h2>
        //     ${combinedResults.length ? '<ul class="link-list min-list>' : "<p>No general information matches that search.</p>" }
            
        //     ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join("")}

        //     ${combinedResults.length ? '</ul>' : '' }
        //     `);
        //     // <li><a href="${posts[0].link}">${posts[0].title.rendered}</a></li>
        //     this.isSpinnerVisible = false;
        //     });
        // });
        // The ES6 arrow function is being used becuase it doesn't change the value of the this keyword. 
    }

    // When the event happens, it will pass certain information on to the keyPressDispatcher function. We call it e.
    keyPressDispatcher(e) {
        // console.log(e.keyCode); -- you can use this to test the keycodes to find the ones you need. -- just open the console in the browser and type the key you need to check.
        // It's possible for keyboard requests to overload the browser if the only condition is to wait for the keypress.
        // It's possible to use jQuery to check if the class we are adding/removing is already there, however, it's costly to deal with the DOM and better to use JS variables, whenever possible.  -- so we have a value in the constructor to store the state of the overlay instead. This only fires if the overlay is not open yet:
        // S key opens the search:-- however, we don't want s to open it when an input or text area is currently in focus (becuase then the person is just trying to type, they aren't trying to open the overlay.) So we use - !$('input, textarea').is(':focus') - for this.
        if(e.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')){
            this.openOverlay();
        }
        // escape key to close the overlay:
        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }

    }
    
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        this.searchField.val('');
        // This adds a class to the body element so that page-scroll doesn't work anymore for the actual page that's slightly visible under the overlay (The class uses overflow: hidden to prevent scrolling):
        $("body").addClass("body-no-scroll");
        // The below will place the cursor in the field, however, we have to do a timeout so the CSS transition is complete before it activates. Most of the time 301 milliseconds should be enough for a css transition.
        setTimeout(() =>  
            this.searchField.focus(), 301);
        this.isOverlayOpen = true; 
    }
    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }

    // This will include the code on the bottom of each page: (You can put it here instead of in the footer.) -- append will ad it to the bottom.
    addSearchHTML(){
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div>
                    <div id="search-overlay__results"></div>
                </div>
            </div>
        `);
    }
}

// This allows us to import our file elsewhere. In this case, it's imported into index.js.
export default Search