// get all  div where is used for a skill
// console.log("__ Entering in addSkill JS file__")
const debug = false;

/* eslint-disable no-param-reassign */
function setCheckBox(link, checkbox) {
    try {
        let datas = null;
        return fetch(link)
            .then((res) => res.json())
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
            });
        // console.log('___ Add skill Data asynch___', datas);
    } catch (error) {
        // console.log('___ Add skill ___', ' Serveur error no repsonse in skill Asych function control');
    }
    return null;
}

async function checkHasSkillAsynch(skillBlocks) {
    // utiliser async + await permet un  retour plus rapide Ainsi que la prise en
    // compte immédiate des demandes de unchek ou chek alors que le chargement
    // est pas terminer, si on utilise pas async + await il  faut attendre la
    // fin du  for pour que les evenements check/uncheck soient pris en comptes.

    for (let i = 0; i < skillBlocks.length; i += 1) {
        // get one skillBlock witch it containt multiple elements
        const aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        const checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        const skillId = aSkill.querySelector('#skillId');
        const linkCheck = aSkill.querySelector('#linkCheck');
        // console.log('___ Add skill ___', linkCheck.value);
        const val = await setCheckBox(linkCheck.value, checkbox);
    }
}


$(document).ready(() => {
    // get all skill group , each skillGroup contain , hiden , label and checkbox
    const skillBlocks = document.querySelectorAll('div[name="skillGroup"]');
    // console.log("___ Add Skill ___" , skillBlocks);
    checkHasSkillAsynch(skillBlocks);
    // for each skillgroup  add event listener
    for (let i = 0; i < skillBlocks.length; i += 1) {
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
        checkbox.addEventListener('change', () => {
            // if checkbox is cheked
            if (this.checked) {
                // use fetch to  go  on url  linkadd.value
                // console.log("__ Add skill link__", linkAdd)
                fetch(linkAdd.value).then( (response) => {
                    if (response.ok) {
                        // console.log('___ Add Skill ___', 'Then OK', response);
                        response.json().then((data) => {
                            if (data.isChecked) {
                                // console.log('___ Add Skill ___ dbwrite OK', aSkill);
                                // todo implement errors possibility
                            } else {
                                // console.log('___ Add Skill ___ dbwrite NOK', aSkill);
                            }
                        });
                    } else {
                        // console.log('Mauvaise réponse du réseau');
                    }
                })
                    .catch((error) => {
                        // console.log('Il y a eu un problème avec l\'opération fetch:
                        // ' + error.message);
                    });
            } else {
                fetch(linkRemove.value).then((response) => {
                    // console.log('__ Add skill link remove__', linkRemove.value);
                    if (response.ok) {
                        // console.log("___ Add Skill ___",
                        // "Then OK", response);
                        response.json().then((data) => {
                            if (data.isChecked) {
                                // console.log('___ rem Skill ___ dbwrite del NOK',
                                // linkRemove.value);
                                // todo implement errors possibility
                            } else {
                                // console.log('___ rem Skill ___ dbwrite del OK',
                                // linkRemove.value);
                            }
                        });
                    }
                })
                    .catch( (error) => {
                        // console.log('Il y a eu un problème avec l\'opération fetch:
                        // ' + error.message);
                    });
            }
        });
    }
});
