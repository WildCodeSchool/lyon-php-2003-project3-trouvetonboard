// get all  div where is used for a skill

//console.log("__ Entering in addSkill JS file__")
var debug = false;
$(document).ready(() => {
    if (debug) console.log('__ Add Skill __', 'Document ready');
    if (debug) console.log('__ Add Skill __', 'recupération du  skill group');
    // get all skill group , each skillGroup contain , hiden , label and checkbox
    var skillBlocks = document.querySelectorAll('div[name="skillGroup"]');
    if (debug) console.log('__ Add Skill __', skillBlocks);
    // for each skillgroup  add event listener
    checkHasSkillAsynch(skillBlocks);
    for (var i = 0; i < skillBlocks.length; i++) {
        // get one skillBlock witch it containt multiple elements
        let aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        let checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        let linkAdd = aSkill.querySelector('#linkAdd');
        let linkRemove = aSkill.querySelector('#linkRemove');
        let linkCheck = aSkill.querySelector('#linkCheck');
        if (debug) console.log('__Add Skill__','Profile add skill link :' + linkAdd.value);
        // add event listener to the checkbox
        checkbox.addEventListener('change', function () {
            // if checkbox is cheked
            if (this.checked) {
                // if (debug) console.log('__Add Skill__',
                // 'Skill ID :' + skillId.value + ' IS' + ' CHECKED');
                // use fetch to  go  on url  linkadd.value
                fetch(linkAdd.value).then(function (response) {
                    if (response.ok) {
                        if (debug)console.log('___ Add Skill ___', 'Then OK', response);
                        response.json().then(function (data) {
                            if (data.isChecked) {
                                //todo implement errors possibility
                            }
                        });
                    } else {
                        console.log('Mauvaise réponse du réseau');
                    }
                })
                    .catch(function (error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                    });
            } else {
                if (debug) console.log('__Add Skill__',
                    'Skill ID :' + skillId.value + ' IS UNCHEKED');
                fetch(linkRemove.value).then(function (response) {
                    if (response.ok) {
                        //console.log("___ Add Skill ___", "Then OK", response);
                        response.json().then(function (data) {
                            if (!data.isChecked) {
                                //todo implement errors possibility
                               /* aSkill.style.borderColor = "red";
                                aSkill.style.borderWidth = "1px";
                                aSkill.style.borderStyle = "solid";*/
                            } else {

                            }
                        });
                    }
                })
                    .catch(function (error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                    });
            }
        });
    }
});


function checkHasSkill(skillBlocks) {
    for (var i = 0; i < skillBlocks.length; i++) {
        // get one skillBlock witch it containt multiple elements
        let aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        let checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        //let skillId = aSkill.querySelector('#skillId');
        let linkCheck = aSkill.querySelector('#linkCheck');
        //--------------- check if profile has skill --------------------
        // use fetch to  go  on url  linkadd.value
        fetch(linkCheck.value).then(function (response) {
            if (response.ok) {
                //console.log('___ Add Skill ___', 'Then OK Link check', response);
                response.json().then(function (data) {
                    if (data.isChecked) {
                        checkbox.checked = true;
                        //console.log('___ Add Skill ___', ' +++ this skill is checked');
                    } else {
                        checkbox.checked = false;
                        //console.log('___ Add Skill ___', ' --- this skill is UNchecked');
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
}

/**
 *
 * @param skillBlocks
 */
function checkHasSkillAsynch(skillBlocks) {
    for (var i = 0; i < skillBlocks.length; i++) {
        // get one skillBlock witch it containt multiple elements
        let aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        let checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        //let skillId = aSkill.querySelector('#skillId');
        let linkCheck = aSkill.querySelector('#linkCheck');
        //console.log('___ Add skill ___', linkCheck.value);
        var val = setCheckBox(linkCheck.value,checkbox);
        //console.log('___ Add skill Data asynch___', data);
    }
}

function setCheckBox(link,checkbox)  {

    try{
        let datas = null;
        return fetch(link)
            .then((res) => {
            return res.json();
        })
            .then((data) => {
                datas = data;
                if (datas.isChecked) {
                    checkbox.checked = true;
                    //console.log('___ Add Skill ___', ' +++ this skill is checked');
                } else {
                    checkbox.checked = false;
                    //console.log('___ Add Skill ___', ' --- this skill is UNchecked');
                }
                return datas;
            })
        //console.log('___ Add skill Data asynch___', datas);

        } catch (error) {
            console.log('___ Add skill ___', ' Serveur error no repsonse in skill Asych function control');
        }

}
