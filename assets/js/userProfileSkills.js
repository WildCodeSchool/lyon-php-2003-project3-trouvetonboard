/* global $ */
$(document).ready(() => {
    $('a[name = "categoryLink"]').click(function fct(e) {
        e.preventDefault();
        const divConstainAllCategory = $('#listCategoryDiv');
        divConstainAllCategory.children().removeClass('bg-white border-bottom border-top')
            .addClass('bg-light border-right');
        const idDivSkillNb = $(this).attr('id');
        const idDivSkills = `skills-${idDivSkillNb}`;
        const idDivCategory = `#category-${idDivSkillNb}`;
        const divContainSkills = document.getElementById(idDivSkills);
        const divContainCurrentCategory = $(idDivCategory);
        divContainCurrentCategory.removeClass('bg-light border-right')
            .addClass('bg-white border-style-blue border-bottom border-top');
        const divDestination = $('#tableSkills');
        const divToHide = $('div[name = "divSkillsInCategory"]');
        divDestination.children().removeClass('d-flex').addClass('d-none');
        divDestination.append(divContainSkills);
        divContainSkills.setAttribute('class', 'd-flex');
    });
});
