/* global $ */
/* eslint-disable no-param-reassign */
/* eslint-disable arrow-parens */
console.log('start');

$('#payment').change(() => {
    const controller = $('#payment').data('link');

    let status = $('#payment option:selected').val();

    const link = controller + "/" + status;
    console.log(link);
    console.log("dedeedefe");

    try {
        return fetch(link)
            .then((data) => {
                return data;
            });
        // console.log('___ Add skill Data asynch___', datas);
    } catch (error) {
        // console.log('___ Add skill ___', ' Serveur error no
        // repsonse in skill Asych function control');
    }
    return null;
});
