/* global $ */
/* eslint-disable no-param-reassign */
/* eslint-disable arrow-parens */
/* eslint eqeqeq: "off" */

function getSkillList(link, token) {
    try {
        return fetch(link, {
            method: 'post',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: `token=${token}`,
            credentials: 'include',
        })
            .then((res) => res.json())
            .then((data) => data.skillsId);
    } catch (error) {
        // todo  implement message error
    }
    return null;
}

async function checkHasSkillAsynch(skillBlocks) {
    // get the path for obtain skill list
    const way = $('#linkCheck');
    const wayLink = way.data('link');
    const csrsToken = way.data('token');
    const skillsId = await getSkillList(wayLink, csrsToken);
    for (let i = 0; i < skillBlocks.length; i += 1) {
        // get one skillBlock witch it containt multiple elements
        const aSkill = skillBlocks[i];
        // get the checkbox in skillblock
        const checkbox = aSkill.querySelector('input[name="skillCheckbox"]');
        // get the skillid in hidden input
        const skillId = aSkill.querySelector('#skillId').value;

        for (let p = 0; p < skillsId.length; p += 1) {
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
    visibleGroupCategory.data('active', 'true');
    let activeDivNumber = 0;
    const allDivCategoryArray = $('div[name = "skillGroupParent"]');
    const maxSkillGroupCategory = allDivCategoryArray.length;
    allDivCategoryArray.each((index) => {
        if ($(this).data('active')) {
            activeDivNumber = $(this).data('number');
        }
    });

    // get the button next in skills edit for enterprise editBordRequestEnterprise.html.twig
    $('a[id ="category-back"]').click((e) => {
        e.preventDefault();
        let visibleGroupCategoryId = `#skillGroupParent-${activeDivNumber}`;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('form-one-col-block').addClass('d-none');
        visibleGroupCategory.data('active', 'false');
        if (activeDivNumber === 0) {
            activeDivNumber = maxSkillGroupCategory - 1;
        } else {
            activeDivNumber -= 1;
        }
        visibleGroupCategoryId = `#skillGroupParent-${activeDivNumber}`;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('d-none').addClass('form-one-col-block');
        visibleGroupCategory.data('active', 'true');
    });
    // get the button next skills edit for enterprise editBordRequestEnterprise.html.twig
    $('a[id ="category-next"]').click((e) => {
        e.preventDefault();
        let visibleGroupCategoryId = `#skillGroupParent-${activeDivNumber}`;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('form-one-col-block').addClass('d-none');
        visibleGroupCategory.data('active', 'false');
        if (activeDivNumber === maxSkillGroupCategory - 1) {
            activeDivNumber = 0;
        } else {
            activeDivNumber += 1;
        }
        visibleGroupCategoryId = `#skillGroupParent-${activeDivNumber}`;
        visibleGroupCategory = $(visibleGroupCategoryId);
        visibleGroupCategory.removeClass('d-none').addClass('form-one-col-block');
        visibleGroupCategory.data('active', 'true');
    });
}

$(document).ready(() => {
    // check if view page is enterprise or advisor , if data-advisor exist,
    // is advisor and if not exist enterprise
    const way = $('#linkCheck');
    const isAdvisorView = way.data('advisor');
    const token = way.data('token');
    if (!isAdvisorView) {
        buildScreenByCategories();
    }
    // get all skill group , each skillGroup contain , hiden , label and checkbox
    const skillBlocks = document.querySelectorAll('div[name="skillGroup"]');
    checkHasSkillAsynch(skillBlocks);
    // for each skillgroup  add event listener
    for (let j = 0; j < skillBlocks.length; j += 1) {
        // get one skillBlock witch it containt multiple elements
        const aSkill = skillBlocks[j];
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
                fetch(linkAdd.value, {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: `token=${token}`,
                    credentials: 'include',
                }).then((response) => {
                    if (response.ok) {
                        response.json().then((data) => {
                        });
                    }
                })
                    .catch((error) => {
                    });
            } else {
                fetch(linkRemove.value, {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: `token=${token}`,
                    credentials: 'include',
                }).then((response) => {
                    if (response.ok) {
                        response.json().then((data) => {
                        });
                    }
                })
                    .catch((error) => {
                    });
            }
        });
    }
});
