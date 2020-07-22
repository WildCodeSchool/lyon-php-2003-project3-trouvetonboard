/* global $ */
/* eslint-disable no-param-reassign */
/* eslint-disable arrow-parens */

$('#payment').change(() => {
    const controller = $('#payment').data('link');

    const status = $('#payment option:selected').val();

    const link = `${controller}/${status}`;


    try {
        return fetch(link)
            .then((data) => data);
        // console.log('___ Add skill Data asynch___', datas);
    } catch (error) {
        // console.log('___ Add skill ___', ' Serveur error no
        // repsonse in skill Asych function control');
    }
    return null;
});
