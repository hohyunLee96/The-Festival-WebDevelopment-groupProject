const image_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
    let xhr, formData;
    xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', 'http://localhost/api/infoPages/uploadImage');
    xhr.onload = function () {
        let json;
        if (xhr.status != 200) {
            reject('HTTP Error: ' + xhr.status);
            return;
        }
        json = JSON.parse(xhr.responseText);
        if (!json || typeof json.location != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
        }
        resolve(json.location);
    };
    formData = new FormData();
    formData.append('image', blobInfo.blob(), blobInfo.filename());
    xhr.send(formData);
});

tinymce.init({
    selector: '#pageEditor',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable ' +
        'advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss image '
        + 'emoticons ',

    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table ' +
        'mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist ' +
        'numlist bullist indent outdent | emoticons charmap | removeformat |image ',
    tinycomments_mode: 'embedded',
    images_upload_url: 'http://localhost/api/infoPagesImages/uploadImage',
    automaticUploads: false,
    mergetags_list: [
        {value: 'First.Name', title: 'First Name'},
        {value: 'Email', title: 'Email'},
    ], images_upload_handler: image_upload_handler_callback

});


function btnSaveChangesClicked(id) {
    let content = tinymce.get('pageEditor').getContent();
    let title = document.getElementById('pageTitle').value;
    let data = {
        infoPageId: id,
        infoPageTitle: title,
        infoPageContent: content
    };
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/api/infoPages/editInfoPage");
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function () {
        if (xhr.status === 200) {
           location.reload();
        } else {
            displayError(JSON.parse(xhr.response).errorMessage);
        }

    };
    xhr.send(JSON.stringify(data));
}

function displayError(error) {
    let errorDiv = document.getElementById("errors");
    errorDiv.innerHTML = error;
    errorDiv.hidden = false;
}
