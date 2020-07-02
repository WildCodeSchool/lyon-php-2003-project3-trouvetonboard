// get all  div where is used for a skill
// console.log("__ Entering in addSkill JS file__")
const debug = false;

function checkHasSkillAsynch(skillBlocks) {
    for (let i = 0; i < skillBlocks.length; i++) {
        // get one skillBlock witch it containt multiple elements
        let aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        let checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        let skillId = aSkill.querySelector('#skillId');
        let linkCheck = aSkill.querySelector('#linkCheck');
        // console.log('___ Add skill ___', linkCheck.value);
        var val = setCheckBox(linkCheck.value, checkbox);
    }
}

async function setCheckBox(link, checkbox) {
    try {
        let datas = null;
        return fetch(link)
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                datas = data;
                if (datas.isChecked) {
                    checkbox.checked = true;
                    // console.log('___ Add Skill ___', ' +++ this skill is checked');
                } else {
                    checkbox.checked = false;
                    // console.log('___ Add Skill ___', ' --- this skill is UNchecked');
                }
                return datas;
            })
        // console.log('___ Add skill Data asynch___', datas);
    } catch (error) {
        console.log('___ Add skill ___', ' Serveur error no repsonse in skill Asych function control');
    }
}


$(document).ready(() => {
    // get all skill group , each skillGroup contain , hiden , label and checkbox
    const skillBlocks = document.querySelectorAll('div[name="skillGroup"]');
    // console.log("___ Add Skill ___" , skillBlocks);
    checkHasSkillAsynch(skillBlocks);
    // for each skillgroup  add event listener
    for (var i = 0; i < skillBlocks.length; i++) {
        // get one skillBlock witch it containt multiple elements
        const aSkill = skillBlocks[i];
        // console.log('___ Add Skill ___', aSkill);
        // get the checkbox in skillblock
        const checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        const linkAdd = aSkill.querySelector('#linkAdd');
        const linkRemove = aSkill.querySelector('#linkRemove');
        const linkCheck = aSkill.querySelector('#linkCheck');
        // add event listener to the checkbox
        checkbox.addEventListener('change', function () {
            // if checkbox is cheked
            if (this.checked) {
                // use fetch to  go  on url  linkadd.value
                console.log("__ Add skill link__", linkAdd)
                fetch(linkAdd.value).then(function (response) {
                    if (response.ok) {
                        if (debug) console.log('___ Add Skill ___', 'Then OK', response);
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
                fetch(linkRemove.value).then(function (response) {
                    console.log("__ Add skill link remove__", linkRemove.value)
                    if (response.ok) {
                        //console.log("___ Add Skill ___", "Then OK", response);
                        response.json().then(function (data) {
                            if (!data.isChecked) {
                                //todo implement errors possibility
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


