// get all  div where is used for a skill

console.log("__ Entering in addSkill JS file__")
var debug = true;
$(document).ready(() => {
    if (debug)console.log("__ Add Skill __","Document ready");

    if (debug) console.log("__ Add Skill __", "recupération du  skill group");

    // get all skill group , each skillGroup contain , hiden , label and checkbox
    var skillBlocks = document.querySelectorAll('#skillGroup');
    //if (debug) console.log("__ Add Skill __", skillBlocks);
    // for each skillgroup  add event listener
    for( var i = 0 ; i<skillBlocks.length; i++){
        // get one skillBlock witch it containt multiple elements
        let aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        let checkbox = aSkill.querySelector('#skillCheckbox');
        // get the skillid in hidden input
        let skillId = aSkill.querySelector('#skillId');
        // add event listener to the checkbox
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                if (debug) console.log("__Add Skill__",
                    "Skill ID :" + skillId.value + " IS" + " CHECKED");
            } else {
                if (debug)console.log("__Add Skill__",
                    "Skill ID :"+ skillId.value +" IS UNCHEKED");
            }
        });
        console.log(aSkill.querySelector('#skillId'));

    }


});

/*
for (var i = 0; i < watchListIcons.length; i++) {
    watchListIcons[i].addEventListener('click', addToWatchlist);
}
*/
/*
function addToWatchlist(event) {

// Get the link object you click in the DOM
    let watchlistIcon = event.target;
    let link = watchlistIcon.dataset.href;
    //console.log("----- Module Watchlist add and remove favorite link---", link);
    //link = link.replace("\"","");
    console.log("----- Module Watchlist add and remove favorite link cote del---", link);
// Send an HTTP request with fetch to the URI defined in the href
    /* fetch(link)
         .then(function (res) {
             console.log("----- Module Watchlist add and remove favorite ---","Then OK",res);

             watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
             watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
         });
 */ /*
    fetch(link).then(function (response) {
        if (response.ok) {
            console.log("----- Module Watchlist add and remove favorite ---", "Then OK", response);
            response.json().then(function (data) {
                if (data.isInWatchlist) {
                    watchlistIcon.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
                    watchlistIcon.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
                } else {
                    watchlistIcon.classList.add('far'); // Add the .fas (full heart) from classes in <i> element
                    watchlistIcon.classList.remove('fas'); // Remove the .far (empty heart) from classes in <i> element
                }
            });


        } else {
            console.log('Mauvaise réponse du réseau');
        }
    })
        .catch(function (error) {
            console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
        });
}
*/
/*
function hello() {
    console.log("coucou je suis un script js");
}
*/
