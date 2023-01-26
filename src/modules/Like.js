import $ from "jquery"

class Like {
    constructor() {
        this.events()
    }

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this))
    }

    // Methods
ourClickDispatcher(e) {
    var currentLikeBox = $(e.target).closest(".like-box") // when someone clicks on the like box, they may not click on the gray box itself. They may click on the heart icon or the number. In that case, e.target may be the i icon element, and that doesn't contain a data attribute of exists. So we have to find the closest ancestor, parent or grandparent.
    //  For the below we were using (currentLikeBox.data("exists") == "yes") - The reason we had to replace it in the if statement block is becuase it only checks once when the page loads. attr() will notice changes/updates after page load.-- Whenever you need to check for page changes it's ideal to use.
        if (currentLikeBox.attr("data-exists") == "yes") {
            this.deleteLike(currentLikeBox)
            } else {
            this.createLike(currentLikeBox)
            }
    }

    createLike(currentLikeBox) {
        // alert("create test message");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);//this is how you can pass along information with your request. wp is on the lookout for 'X-WP-Nonce'. The second parameter is the actual Nonce. In this case, we created it in functions.php and are accessing it with universityData.nonce.
            },
            url: universityData.root_url + "/wp-json/university/v1/manageLike",
            type: "POST",
            // Data will be equivalent to: /wp-json/university/v1/manageLike?professorId=(value here)
            data: { "professorId": currentLikeBox.data("professor") },
            success: response => {
                currentLikeBox.attr('data-exists', 'yes'); //first is name of attribute you want to work with. Second is what you want to set the value to.
                // with parseInt(), 2nd number is your base, and 10 is commonly used.
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); // this pulls the currentLikeBox's html value, which is a number.
                likeCount++; 
                currentLikeBox.find(".like-count").html(likeCount);//this will update to likeCount (to update the number).
                currentLikeBox.attr("data-like", response); //if we successfully create a like post, the server sends back the id in the response variable.
                console.log(response);
            },
            error: response => {
                console.log(response)
            }
        });
    }

    deleteLike(currentLikeBox) {
        // alert("delete test message");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);//this is how you can pass along information with your request. wp is on the lookout for 'X-WP-Nonce'. The second parameter is the actual Nonce. In this case, we created it in functions.php and are accessing it with universityData.nonce. -- It's used to validate the user who is logged in.
            },
        url: universityData.root_url + "/wp-json/university/v1/manageLike",
        // in single-professor.php we added a data-like property to the span element.
        data: {'like': currentLikeBox.attr('data-like')},
        type: "DELETE",
        success: response => {
            // This is all to reduce the like count number for the post on the front end:
            currentLikeBox.attr('data-exists', 'no'); //first is name of attribute you want to work with. Second is what you want to set the value to.
            // with parseInt(), 2nd number is your base, and 10 is commonly used.
            var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10); // this pulls the currentLikeBox's html value, which is a number.
            likeCount--; 
            currentLikeBox.find(".like-count").html(likeCount);//this will update to likeCount (to update the number).
            currentLikeBox.attr("data-like", ''); //Since we are removing/deleting a like, we are setting the data-like attribute back to being blank as the current user no longer is liking the post.
            console.log(response)
        },
        error: response => {
            console.log(response)
        }
        })
    }
}

export default Like;