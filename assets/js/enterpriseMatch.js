/* global $ */
$(document).ready(() => {
    const boardRequestActive = $('div[name = "boardRequestActive"]');
    const divConstainAllCategory = $('#allBoardRequest');
    divConstainAllCategory.children().removeClass('bg-white border-bottom border-top  bg-color-styleGuideGray')
        .addClass('bg-tranparent');
    boardRequestActive.removeClass('bg-light border-right')
        .addClass('bg-white border-style-blue border-bottom border-top  bg-color-styleGuideGray');
});
