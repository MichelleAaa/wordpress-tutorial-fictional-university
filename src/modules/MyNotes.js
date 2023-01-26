import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();//will will cause the code to run when the class is read.
    }

    events() {
        $("#my-notes").on("click", ".delete-note", this.deleteNote); //whenever youc lick anywhere within the parent unordered list, if the interior element matches .delete-note, then fire off this.deleteNote function.
        $("#my-notes").on("click", ".edit-note", this.editNote.bind(this)); // We need this to remain the object, not the element that got clicked on in this case.
        $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
        $(".submit-note").on("click", this.createNote.bind(this));
    }

    // Custom Methods
    editNote(e) {
        var thisNote = $(e.target).parents("li"); // The data attribute of the ID number is in the parent LI element.
        if (thisNote.data("state") == 'editable') {
            //make read only:
            this.makeNoteReadOnly(thisNote);
        } else {
            // Make editable:
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field"); //find is a jquery method. to both of these classes readonly will be removed while note-active-field will be added.
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    }

    makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");
    }

    deleteNote(e) {
        var thisNote = $(e.target).parents("li"); // The data attribute of the ID number is in the parent LI element.
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);//this is how you can pass along information with your request. wp is on the lookout for 'X-WP-Nonce'. The second parameter is the actual Nonce. In this case, we created it in functions.php and are accessing it with universityData.nonce.
            }, // Below we techically called the attrubute on the parent li data-id, but note below we only use 'li', in thisNote.data('id'). You can leave off the data- part.
            url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),//universityData.root_url - this is a property we set up in another lecture. If you view the source code for the front end of the website and search for universityData. It contains the root part of our URL so our code can be flexible here.
            // The last value at the end is the post ID. If you go to http://fictional-university-2.local/wp-json/wp/v2/note/ you can see the ID's in the JSON, but it's also in the URl of the note post in wp-admin.
            type: 'DELETE',
            success: (response) => {
                thisNote.slideUp();//slideUp is a jQuery animation to slide the element up with an animation.
                console.log("congrats");
                console.log(response);
                if (response.userNoteCount < 5) {
                    $(".note-limit-message").removeClass("active");
                }
            }, //this is a function we want to run if the function is successful.
            error: () => {
                // nonce stands for a number used once. Whenever you log into your wp account it can generate a nonce for you.
                // console.log("sorry");
                // console.log(response);
            } //function that will run if the request fails.
        }); // ajax is a great option when you want to control what type of request you are sending. 
    }

    updateNote (e) {
        var thisNote = $(e.target).parents("li"); // The data attribute of the ID number is in the parent LI element.
        var ourUpdatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);//this is how you can pass along information with your request. wp is on the lookout for 'X-WP-Nonce'. The second parameter is the actual Nonce. In this case, we created it in functions.php and are accessing it with universityData.nonce.
            }, // Below we techically called the attrubute on the parent li data-id, but note below we only use 'li', in thisNote.data('id'). You can leave off the data- part.
            url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),//universityData.root_url - this is a property we set up in another lecture. If you view the source code for the front end of the website and search for universityData. It contains the root part of our URL so our code can be flexible here.
            // The last value at the end is the post ID. If you go to http://fictional-university-2.local/wp-json/wp/v2/note/ you can see the ID's in the JSON, but it's also in the URl of the note post in wp-admin.
            type: 'POST',
            data: ourUpdatedPost,
            success: (response) => {
                this.makeNoteReadOnly(thisNote);
            }, //this is a function we want to run if the function is successful.
            error: () => {
                // nonce stands for a number used once. Whenever you log into your wp account it can generate a nonce for you.
            } //function that will run if the request fails.
        }); // ajax is a great option when you want to control what type of request you are sending. 
    }

    createNote (e) {
        var ourNewPost = {
            'title': $(".new-note-title").val(),
            'content': $(".new-note-body").val(),
            'status': 'private'//this will set the note to private status.
            // 'status': 'publish' // note that by default when we add a note via the wp API it is set to draft mode. So here we are setting status to publish so it will show up immediately.
            
            
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);//this is how you can pass along information with your request. wp is on the lookout for 'X-WP-Nonce'. The second parameter is the actual Nonce. In this case, we created it in functions.php and are accessing it with universityData.nonce.
            }, // Below we techically called the attrubute on the parent li data-id, but note below we only use 'li', in thisNote.data('id'). You can leave off the data- part.
            url: universityData.root_url + '/wp-json/wp/v2/note/',//universityData.root_url - this is a property we set up in another lecture. If you view the source code for the front end of the website and search for universityData. It contains the root part of our URL so our code can be flexible here.
            // The last value at the end is the post ID. If you go to http://fictional-university-2.local/wp-json/wp/v2/note/ you can see the ID's in the JSON, but it's also in the URl of the note post in wp-admin.
            type: 'POST',
            data: ourNewPost,
            success: (response) => {
                $(".new-note-title, .new-note-body").val('');
                $(`
                <li data-id="${response.id}">
                    <input readonly class="note-title-field" value="${response.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                    <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
                `).prependTo("#my-notes").hide().slideDown();
                // note that when you create a note through the API it shows up in wp-admin, but it's in draft mode. You would have to go into wp-admin and click published.
            }, //this is a function we want to run if the function is successful.
            error: response => {
                console.log(response);
                if (response.responseText == "You have reached your note limit.") {

                    // if we receive the above error message, then we want to reveal the span that has a message:
                    $(".note-limit-message").addClass("active")
                }
                // nonce stands for a number used once. Whenever you log into your wp account it can generate a nonce for you.
            } //function that will run if the request fails.
        }); // ajax is a great option when you want to control what type of request you are sending. 
    }
}

export default MyNotes;