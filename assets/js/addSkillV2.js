// get all  div where is used for a skill
// console.log("__ Entering in addSkill JS file__")

/* global $ */
/* eslint-disable no-param-reassign */
/* eslint-disable arrow-parens */
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
        // console.log('___ Add skill ___', ' Serveur error no
        // repsonse in skill Asych function control');
    }
    return null;
}

function getSkillList(link) {
    try {
        return fetch(link)
            .then((res) => res.json())
            .then((data) => {
                console.log('___ Add Skill ___', data.skillsId[0]);
                return data.skillsId;
            });
    } catch (error) {
         //console.log('___ Add skill ___', ' Serveur error noresponse in skill Asych function control');
    }
    return null;
}

async function checkHasSkillAsynch(skillBlocks) {
        // get the path for obtain skill list
        const way = $('#linkCheck');
        const wayLink = way.data("link");
        console.log('___ Check Skills All ___', wayLink);
        const skillsId = await getSkillList(wayLink);
        console.log(skillsId);
        for (let i = 0; i < skillBlocks.length; i += 1) {
            // get one skillBlock witch it containt multiple elements
            const aSkill = skillBlocks[i];
            // get the checkbox in skillblock
            const checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
            // get the skillid in hidden input
            const skillId = aSkill.querySelector('#skillId').value;
            console.log(" controle pour ", skillId)
            for (let p = 0 ; p< skillsId.length ; p +=1 ) {
                console.log(" controle 2 pour ",skillsId[p])
                if (skillsId[p] == skillId) {
                    checkbox.checked = true;
                }
            }
        }
}

function buildScreenByCategories() {

    const divConstainAllCategory = $('#conatainer-category-skills');
    divConstainAllCategory.children().removeClass('form-one-col-block').addClass('d-none');
    let visibleGroupCategory = $('#skillGroupParent-0');
    visibleGroupCategory.removeClass('d-none').addClass('form-one-col-block');
    console.log(visibleGroupCategory.data('active'));
    visibleGroupCategory.data('active','true');
    console.log(visibleGroupCategory.data('active'));
    let activeDivNumber = 0;
    const allDivCategoryArray = $('div[name = "skillGroupParent"]')
    const maxSkillGroupCategory = allDivCategoryArray.length;
    allDivCategoryArray.each((index)=> {
        if ($(this).data("active")){
            activeDivNumber = $(this).data("number");
        }
    });

    // get the button next in skills edit for enterprise editBordRequestEnterprise.html.twig
    $('a[id ="category-back"]').click((e)=> {
        e.preventDefault();
        console.log("click back")
        let visibleGroupCategoryId = '#skillGroupParent-' + activeDivNumber;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('form-one-col-block').addClass('d-none');
        visibleGroupCategory.data('active', 'false');
        if (activeDivNumber == 0 ) {
            activeDivNumber = maxSkillGroupCategory - 1;
        } else {
            activeDivNumber -= 1;
        }
        visibleGroupCategoryId = '#skillGroupParent-' + activeDivNumber;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('d-none').addClass('form-one-col-block');
        visibleGroupCategory.data('active', 'true');
    });
    // get the button next skills edit for enterprise editBordRequestEnterprise.html.twig
    $('a[id ="category-next"]').click((e)=> {
        e.preventDefault();
        console.log("click next")
        let visibleGroupCategoryId = '#skillGroupParent-' + activeDivNumber;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('form-one-col-block').addClass('d-none');
        visibleGroupCategory.data('active', 'false');
        if(activeDivNumber == maxSkillGroupCategory-1) {
            activeDivNumber = 0;
        } else {
            activeDivNumber += 1;
        }
        visibleGroupCategoryId = '#skillGroupParent-' + activeDivNumber;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('d-none').addClass('form-one-col-block');
        visibleGroupCategory.data('active', 'true');
    });



}

$(document).ready(() => {
    buildScreenByCategories();
    // get all skill group , each skillGroup contain , hiden , label and checkbox
    const skillBlocks = document.querySelectorAll('div[name="skillGroup"]');
    // console.log("___ Add Skill ___" , skillBlocks);
    checkHasSkillAsynch(skillBlocks);
    // for each skillgroup  add event listener
    for (let j = 0; j < skillBlocks.length; j += 1) {
        // get one skillBlock witch it containt multiple elements
        const aSkill = skillBlocks[j];
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
            if (checkbox.checked) {
                // use fetch to  go  on url  linkadd.value
                // console.log("__ Add skill link__", linkAdd)
                fetch(linkAdd.value).then((response) => {
                    if (response.ok) {
                        // console.log('___ Add Skill add___', 'Then OK', response);
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
                    .catch((error) => {
                        // console.log('Il y a eu un problème avec l\'opération fetch:
                        // ' + error.message);
                    });
            }
        });
    }
});
