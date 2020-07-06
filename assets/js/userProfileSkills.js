
$(document).ready(() => {
    $('a[name = "categoryLink"]').click(function (e) {
        e.preventDefault();
        const divConstainAllCategory = $("#listCategoryDiv");
        divConstainAllCategory.children().removeClass("bg-white border-bottom border-top")
            .addClass("bg-light border-right");
        const idDivSkills = "skills-" + $(this).attr('id');
        const idDivCategory= "#category-" + $(this).attr("id");
        const divContainSkills = document.getElementById(idDivSkills);
        const divContainCurrentCategory = $(idDivCategory);
        divContainCurrentCategory.removeClass("bg-light border-right")
            .addClass("bg-white border-style-blue border-bottom border-top");
        const divDestination = $("#tableSkills");
        const divToHide = $('div[name = "divSkillsInCategory"]');
        console.log(divToHide);
        divDestination.children().removeClass("d-flex").addClass("d-none");
        divDestination.append(divContainSkills);
        divContainSkills.setAttribute('class', 'd-flex');
    });
})
